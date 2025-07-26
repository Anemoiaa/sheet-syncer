<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sheet Syncer')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex flex-col min-h-screen">
<main class="flex-1">
    @yield('content')
</main>
<footer class="bg-gray-100 py-4 text-center text-gray-500 text-sm">
    &copy; {{ date('Y') }} Sheet Syncer
</footer>
</body>
</html>
