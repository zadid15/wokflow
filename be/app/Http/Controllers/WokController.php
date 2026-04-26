<?php

namespace App\Http\Controllers;

use App\Models\Wok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WokController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Wok::with('stove');

            if ($request->has('stove_id')) {
                $query->where('stove_id', $request->stove_id);
            }

            if ($request->has('search')) {
                $query->whereHas('stove', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            }

            $woks = $query->paginate(10);

            return response()->json([
                'data' => $woks->items(),
                'pagination' => [
                    'total' => $woks->total(),
                    'current_page' => $woks->currentPage(),
                    'last_page' => $woks->lastPage(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Wok gagal diambil',
                'error' => 'Wok sudah ada' // Following prompt's specific error requirement
            ], 400);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'stove_id' => 'required|exists:stoves,stove_id',
            'capacity' => 'required|integer',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Wok gagal ditambahkan',
                'error' => 'Wok sudah ada' // Following prompt's specific error requirement
            ], 400);
        }

        try {
            $wok = Wok::create($request->all());
            return response()->json([
                'message' => 'Wok berhasil ditambahkan',
                'data' => $wok
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Wok gagal ditambahkan',
                'error' => 'Wok sudah ada'
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'stove_id' => 'required|exists:stoves,stove_id',
            'capacity' => 'required|integer',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Wok gagal diupdate',
                'error' => 'Wok sudah ada'
            ], 400);
        }

        try {
            $wok = Wok::findOrFail($id);
            $wok->update($request->all());
            return response()->json([
                'message' => 'Wok berhasil diupdate',
                'data' => $wok
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Wok gagal diupdate',
                'error' => 'Wok sudah ada'
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $wok = Wok::findOrFail($id);
            $wok->delete();
            return response()->json([
                'message' => 'Wok berhasil dihapus',
                'data' => $wok
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Wok gagal dihapus',
                'error' => 'Wok sudah ada'
            ], 400);
        }
    }
}
