<?php

namespace App\Http\Controllers;

use App\Models\BusinessCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusinessCategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = BusinessCategory::all();
            return response()->json([
                'data' => [
                    'message' => 'Business category berhasil diambil',
                    'business_categories' => $categories
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Business category gagal diambil.'
            ], 400);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Business category gagal dibuat.',
                'errors' => collect($validator->errors())->map(function ($messages, $field) {
                    return ['field' => $field, 'message' => $messages[0]];
                })->values()
            ], 400);
        }

        try {
            $category = BusinessCategory::create($request->all());
            return response()->json([
                'message' => 'Business category berhasil dibuat',
                'data' => $category
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Business category gagal dibuat.'
            ], 400);
        }
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Business category gagal diupdate.',
                'errors' => collect($validator->errors())->map(function ($messages, $field) {
                    return ['field' => $field, 'message' => $messages[0]];
                })->values()
            ], 400);
        }

        try {
            $category = BusinessCategory::findOrFail($id);
            $category->update($request->all());
            return response()->json([
                'message' => 'Business category berhasil diupdate',
                'data' => $category
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Business category gagal diupdate.'
            ], 400);
        }
    }

    public function destroy(string $id)
    {
        try {
            $category = BusinessCategory::findOrFail($id);
            $category->delete();
            return response()->json([
                'message' => 'Business category berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Business category gagal dihapus.'
            ], 400);
        }
    }
}
