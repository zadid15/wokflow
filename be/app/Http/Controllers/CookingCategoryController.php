<?php

namespace App\Http\Controllers;

use App\Models\CookingCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CookingCategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = CookingCategory::all();
            return response()->json([
                'data' => [
                    'message' => 'Cooking category berhasil diambil',
                    'cooking_categories' => $categories
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Cooking category gagal diambil.'
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
                'message' => 'Cooking category gagal dibuat.',
                'errors' => collect($validator->errors())->map(function ($messages, $field) {
                    return ['field' => $field, 'message' => $messages[0]];
                })->values()
            ], 400);
        }

        try {
            $category = CookingCategory::create($request->all());
            return response()->json([
                'message' => 'Cooking category berhasil dibuat',
                'data' => $category
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Cooking category gagal dibuat.'
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
                'message' => 'Cooking category gagal diupdate.',
                'errors' => collect($validator->errors())->map(function ($messages, $field) {
                    return ['field' => $field, 'message' => $messages[0]];
                })->values()
            ], 400);
        }

        try {
            $category = CookingCategory::findOrFail($id);
            $category->update($request->all());
            return response()->json([
                'message' => 'Cooking category berhasil diupdate',
                'data' => $category
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Cooking category gagal diupdate.'
            ], 400);
        }
    }

    public function destroy(string $id)
    {
        try {
            $category = CookingCategory::findOrFail($id);
            $category->delete();
            return response()->json([
                'message' => 'Cooking category berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Cooking category gagal dihapus.'
            ], 400);
        }
    }
}
