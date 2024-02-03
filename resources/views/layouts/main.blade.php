<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'UniSpace')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<header class="header">
    <div class="nav-menu">
        <div class="flex items-center">
            <a href="/" class="text-lg font-bold">UniSpace</a>
        </div>
        <div class="flex items-center space-x-4">
            <a href="{{ route('university.create') }}" class="nav-menu-item">Зареєструвати ВНЗ</a>
            @if (auth()->user())
                <a href="{{ route('logout') }}" class="nav-menu-item">Вийти</a>
            @else
                <a href="{{ route('login') }}" class="nav-menu-item">Увійти</a>
            @endif
        </div>
    </div>
</header>
<div class="container mx-auto mt-4">
    @yield('content')
</div>
</body>
</html>
