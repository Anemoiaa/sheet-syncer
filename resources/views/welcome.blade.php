<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Sheet Syncer</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <main>
        <div class="container mt-20">
            <div class="flex gap-20 bg-gray-100 p-10 rounded-lg">
                <form action="#" class="flex flex-col">
                    @csrf
                    <label for="generate-rows">Сгенерировать новые строки</label>
                    <select id="generate-rows" class="px-4 py-2 border rounded bg-white">
                        <option>100</option>
                        <option>200</option>
                        <option>500</option>
                        <option>1000</option>
                    </select>
                    <button type="submit" class="mt-4 px-4 py-2 border hover:bg-green-800 hover:text-white cursor-pointer">Сгенерировать</button>
                </form>
                <form action="#" class="flex flex-col">
                    @csrf
                    <label for="sheet-id">Ссылка на таблицу</label>
                    <input id="sheet-id" class="px-4 py-2 border rounded bg-white" type="text" />
                </form>
            </div>
        </div>

        <div class="mt-20 container">
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
                                {{ $row->status }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $row->created_at }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $row->updated_at }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</html>
