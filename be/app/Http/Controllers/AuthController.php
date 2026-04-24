<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal. Silakan periksa kembali input Anda.',
                'errors' => $validator->errors()
            ], 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau password yang Anda masukkan salah.'
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi admin.'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Selamat datang kembali, ' . $user->name . '! Login berhasil.',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Logout berhasil. Sesi Anda telah diakhiri secara aman.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat melakukan logout.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function profile(Request $request)
    {
        return response()->json([
            'message' => 'Data profil berhasil diambil.',
            'data' => $request->user(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Gagal memperbarui profil. Silakan periksa kembali data yang Anda masukkan.',
                'errors' => $validator->errors()
            ], 400);
        }

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('profile_photos', 'public');
            $user->foto = $path;
        }

        $user->save();

        return response()->json([
            'message' => 'Profil Anda berhasil diperbarui.',
            'data' => $user
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Gagal memperbarui password. Pastikan password baru memenuhi syarat.',
                'errors' => $validator->errors()
            ], 400);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Password saat ini yang Anda masukkan salah.'
            ], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Password berhasil diperbarui secara aman.',
        ]);
    }
}
