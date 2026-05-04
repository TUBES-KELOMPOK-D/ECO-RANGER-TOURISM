@extends('layouts.app')

@section('title', 'Tambah Kuis Academy')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-8">
        <a href="{{ route('admin.academy.quizzes.index') }}" class="mb-4 inline-flex items-center text-sm font-bold text-slate-500 transition hover:text-slate-800">Kembali ke Kuis</a>
        <h1 class="text-3xl font-extrabold text-slate-900">Tambah Kuis</h1>
        <p class="mt-2 text-sm text-slate-500">Setiap kuis terhubung ke satu materi dan disimpan sebagai JSON sesuai flow user saat ini.</p>
    </div>

    <form action="{{ route('admin.academy.quizzes.store') }}" method="POST">
        @csrf
        @include('admin.academy.quizzes._form')
    </form>
</div>
@endsection
