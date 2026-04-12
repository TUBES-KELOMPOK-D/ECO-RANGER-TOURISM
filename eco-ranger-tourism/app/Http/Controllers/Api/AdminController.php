<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    /**
     * Daftar semua user dengan pagination dan filter opsional.
     *
     * GET /api/admin/users (auth:sanctum, role:admin)
     *
     * Query params:
     *   - search : cari berdasarkan name atau email
     *   - role   : filter berdasarkan role (user|admin|guest)
     *   - per_page: jumlah per halaman (default: 15)
     */
    public function index(): JsonResponse
    {
        $query = User::query()
            ->select('id', 'name', 'email', 'role', 'created_at');

        // Filter search (name atau email)
        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan role
        if ($role = request('role')) {
            $query->where('role', $role);
        }

        $perPage = (int) request('per_page', 15);
        $users   = $query->latest()->paginate($perPage);

        // Tambahkan total_points untuk setiap user
        $usersData = $users->getCollection()->map(function (User $user) {
            $data                = $user->toArray();
            $data['total_points'] = $user->poin()->sum('points');
            return $data;
        });

        return response()->json([
            'success' => true,
            'message' => 'Daftar user berhasil diambil.',
            'data'    => [
                'users'      => $usersData,
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'per_page'     => $users->perPage(),
                    'total'        => $users->total(),
                    'last_page'    => $users->lastPage(),
                ],
            ],
        ], 200);
    }

    /**
     * Detail user beserta riwayat poin dan voucher.
     *
     * GET /api/admin/users/{id} (auth:sanctum, role:admin)
     */
    public function show(int $id): JsonResponse
    {
        $user = User::with([
            'poin'         => fn ($q) => $q->latest()->take(10),
            'userVouchers' => fn ($q) => $q->with('voucher')->latest('redeemed_at'),
        ])->find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail user berhasil diambil.',
            'data'    => [
                'user'         => $user->only(['id', 'name', 'email', 'role', 'created_at']),
                'total_points' => $user->poin()->sum('points'),
                'points'       => $user->poin->map(fn ($p) => [
                    'id'         => $p->id,
                    'points'     => $p->points,
                    'source'     => $p->source,
                    'created_at' => $p->created_at,
                ]),
                'vouchers'     => $user->userVouchers->map(fn ($uv) => [
                    'voucher_id' => $uv->voucher_id,
                    'name'       => $uv->voucher?->name,
                    'status'     => $uv->status,
                    'redeemed_at'=> $uv->redeemed_at,
                ]),
            ],
        ], 200);
    }

    /**
     * Update role user.
     *
     * PATCH /api/admin/users/{id}/role (auth:sanctum, role:admin)
     */
    public function updateRole(UpdateRoleRequest $request, int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        // Cegah admin mengubah role dirinya sendiri
        if ($user->id === $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat mengubah role akun Anda sendiri.',
            ], 422);
        }

        $user->update(['role' => $request->role]);

        return response()->json([
            'success' => true,
            'message' => 'Role user berhasil diperbarui.',
            'data'    => [
                'user' => $user->only(['id', 'name', 'email', 'role']),
            ],
        ], 200);
    }
}
