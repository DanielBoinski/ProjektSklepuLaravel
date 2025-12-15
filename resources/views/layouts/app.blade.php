<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sklep internetowy')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<div class="site-wrapper">
    <header class="site-header">
        <div class="header-inner">

            {{-- Logo --}}
            <div class="logo">
                Sklep<span style="color:#FFB703;">Online</span>
            </div>

            {{-- Proste policzenie ilości sztuk w koszyku --}}
            @php
                $cart = session('cart', []);
                $cartCount = 0;
                foreach ($cart as $item) {
                    $cartCount += $item['quantity'];
                }
            @endphp

            <nav>
                <a href="{{ route('home') }}">Strona główna</a>
                <a href="{{ route('shop') }}">Asortyment</a>

                @auth
                    @if(auth()->user()->role === 'client')
                        {{-- Link do koszyka z licznikiem --}}
                        <a href="{{ route('cart.index') }}">
                            Koszyk
                            @if($cartCount > 0)
                                <span class="cart-count-badge">{{ $cartCount }}</span>
                            @endif
                        </a>

                        <a href="{{ route('client.dashboard') }}">Panel klienta</a>
                    @elseif(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}">Panel admina</a>
                    @elseif(auth()->user()->role === 'moderator')
                        <a href="{{ route('moderator.dashboard') }}">Panel moderatora</a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-link" style="color:#E9F5F0;">Wyloguj</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Zaloguj</a>
                    <a href="{{ route('register') }}">Rejestracja</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="site-main">

        {{-- GLOBALNE KOMUNIKATY (flash messages) --}}
        @if (session('success'))
            <div class="alert alert-success mt-2">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error mt-2">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="site-footer">
        &copy; {{ date('Y') }} SklepOnline – projekt zaliczeniowy
    </footer>
</div>
</body>
</html>
