@php use App\Enums\RowStatusEnum; @endphp
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sheet Syncer</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
    <body class="flex flex-col min-h-screen">
        <main class="flex-1">
            @include('partials.alerts')

            <div class="container mt-10">
                <div class="flex flex-col md:flex-row gap-6 bg-gray-50 p-6 rounded-2xl shadow-sm">
                    <form action="{{ route('generate') }}" method="POST" class="flex flex-col gap-2 flex-1">
                        @csrf
                        <label for="generate-rows" class="text-gray-700 font-semibold">Сгенерировать новые строки</label>
                        <select id="generate-rows" name="count" class="px-4 py-2 border rounded bg-white shadow-sm">
                            <option>100</option>
                            <option>200</option>
                            <option>500</option>
                            <option>1000</option>
                        </select>
                        <button type="submit" class="mt-2 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition cursor-pointer">
                            Сгенерировать
                        </button>
                    </form>

                    <form action="#" class="flex flex-col gap-2 flex-1">
                        @csrf
                        <label for="sheet-id" class="text-gray-700 font-semibold">Ссылка на таблицу</label>
                        <input id="sheet-id" type="text" class="px-4 py-2 border rounded bg-white shadow-sm" placeholder="https://docs.google.com/..." />
                        <button type="submit" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition cursor-pointer">
                            Сохранить
                        </button>
                    </form>

                    <form action="{{ route('delete-all') }}" method="POST" class="flex flex-col justify-end flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-800 transition cursor-pointer">
                            Удалить все
                        </button>
                    </form>
                </div>
            </div>

            <div class="my-20 container">
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                ID
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Text
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Created At
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Updated At
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($textRows as $row)
                            <tr class="bg-white border-b border-gray-200">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $row->id }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $row->text }}
                                </td>
                                <td class="px-6 py-4">
                                    <select
                                        name="status"
                                        data-id="{{ $row->id }}"
                                        class="status-select px-2 py-1 border rounded"
                                    >
                                        @foreach(RowStatusEnum::cases() as $status)
                                            <option
                                                value="{{ $status->value }}"
                                                {{ $row->status->value === $status->value ? 'selected' : '' }}
                                            >
                                                {{ $status->value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $row->created_at }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $row->updated_at }}
                                </td>
                                <td class="px-6 py-4 flex flex-col">
                                    <a href="{{ route('edit', $row->id) }}" class="underline">Редактировать</a>
                                    <form action="{{ route('delete', $row->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="underline text-red-600 hover:text-red-800 cursor-pointer">Удалить</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
        <footer class="bg-gray-100 py-10">
            <div class="container">
                <a href="https://github.com/Anemoiaa/sheet-syncer" class="underline">Source Code</a>
            </div>
        </footer>
    </body>
</html>
