@extends('layouts.app')

@section('title', 'Strona główna')

@section('content')

    {{-- HERO BANER --}}
    <section class="hero-section">
        <div class="hero-box">
            <h1 class="hero-title">Witamy w sklepie <span>SklepOnline</span></h1>

            <p class="hero-subtitle">
                Zakupy online w najlepszych cenach – szybko, wygodnie i bezpiecznie.
            </p>

            <p class="hero-text">
                Oferujemy szeroki wybór produktów dla domu, elektroniki, mody oraz akcesoriów.
                Wszystko w jednym miejscu – sprawdź nasze propozycje!
            </p>

            <a href="{{ route('shop') }}" class="btn btn-hero">
                Przejdź do zakupów
            </a>
        </div>
    </section>



    {{-- DLACZEGO WARTO U NAS KUPOWAĆ --}}
    <section class="card home-section">
        <h2 class="home-section-title">Dlaczego warto nas wybrać?</h2>
        <p class="home-section-subtitle">
            Dbamy o komfort i bezpieczeństwo Twoich zakupów.
        </p>

        <div class="offer-grid">

            <div class="offer-card">
                <div class="offer-icon">⚡</div>
                <h3>Szybka wysyłka</h3>
                <p>Produkty wysyłamy w 24h! Zamówienia docierają ekspresowo.</p>
            </div>

            <div class="offer-card">
                <div class="offer-icon">🔒</div>
                <h3>Bezpieczne zakupy</h3>
                <p>Twoje dane i płatności są w pełni chronione.</p>
            </div>

            <div class="offer-card">
                <div class="offer-icon">💰</div>
                <h3>Atrakcyjne ceny</h3>
                <p>Regularne promocje i konkurencyjne ceny na setki produktów.</p>
            </div>

            <div class="offer-card">
                <div class="offer-icon">⭐</div>
                <h3>Sprawdzone opinie</h3>
                <p>Tylko zadowoleni klienci – dołącz do nich już dziś!</p>
            </div>

        </div>
    </section>



    {{-- WYBRANE 3 LOSOWE PRODUKTY --}}
    <section class="card home-section">
        <h2 class="home-section-title">Polecane produkty</h2>
        <p class="home-section-subtitle">Losowo wybrane propozycje dla Ciebie</p>

        @if ($products->isEmpty())
            <p class="text-muted text-center mt-2">Brak produktów do wyświetlenia.</p>

        @else
            <div class="product-grid mt-3">

                @foreach ($products as $product)
                    <div class="product-card">

                        {{-- Zdjęcie produktu --}}
                        @if($product->image_path)
                            <img src="{{ asset($product->image_path) }}"
                                 alt="{{ $product->name }}"
                                 class="product-img">
                        @endif

                        <div class="product-name">{{ $product->name }}</div>

                        <div class="product-price">
                            {{ number_format($product->price, 2, ',', ' ') }} zł
                        </div>

                        @if ($product->description)
                            <div class="product-description">
                                {{ Str::limit($product->description, 70) }}
                            </div>
                        @endif

                        <div class="mt-2">
                            <a href="{{ route('shop') }}" class="btn btn-primary">
                                Zobacz produkt
                            </a>
                        </div>

                    </div>
                @endforeach

            </div>
        @endif

    </section>



    {{-- CTA – CALL TO ACTION --}}
    <section class="card home-section">
        <h2 class="home-section-title">Gotowy na zakupy?</h2>
        <p class="home-section-subtitle">
            Najlepsze produkty czekają na Ciebie w naszym asortymencie.
        </p>

        <div class="text-center mt-3">
            <a href="{{ route('shop') }}" class="btn btn-primary" style="font-size: 1.1rem; padding: 12px 26px;">
                Przeglądaj produkty →
            </a>
        </div>
    </section>

@endsection
