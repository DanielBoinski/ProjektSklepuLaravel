@extends('layouts.app')

@section('title', 'Panel klienta')

@section('content')

    {{-- Sekcja powitalna --}}
    <div class="card">
        <h1>Panel klienta</h1>
        <p class="mt-2">
            Witaj, <strong>{{ auth()->user()->name }}</strong>!
        </p>
        <p class="text-muted">
            Tutaj możesz przejrzeć swoje zamówienia, sprawdzić koszyk lub wrócić do zakupów.
        </p>
    </div>

    {{-- Szybkie akcje --}}
    <div class="card">
        <h2 class="home-section-title">Twoje szybkie akcje</h2>
        <p class="home-section-subtitle">
            Najważniejsze funkcje dostępne jednym kliknięciem.
        </p>

        <div class="offer-grid">

            {{-- Kafelek: sklep --}}
            <div class="offer-card">
                <div class="offer-icon">🛍️</div>
                <h3>Przeglądaj sklep</h3>
                <p>
                    Zobacz dostępne produkty i rozpocznij zakupy.
                </p>
                <a href="{{ route('shop') }}" class="btn btn-primary mt-2">
                    Przejdź do sklepu
                </a>
            </div>

            {{-- Kafelek: koszyk --}}
            <div class="offer-card">
                <div class="offer-icon">🛒</div>
                <h3>Twój koszyk</h3>
                <p>
                    Sprawdź produkty dodane do koszyka i złóż zamówienie.
                </p>
                <a href="{{ route('cart.index') }}" class="btn btn-primary mt-2">
                    Zobacz koszyk
                </a>
            </div>

            {{-- Kafelek: historia zamówień --}}
            <div class="offer-card">
                <div class="offer-icon">📦</div>
                <h3>Historia zamówień</h3>
                <p>
                    Zobacz złożone zamówienia i ich status.
                </p>
                <a href="{{ route('client.orders') }}" class="btn btn-primary mt-2">
                    Historia zamówień
                </a>
            </div>

        </div>
    </div>

    {{-- Dodatkowe linki --}}
    <div class="card">
        <h2 class="home-section-title">Dodatkowe opcje</h2>

        <ul class="mt-2">
            <li><a href="{{ route('home') }}">Powrót na stronę główną</a></li>
            <li><a href="{{ route('shop') }}">Przejdź do asortymentu</a></li>
        </ul>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-secondary">
                Wyloguj się
            </button>
        </form>
    </div>

@endsection
