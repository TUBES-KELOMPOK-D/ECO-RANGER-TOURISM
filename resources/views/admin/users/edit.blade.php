@extends('layouts.app')

@section('title', 'Edit User - Admin Panel')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Edit User</h1>
            <p class="mt-2 text-gray-600">Perbarui data user {{ $user->name }}</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-soft p-6">
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nama -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                        class="w-full px-4 py-2 border @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-toscagreen focus:border-transparent transition"
                        placeholder="Masukkan nama lengkap">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                        class="w-full px-4 py-2 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-toscagreen focus:border-transparent transition"
                        placeholder="example@email.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password (Optional) -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                    <input type="password" id="password" name="password" 
                        class="w-full px-4 py-2 border @error('password') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-toscagreen focus:border-transparent transition"
                        placeholder="Minimal 8 karakter">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                        class="w-full px-4 py-2 border @error('password_confirmation') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-toscagreen focus:border-transparent transition"
                        placeholder="Ulangi password">
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div class="mb-6">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select id="role" name="role" 
                        class="w-full px-4 py-2 border @error('role') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-toscagreen focus:border-transparent transition">
                        <option value="">-- Pilih Role --</option>
                        <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-3">
                    <a href="{{ route('users.index') }}" class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition text-center">
                        Batal
                    </a>
                    <button type="submit" class="flex-1 px-4 py-2 text-white bg-toscagreen rounded-lg hover:bg-opacity-90 transition">
                        Perbarui User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
