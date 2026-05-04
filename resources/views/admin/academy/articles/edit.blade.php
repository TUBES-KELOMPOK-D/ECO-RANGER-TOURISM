@extends('layouts.app')

@section('title', 'Edit Materi Edukasi')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-8">
        <a href="{{ route('admin.academy.articles.index') }}" class="mb-4 inline-flex items-center text-sm font-bold text-slate-500 transition hover:text-slate-800">Kembali ke Materi</a>
        <h1 class="text-3xl font-extrabold text-slate-900">Edit Materi Edukasi</h1>
        <p class="mt-2 text-sm text-slate-500">{{ $artikel->title }}</p>
    </div>

    <form action="{{ route('admin.academy.articles.update', $artikel) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.academy.articles._form')
    </form>
</div>
@endsection
