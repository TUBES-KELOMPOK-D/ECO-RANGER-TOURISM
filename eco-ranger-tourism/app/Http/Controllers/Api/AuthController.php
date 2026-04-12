<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Register user baru.
     * Role otomatis diset ke 'user' (tidak bisa dimanipulasi dari request).
     *
     * POST /api/register
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password, // auto-hashed by model cast 'hashed'
            'role'     => 'user',              // selalu 'user' saat self-register
        ]);

        $token = $user->createToken('eco-ranger-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil.',
            'data'    => [
                'user'         => $user->only(['id', 'name', 'email', 'role', 'created_at']),
                'access_token' => $token,
                'token_type'   => 'Bearer',
            ],
        ], 201);
    }

    /**
     * Login untuk user maupun admin (endpoint sama, role dibedakan dari response).
     *
     * POST /api/login
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // Auth::attempt menggunakan Eloquent dengan parameter binding — aman dari SQL Injection
        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Kredensial tidak cocok. Periksa kembali email dan password Anda.',
            ], 401);
        }

        /** @var User $user */
        $user = Auth::user();

        // Hapus token lama jika ada (opsional — jaga satu sesi aktif)
        // $user->tokens()->delete();

        $token = $user->createToken('eco-ranger-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data'    => [
                'user'         => $user->only(['id', 'name', 'email', 'role']),
                'access_token' => $token,
                'token_type'   => 'Bearer',
            ],
        ], 200);
    }

    /**
     * Logout — hapus token aktif pengguna.
     *
     * POST /api/logout (auth:sanctum)
     */
    public function logout(Request $request): JsonResponse
    {
        // Hanya menghapus token yang sedang digunakan (current token)
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ], 200);
    }

    /**
     * Logout dari semua perangkat — hapus semua token pengguna.
     *
     * POST /api/logout-all (auth:sanctum)
     */
    public function logoutAll(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout dari semua perangkat berhasil.',
        ], 200);
    }
}
