@php use App\Enums\RowStatusEnum; @endphp
@extends('layouts.app')

@section('content')
    <div class="container mt-10 max-w-xl mx-auto">
        <h1 class="text-2xl font-semibold mb-6">Редактировать строку #{{ $row->id }}</h1>

        @include('partials.alerts')

        <form action="{{ route('update', $row->id) }}" method="POST" class="flex flex-col gap-4 bg-gray-50 p-6 rounded shadow">
            @csrf
            @method('PATCH')

            <div>
                <label for="text" class="block mb-1 text-gray-700 font-semibold">Text</label>
                <input type="text" name="text" id="text" value="{{ old('text', $row->text) }}"
                       class="w-full px-4 py-2 border rounded bg-white shadow-sm @error('text') border-red-500 @enderror">
                @error('text')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="status" class="block mb-1 text-gray-700 font-semibold">Status</label>
                <select name="status" id="status" class="w-full px-4 py-2 border rounded bg-white shadow-sm">
                    @foreach(RowStatusEnum::cases() as $status)
                        <option value="{{ $status->value }}" {{ old('status', $row->status->value) === $status->value ? 'selected' : '' }}>
                            {{ $status->value }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                    Сохранить
                </button>
                <a href="{{ route('home') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                    Назад
                </a>
            </div>
        </form>
    </div>
@endsection
