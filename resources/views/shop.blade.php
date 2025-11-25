@extends('layouts.app')

@section('title', 'Asortyment')

@section('content')

    <div class="card">
        <h1>Asortyment sklepu</h1>

        <form method="GET" action="{{ route('shop') }}" class="mt-3">
            <div class="form-group">
                <label for="q">Szukaj produktu:</label>
                <input type="text"
                       id="q"
                       name="q"
                       value="{{ $search }}"
                       placeholder="np. buty, koszulka...">
            </div>
            <button type="submit" class="btn btn-primary mt-2">Szukaj</button>
        </form>
    </div>

    {{-- GRID PRODUKTÓW --}}
    <div class="card">

        @if($products->isEmpty())
            <p>Brak produktów spełniających kryteria wyszukiwania.</p>
        @else

            <div class="product-grid">

                @foreach($products as $product)
                    <div class="product-card">

                        {{-- ZDJĘCIE --}}
                        @if($product->image_path)
                            <img src="{{ asset($product->image_path) }}"
                                 alt="{{ $product->name }}"
                                 class="product-img">
                        @endif

                        <div class="product-name">{{ $product->name }}</div>

                        <div class="product-price">
                            {{ number_format($product->price, 2, ',', ' ') }} zł
                        </div>

                        @if($product->description)
                            <div class="product-description">
                                {{ $product->description }}
                            </div>
                        @endif

                        <div class="mt-2">
                            @auth
                                @if(auth()->user()->role === 'client')
                                    <a href="{{ route('cart.add', $product->id) }}"
                                       class="btn btn-primary">
                                        Dodaj do koszyka
                                    </a>
                                @else
                                    <span class="text-muted">Zalogowano jako {{ auth()->user()->role }}</span>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-secondary">
                                    Zaloguj się, aby kupić
                                </a>
                            @endauth
                        </div>

                    </div>
                @endforeach

            </div>

            <div class="mt-3">
                {{ $products->links() }}
            </div>

        @endif
    </div>

@endsection
