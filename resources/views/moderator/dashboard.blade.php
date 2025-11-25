@extends('layouts.app')

@section('title', 'Panel moderatora')

@section('content')
    <div class="card">
        <h1>Panel moderatora</h1>
        <p class="mt-2">
            Witaj, <strong>{{ auth()->user()->name }}</strong>
            (rola: <strong>{{ auth()->user()->role }}</strong>)
        </p>
        <p class="text-muted mt-2">
            Jako moderator możesz zarządzać asortymentem sklepu – dodawać nowe produkty,
            edytować istniejące oraz usuwać te, które nie są już dostępne.
        </p>
    </div>

    <div class="card">
        <h2 class="home-section-title">Szybkie akcje</h2>
        <p class="home-section-subtitle">
            Wybierz jedną z poniższych opcji, aby szybko przejść do najważniejszych funkcji.
        </p>

        <div class="offer-grid">
            {{-- Kafelek: lista produktów --}}
            <div class="offer-card">
                <div class="offer-icon">📦</div>
                <h3>Lista produktów</h3>
                <p>
                    Przeglądaj cały asortyment sklepu, edytuj istniejące produkty
                    lub usuwaj te, które nie są już dostępne.
                </p>
                <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">
                    Przejdź
                </a>
            </div>

            {{-- Kafelek: dodawanie produktu --}}
            <div class="offer-card">
                <div class="offer-icon">➕</div>
                <h3>Dodaj nowy produkt</h3>
                <p>
                    Dodaj nową pozycję do sklepu – ustaw nazwę, cenę, opis,
                    ilość na magazynie oraz zdjęcie produktu.
                </p>
                <a href="{{ route('products.create') }}" class="btn btn-primary mt-2">
                    Dodaj produkt
                </a>
            </div>

            {{-- Kafelek: podgląd sklepu --}}
            <div class="offer-card">
                <div class="offer-icon">🛒</div>
                <h3>Podgląd sklepu</h3>
                <p>
                    Zobacz, jak aktualna oferta wygląda dla zwykłego użytkownika.
                    Możesz sprawdzić, czy opisy i zdjęcia prezentują się poprawnie.
                </p>
                <a href="{{ route('shop') }}" class="btn btn-primary mt-2">
                    Zobacz sklep
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <h2 class="home-section-title">Przydatne linki</h2>

        <ul class="mt-2">
            <li><a href="{{ route('home') }}">Strona główna sklepu</a></li>
            <li><a href="{{ route('shop') }}">Asortyment (widok klienta)</a></li>
        </ul>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-secondary">
                Wyloguj
            </button>
        </form>
    </div>
@endsection
