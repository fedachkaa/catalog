<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AdminPanel | UniSpace')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<header class="header">
    <div class="nav-menu">
        <div class="flex items-center">
            <a href="/" class="text-lg font-bold">UniSpace</a>
        </div>
        <div class="flex flex-row">
            <div class="mx-2">
                <a href="#" class="nav-menu-item">Університети</a>
            </div>
            <div class="mx-2">
                <a href="#" class="nav-menu-item">Користувачі</a>
            </div>
            <div class="mx-2">
                <a href="{{ route('logout') }}" class="nav-menu-item">Вийти</a>
            </div>
        </div>
    </div>
</header>

<div class="container mx-auto mt-4">
    @yield('content')
    @stack('scripts')
</div>
</body>
</html>
