<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Insta') }} | @yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    {{-- Customized CSS --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    {{-- [SOON] Search bar here. Show it when user logs in. --}}
                    @auth
                        @if (!request()->is('admin/*'))
                            <ul class="navbar-nav ms-auto">
                                <form action="{{ route('search') }}" style="width: 300px">
                                    <input type="search" name="search" id="search" class="form-control form-control-sm" placeholder="Search users...">
                                </form>
                            </ul>
                        @endif
                    @endauth


                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
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
                            {{-- Home --}}
                                <li class="nav-item" title="Home">
                                    <a href="{{ route('index') }}" class="nav-link">
                                        <i class="fa-solid fa-house text-dark icon-sm"></i>
                                    </a>
                                </li>
                            {{-- Create Post --}}
                            <li class="nav-item" title="Create Post">
                                <a href="{{ route('post.create') }}" class="nav-link">
                                    <i class="fa-solid fa-circle-plus text-dark icon-sm"></i>
                                </a>
                            </li>

                            {{-- notifications --}}
                            <li class="nav-item dropdown position-relative">
                                <button id="account-dropdown" class="btn shadow-none nav-link" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-bell text-dark icon-sm"></i>
                                </button>
                                <span class="position-absolute translate-middle badge rounded-pill bg-danger"
                                      style="top: 20%; left: 90%; z-index: 10">
                                    {{ $total_notifications }}
                                    <span class="visually-hidden">unread messages</span>
                                </span>

                                {{-- dropdown items - notifications --}}
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="notification-dropdown">
                                    @if ($notificationDetails->isNotEmpty())
                                        @php
                                            $index = 0
                                        @endphp
                                        @foreach ($notificationDetails as $detail)
                                            @if ($index < 5)
                                                <a href="{{ route('profile.chats', $detail['sender_id']) }}" class="dropdown-item">
                                                    {{ $detail['count'] }} notification{{ $detail['count'] == 1 ? 's' : '' }} from {{ $detail['sender_name'] }}
                                                </a>
                                                @php
                                                    $index++;
                                                @endphp
                                            @elseif ($index === 5)
                                                <a href="{{ route('profile.notifications', $user->id) }}" class="dropdown-item text-center text-primary fw-bold">
                                                    View All {{ $notifications_count }} notification{{ $notifications_count == 1 ? 's' : '' }}
                                                </a>
                                                @break
                                            @endif
                                        @endforeach
                                    @else
                                        <span class="dropdown-item text-muted">No new messages</span>
                                    @endif

                                    <a href="{{ route('requests') }}" class="dropdown-item">
                                        {{ $requests_count }} follow request{{ $requests_count == 1 ? 's' : '' }}
                                    </a>

                                </div>
                            </li>

                            {{-- Account --}}
                            <li class="nav-item dropdown">
                                {{-- <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a> --}}

                                <button id="account-dropdown" class="btn shadow-none nav-link" data-bs-toggle="dropdown">
                                    @if (Auth::user()->avatar)
                                        <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="rounded-circle avatar-sm">
                                    @else
                                        <i class="fa-solid fa-circle-user text-dark icon-sm"></i>
                                    @endif
                                </button>


                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="account-dropdown">
                                    {{-- Use Gate 'admin' - this checks if the currently logged-in user is allowed to perform the "admin" action.
                                         If the gate returns truem the content inside @can is displayed. Otherwise, it is hidden. --}}
                                    @can('admin')
                                        {{--  Admin --}}
                                        <a href="{{ route('admin.users') }}" class="dropdown-item">
                                            <i class="fa-solid fa-user-gear"></i> Admin
                                        </a>
                                        <hr class="dropdown-divider">
                                    @endcan

                                    {{-- Profie --}}
                                    <a href="{{ route('profile.show', Auth::user()->id) }}" class="dropdown-item">
                                        <i class="fa-solid fa-circle-user"></i> Profile
                                    </a>

                                    {{-- Log out --}}
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fa-solid fa-right-from-bracket"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-5">
            <div class="container">
                <div class="row justify-content-center">
                    {{-- [SOON] Admin Menu --}}
                    @if (request()->is('admin/*'))
                        <div class="col-3">
                            <div class="list-group">
                                {{-- users --}}
                                <a href="{{ route('admin.users') }}"
                                   class="list-group-item
                                         {{ request()->is('admin/users') || request()->is('admin/search*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-users"></i> Users
                                </a>

                                {{-- posts --}}
                                <a href="{{ route('admin.posts') }}"
                                   class="list-group-item
                                         {{ request()->is('admin/posts') ? 'active' : '' }}">
                                    <i class="fa-solid fa-newspaper"></i> Posts
                                </a>

                                {{-- categories --}}
                                <a href="{{ route('admin.categories') }}"
                                   class="list-group-item
                                   {{ request()->is('admin/categories') ? 'active' : '' }}">
                                    <i class="fa-solid fa-tags"></i> Categories
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="col-9">
                        @yield('content')
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
