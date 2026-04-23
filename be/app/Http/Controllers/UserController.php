<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(8);

        return response()->json([
            'message' => 'User berhasil diambil',
            'data' => $users->items(),
            'pagination' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,cashier,kitchen',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'User gagal ditambahkan', 'errors' => $validator->errors()], 400);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->is_active,
        ]);

        return response()->json([
            'message' => 'User berhasil ditambahkan',
        ], 201);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User gagal diambil'], 404);
        }

        return response()->json([
            'message' => 'User berhasil diambil',
            'data' => $user,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User gagal diupdate'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|min:8',
            'role' => 'required|in:admin,cashier,kitchen',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'User gagal diupdate', 'errors' => $validator->errors()], 400);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->is_active = $request->is_active;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'message' => 'User berhasil diupdate',
        ]);
    }

    public function deactivate($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User gagal dinonaktifkan'], 404);
        }

        $user->is_active = false;
        $user->save();

        return response()->json([
            'message' => 'User berhasil dinonaktifkan',
        ]);
    }

    public function activate($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User gagal diaktifkan'], 404);
        }

        $user->is_active = true;
        $user->save();

        return response()->json([
            'message' => 'User berhasil diaktifkan',
        ]);
    }

    public function getByRole($role)
    {
        $users = User::where('role', $role)->get();

        return response()->json([
            'message' => 'User berdasarkan role berhasil diambil',
            'data' => $users,
        ]);
    }
}
