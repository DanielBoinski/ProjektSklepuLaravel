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

            {{-- LEWA STRONA: logo + główne linki --}}
            <div style="display: flex; align-items: center; gap: 32px;">
                <div class="logo">
                    <a href="{{ route('home') }}" style="color: #ffffff; text-decoration: none;">
                        Sklep<span style="color:#FFB703;">Online</span>
                    </a>
                </div>

                <nav>
                    <a href="{{ route('home') }}">Strona główna</a>
                    <a href="{{ route('shop') }}">Asortyment</a>

                    {{-- Panel admina widoczny TYLKO dla admina --}}
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}">Panel admina</a>
                    @endif
                </nav>
            </div>

            {{-- PRAWA STRONA: logowanie / panel użytkownika / koszyk --}}
            <nav>
                @auth
                    @if(auth()->user()->role === 'client')
                        <a href="{{ route('cart.index') }}">Koszyk</a>
                        <a href="{{ route('client.dashboard') }}">Panel klienta</a>
                    @elseif(auth()->user()->role === 'admin')
                        {{-- Admin ma osobny link po lewej, tutaj można zostawić sam napis --}}
                        <span style="color:#E9F5F0; margin-right: 10px;">Admin</span>
                    @elseif(auth()->user()->role === 'moderator')
                        <a href="{{ route('moderator.dashboard') }}">Panel moderatora</a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-link" style="color:#FFB703;">
                            Wyloguj
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Zaloguj</a>
                    <a href="{{ route('register') }}">Rejestracja</a>
                @endauth
            </nav>

        </div>
    </header>

    <main class="site-main">
        @yield('content')
    </main>

    <footer class="site-footer">
        &copy; {{ date('Y') }} SklepOnline – projekt zaliczeniowy
    </footer>
</div>
</body>
</html>
