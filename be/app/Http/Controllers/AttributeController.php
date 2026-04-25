<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttributeController extends Controller
{
    public function index()
    {
        try {
            $attributes = Attribute::all();
            return response()->json([
                'data' => [
                    'message' => 'Attribute berhasil diambil',
                    'attributes' => $attributes
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Attribute gagal diambil.'
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
                'message' => 'Attribute gagal dibuat.',
                'errors' => collect($validator->errors())->map(function ($messages, $field) {
                    return ['field' => $field, 'message' => $messages[0]];
                })->values()
            ], 400);
        }

        try {
            $attribute = Attribute::create($request->all());
            return response()->json([
                'message' => 'Attribute berhasil dibuat',
                'data' => $attribute
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Attribute gagal dibuat.'
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
                'message' => 'Attribute gagal diupdate.',
                'errors' => collect($validator->errors())->map(function ($messages, $field) {
                    return ['field' => $field, 'message' => $messages[0]];
                })->values()
            ], 400);
        }

        try {
            $attribute = Attribute::findOrFail($id);
            $attribute->update($request->all());
            return response()->json([
                'message' => 'Attribute berhasil diupdate',
                'data' => $attribute
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Attribute gagal diupdate.'
            ], 400);
        }
    }

    public function destroy(string $id)
    {
        try {
            $attribute = Attribute::findOrFail($id);
            $attribute->delete();
            return response()->json([
                'message' => 'Attribute berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Attribute gagal dihapus.'
            ], 400);
        }
    }
}
