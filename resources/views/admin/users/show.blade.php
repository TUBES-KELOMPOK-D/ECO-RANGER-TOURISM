@extends('layouts.app')

@section('title', 'Detail User - Admin Panel')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Detail User</h1>
                    <p class="mt-2 text-gray-600">Informasi lengkap user</p>
                </div>
                <a href="{{ route('users.index') }}" class="text-toscagreen hover:text-opacity-80 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- User Card -->
        <div class="bg-white rounded-lg shadow-soft p-8">
            <!-- User Info -->
            <div class="mb-8">
                <div class="flex items-center mb-6">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-toscagreen rounded-full flex items-center justify-center">
                            <span class="text-white text-xl font-semibold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                        <p class="text-gray-600">{{ $user->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 pb-8 border-b border-gray-200">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <p class="text-gray-900">{{ $user->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <p class="text-gray-900">{{ $user->email }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <div>
                        @if ($user->role === 'admin')
                            <span class="inline-block px-3 py-1 text-xs font-semibold text-white bg-red-500 rounded-full">Admin</span>
                        @else
                            <span class="inline-block px-3 py-1 text-xs font-semibold text-white bg-blue-500 rounded-full">User</span>
                        @endif
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Terdaftar Sejak</label>
                    <p class="text-gray-900">{{ $user->created_at->format('d M Y, H:i') }}</p>
                </div>
                @if ($user->eco_level)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Level Eco</label>
                        <p class="text-gray-900">{{ $user->eco_level }}</p>
                    </div>
                @endif
                @if ($user->eco_points)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Poin Eco</label>
                        <p class="text-gray-900">{{ $user->eco_points ?? 0 }} poin</p>
                    </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="flex gap-3">
                <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center px-4 py-2 text-white bg-yellow-600 rounded-lg hover:bg-yellow-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit User
                </a>
                <button onclick="confirmDelete('{{ route('users.destroy', $user) }}')" class="inline-flex items-center px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Hapus User
                </button>
                <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-sm mx-auto p-6">
        <div class="text-center">
            <svg class="w-12 h-12 text-red-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Hapus User?</h3>
            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                Batal
            </button>
            <form id="deleteForm" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700 transition">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmDelete(url) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        form.action = url;
        modal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('deleteModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
</script>
@endsection
