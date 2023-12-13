<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Product') }}</title>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    {{-- @vite('resources/js/app.js') --}}
</head>

<body>
    <div id="app">
        <main class="py-4">
            <nav class="navbar navbar-expand-lg navbar-light bg-light mt-0 mb-5">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <h1 class="font-weight-bold">{{ config('app.name') }}</h1>
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            @if (auth()->user())
                                <?php 
                                    if (auth()->user()->role == 1){
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link" aria-current="page" href="{{ route('user.viewuser') }}">User
                                        Details</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" aria-current="page" href="{{ route('product.index') }}">Product
                                        Details</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" aria-current="page"
                                        href="{{ route('purchase.index') }}">Purchase Details</a>
                                </li>
                                <?php
                                    }

                                    if(auth()->user()->role == 0){
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link" aria-current="page"
                                        href="{{ route('purchase.userindex') }}">Purchase History</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link ml-auto" aria-current="page"
                                        href="{{ route('cart.index') }}">Cart</a>
                                </li>
                                <?php
                                    }
                                ?>
                            @endif
                        </ul>
                        <ul class="navbar-nav ms-auto ml-auto">
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </li>
                                @endif
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @else
                                <div class="text-right">
                                    <li class="nav-item">
                                        <a id="navbarDropdown" class="nav-link" href="#" role="button"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                            {{ Auth::user()->name }}
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </div>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
            @yield('Dashboard')
            <div class="container">
                @yield('content')
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
</body>

</html>
