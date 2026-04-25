<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Order::with(['orderItems.orderItemAttributes']);

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by source
            if ($request->has('source')) {
                $query->where('source', $request->source);
            }

            // Search by queue_number, customer_name, customer_phone
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('queue_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%");
                });
            }

            // Sort by status priority and then by created_at DESC
            $query->orderByRaw("FIELD(status, 'pending', 'in_progress', 'partially_ready', 'ready', 'completed', 'cancelled')")
                ->orderBy('created_at', 'desc');

            $orders = $query->paginate(10);

            return response()->json([
                'data' => $orders->items(),
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'total' => $orders->total()
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Order gagal diambil'], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        try {
            $order = DB::transaction(function () use ($request) {
                $source = $request->source;
                $prefix = $source === 'cashier' ? 'C-' : 'W-';

                // Get the last order for this source to determine the next queue number
                $lastOrder = Order::where('source', $source)
                    ->lockForUpdate()
                    ->latest('order_id')
                    ->first();

                $nextNumber = 1;
                if ($lastOrder) {
                    $lastNumber = (int) substr($lastOrder->queue_number, 2);
                    $nextNumber = $lastNumber + 1;
                }

                $queueNumber = $prefix . $nextNumber;

                $order = Order::create([
                    'queue_number' => $queueNumber,
                    'status' => 'pending',
                    'source' => $source,
                    'customer_name' => $request->customer_name,
                    'customer_phone' => $request->customer_phone,
                    'created_by' => auth()->id(),
                ]);

                foreach ($request->order_items as $itemData) {
                    $orderItem = $order->orderItems()->create([
                        'menu_id' => $itemData['menu_id'],
                        'qty' => $itemData['qty'],
                        'status' => 'waiting_queue',
                    ]);

                    if (isset($itemData['order_item_attributes'])) {
                        foreach ($itemData['order_item_attributes'] as $attrData) {
                            $orderItem->orderItemAttributes()->create([
                                'attribute_id' => $attrData['attribute_id'],
                                'value' => $attrData['value'],
                            ]);
                        }
                    }
                }

                return $order->load('orderItems.orderItemAttributes');
            });

            return response()->json([
                'message' => 'Order berhasil dibuat.',
                'data' => $order
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Order gagal dibuat.',
                'error' => $e->getMessage() // Added for debugging during implementation
            ], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, $id)
    {
        try {
            $order = Order::findOrFail($id);
            $data = $request->only(['customer_name', 'customer_phone']);
            $data['updated_by'] = auth()->id();
            $order->update($data);

            return response()->json(['message' => 'Order berhasil diupdate'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Order gagal diupdate'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);

            // Only completed and cancelled orders can be deleted
            if (!in_array($order->status, ['completed', 'cancelled'])) {
                return response()->json(['message' => 'Order gagal dihapus'], 400);
            }

            $order->delete();

            return response()->json(['message' => 'Order berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Order gagal dihapus'], 400);
        }
    }
}
