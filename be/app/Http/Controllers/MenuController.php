<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function index()
    {
        try {
            $menus = Menu::all();
            return response()->json([
                'data' => [
                    'message' => 'Menu berhasil diambil',
                    'menus' => $menus
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Menu gagal diambil.'
            ], 400);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'business_category_id' => 'required|exists:business_categories,business_category_id',
            'cooking_category_id' => 'required|exists:cooking_categories,cooking_category_id',
            'price' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Menu gagal dibuat.',
                'errors' => collect($validator->errors())->map(function ($messages, $field) {
                    return ['field' => $field, 'message' => $messages[0]];
                })->values()
            ], 400);
        }

        try {
            $menu = Menu::create($request->all());
            return response()->json([
                'message' => 'Menu berhasil dibuat',
                'data' => $menu
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Menu gagal dibuat.'
            ], 400);
        }
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'business_category_id' => 'required|exists:business_categories,business_category_id',
            'cooking_category_id' => 'required|exists:cooking_categories,cooking_category_id',
            'price' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Menu gagal diupdate.',
                'errors' => collect($validator->errors())->map(function ($messages, $field) {
                    return ['field' => $field, 'message' => $messages[0]];
                })->values()
            ], 400);
        }

        try {
            $menu = Menu::findOrFail($id);
            $menu->update($request->all());
            return response()->json([
                'message' => 'Menu berhasil diupdate',
                'data' => $menu
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Menu gagal diupdate.'
            ], 400);
        }
    }

    public function destroy(string $id)
    {
        try {
            $menu = Menu::findOrFail($id);
            $menu->delete();
            return response()->json([
                'message' => 'Menu berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Menu gagal dihapus.'
            ], 400);
        }
    }
}
