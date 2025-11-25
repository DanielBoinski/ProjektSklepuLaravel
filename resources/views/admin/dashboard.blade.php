@extends('layouts.app')

@section('title', 'Panel administratora')

@section('content')
    <div class="card">
        <h1>Panel administratora</h1>
        <p class="mt-2">
            Witaj, <strong>{{ auth()->user()->name }}</strong>
            (rola: <strong>{{ auth()->user()->role }}</strong>)
        </p>
        <p class="text-muted mt-2">
            Z tego miejsca możesz zarządzać użytkownikami, produktami oraz zamówieniami w sklepie.
        </p>
    </div>

    <div class="card">
        <h2 class="home-section-title">Szybkie akcje</h2>
        <p class="home-section-subtitle">
            Wybierz jedną z poniższych opcji, aby przejść do odpowiedniego modułu panelu.
        </p>

        <div class="offer-grid">
            {{-- Kafelek: Użytkownicy --}}
            <div class="offer-card">
                <div class="offer-icon">👤</div>
                <h3>Zarządzanie użytkownikami</h3>
                <p>
                    Dodawaj nowych użytkowników, edytuj istniejących oraz usuwaj konta.
                </p>
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary mt-2">
                    Przejdź
                </a>
            </div>

            {{-- Kafelek: Produkty --}}
            <div class="offer-card">
                <div class="offer-icon">📦</div>
                <h3>Zarządzanie produktami</h3>
                <p>
                    Dodawaj nowe produkty, aktualizuj ceny, opisy oraz zdjęcia asortymentu.
                </p>
                <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">
                    Przejdź
                </a>
            </div>

            {{-- Kafelek: Zamówienia --}}
            <div class="offer-card">
                <div class="offer-icon">🧾</div>
                <h3>Wszystkie zamówienia</h3>
                <p>
                    Przeglądaj historię zamówień złożonych przez klientów w sklepie.
                </p>
                <a href="{{ route('admin.orders') }}" class="btn btn-primary mt-2">
                    Przejdź
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <h2 class="home-section-title">Podgląd sklepu</h2>
        <p class="home-section-subtitle">
            Te linki pozwolą Ci zobaczyć, jak wygląda sklep z punktu widzenia użytkownika.
        </p>

        <ul class="mt-2">
            <li>
                <a href="{{ route('home') }}">Strona główna sklepu</a>
            </li>
            <li>
                <a href="{{ route('shop') }}">Pełny asortyment (strona sklepu)</a>
            </li>
        </ul>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-secondary">
                Wyloguj
            </button>
        </form>
    </div>
@endsection
