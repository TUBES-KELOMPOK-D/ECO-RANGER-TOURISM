@extends('layouts.app')

@section('title', 'Edit Kuis Academy')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-8">
        <a href="{{ route('admin.academy.quizzes.index') }}" class="mb-4 inline-flex items-center text-sm font-bold text-slate-500 transition hover:text-slate-800">Kembali ke Kuis</a>
        <h1 class="text-3xl font-extrabold text-slate-900">Edit Kuis</h1>
        <p class="mt-2 text-sm text-slate-500">{{ $kuis->title }}</p>
    </div>

    <form action="{{ route('admin.academy.quizzes.update', $kuis) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.academy.quizzes._form')
    </form>
</div>
@endsection
