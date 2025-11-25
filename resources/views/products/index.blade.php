@extends('layouts.app')

@section('title', 'Lista produktów')

@section('content')

    <div class="card">
        <h1>Lista produktów</h1>

        {{-- Komunikaty --}}
        @if (session('success'))
            <div class="alert alert-success mt-2">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-3">
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                ➕ Dodaj nowy produkt
            </a>

            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary" style="margin-left: 10px;">
                    ⮐ Panel admina
                </a>
            @elseif(auth()->user()->role === 'moderator')
                <a href="{{ route('moderator.dashboard') }}" class="btn btn-secondary" style="margin-left: 10px;">
                    ⮐ Panel moderatora
                </a>
            @endif
        </div>
    </div>

    <div class="card">
        <h2>Produkty w systemie</h2>

        <table class="table mt-3">
            <thead>
            <tr>
                <th>ID</th>
                <th>Miniatura</th>
                <th>Nazwa</th>
                <th>Cena</th>
                <th>Magazyn</th>
                <th style="width: 220px;">Akcje</th>
            </tr>
            </thead>

            <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>

                    {{-- MINIATURA ZDJĘCIA --}}
                    <td>
                        @if($product->image_path)
                            <img src="{{ asset($product->image_path) }}"
                                 alt="{{ $product->name }}"
                                 style="
                                    width: 60px;
                                    height: 60px;
                                    object-fit: contain;
                                    border-radius: 6px;
                                    background: #F5F5F5;
                                    padding: 2px;
                                 ">
                        @else
                            <div style="
                                width:60px;
                                height:60px;
                                background:#e5e5e5;
                                border-radius:6px;
                                display:flex;
                                justify-content:center;
                                align-items:center;
                                color:#777;
                                font-size:10px;
                            ">
                                brak
                            </div>
                        @endif
                    </td>

                    {{-- NAZWA --}}
                    <td>
                        <strong>{{ $product->name }}</strong>
                    </td>

                    {{-- CENA --}}
                    <td>
                        {{ number_format($product->price, 2, ',', ' ') }} zł
                    </td>

                    {{-- MAGAZYN --}}
                    <td>
                        {{ $product->stock }}
                    </td>

                    {{-- AKCJE --}}
                    <td>
                        <a href="{{ route('products.edit', $product->id) }}"
                           class="btn btn-primary btn-sm">
                            ✏ Edytuj
                        </a>

                        <form action="{{ route('products.destroy', $product->id) }}"
                              method="POST"
                              style="display:inline-block;"
                              onsubmit="return confirm('Na pewno usunąć ten produkt?');">

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger btn-sm">
                                🗑 Usuń
                            </button>
                        </form>
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        Brak produktów w bazie.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

@endsection
