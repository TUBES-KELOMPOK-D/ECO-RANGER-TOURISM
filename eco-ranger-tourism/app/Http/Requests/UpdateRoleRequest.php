<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Hanya admin yang boleh mengubah role.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    /**
     * Aturan validasi untuk update role.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'role' => ['required', 'string', 'in:user,admin,guest'],
        ];
    }

    /**
     * Pesan error kustom.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'role.required' => 'Role wajib diisi.',
            'role.in'       => 'Role harus salah satu dari: user, admin, guest.',
        ];
    }
}
