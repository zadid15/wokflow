<?php

namespace App\Http\Controllers;

use App\Models\Stove;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoveController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Stove::query();

            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            $stoves = $query->paginate(10);

            return response()->json([
                'data' => $stoves->items(),
                'pagination' => [
                    'total' => $stoves->total(),
                    'current_page' => $stoves->currentPage(),
                    'last_page' => $stoves->lastPage(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Stove gagal diambil',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:stoves,name',
            'is_active' => 'required|boolean',
        ], [
            'name.unique' => 'Nama stove sudah ada'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Stove gagal ditambahkan',
                'error' => $validator->errors()->first()
            ], 400);
        }

        try {
            $stove = Stove::create($request->all());
            return response()->json([
                'message' => 'Stove berhasil ditambahkan',
                'data' => $stove
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Stove gagal ditambahkan',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:stoves,name,' . $id . ',stove_id',
            'is_active' => 'required|boolean',
        ], [
            'name.unique' => 'Nama stove sudah ada'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Stove gagal diupdate',
                'error' => $validator->errors()->first()
            ], 400);
        }

        try {
            $stove = Stove::findOrFail($id);
            $stove->update($request->all());
            return response()->json([
                'message' => 'Stove berhasil diupdate',
                'data' => $stove
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Stove gagal diupdate',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $stove = Stove::findOrFail($id);
            $stove->delete();
            return response()->json([
                'message' => 'Stove berhasil dihapus',
                'data' => $stove
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Stove gagal dihapus',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
