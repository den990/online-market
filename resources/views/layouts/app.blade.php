<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Online Market')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="vh-100 d-flex flex-column">
<div id="app" class="flex-grow-1">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="{{route('home.index')}}">Online Market</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home.index') ? 'active' : '' }}"
                                            aria-current="page"
                                            href="{{ route('home.index') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('about.index') ? 'active' : '' }}"
                                            href="{{route('about.index')}}">About</a></li>

                    @if (Auth::check() && Auth::user()->isAdmin())
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}"
                                                href="{{ route('admin.index') }}">Admin Panel</a></li>
                    @else:
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}"
                                            href="{{ route('support.index') }}">Support</a></li>
                    @endif

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">Shop</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#!">All Products</a></li>
                            <li>
                                <hr class="dropdown-divider"/>
                            </li>
                            <li><a class="dropdown-item" href="#!">Popular Items</a></li>
                            <li><a class="dropdown-item" href="#!">New Arrivals</a></li>
                        </ul>
                    </li>
                </ul>
                @if (!Auth::check())
                    <a class="btn btn-outline-primary" href="{{route('login')}}">
                        Login
                    </a>

                    <a class="ms-2 btn btn-primary" href="{{route('register')}}">
                        Sign up
                    </a>
                @endif

                @if (Auth::check())

                    <a class=" ms-2  btn btn-outline-dark" href="{{route('cart.index')}}">
                        <i class="bi-cart-fill me-1"></i>
                        Cart
                        <span class="badge bg-dark text-white ms-1 rounded-pill" id="cart-count">{{ Auth::user()->cartItems->count() }}</span>
                    </a>


                    <div class="dropdown ms-2">
                        <a href="#" class="d-flex align-items-center text-black text-decoration-none dropdown-toggle"
                           id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ auth()->user()->avatar_path }}" alt="hugenerd" width="30" height="30"
                                 class="rounded-circle">
                            <span class="d-none d-sm-inline mx-1">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.index') }}">{{ __('Profile') }}</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('orders.index') }}">{{ __('My orders') }}</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                    {{ __('Exit') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>
</div>

<footer class="py-5 bg-dark flex-shrink-0">
    <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Online Market(prod by den990) 2024</p>
    </div>
</footer>
</body>
</html>
