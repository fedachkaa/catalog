<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'UniSpace')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="{{ asset('js/general.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <nav class="nav">
        <div>
            <a href="{{ route('home') }}" class="text-2xl font-bold">
                Unispace
            </a>
        </div>
        <ul class="menu">
            <li><a href="{{ route('home') }}">Головна</a></li>
            <li><a href="#">Про нас</a></li>
            @if (auth()->user())
            <li class="sub-menu"><a href="#">{{ auth()->user()->getFirstName() }}</a>
                <ul>
                    <li><a href="{{ route('user.profile') }}">
                            <span>Профіль</span>
                            <i class="fa fa-user"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('logout') }}">
                            <span>Вийти</span>
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </a>
                    </li>
                </ul>
            </li>
            @else
            <li><a href="{{ route('login') }}">Увійти</a></li>
            @endif
        </ul>
    </nav>

    @include('general.spinner')

    <div class="container">
        @yield('content')
        @stack('scripts')
    </div>
</body>

</html>
