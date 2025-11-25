@extends('layouts.app')

@section('title', 'Dodaj produkt')

@section('content')
    <div class="card auth-wrapper" style="max-width: 650px; margin: 0 auto;">
        <h1>Dodaj nowy produkt</h1>

        <p class="mt-2 text-muted">
            Uzupełnij poniższy formularz, aby dodać nowy produkt do asortymentu sklepu.
        </p>

        {{-- Błędy walidacji --}}
        @if ($errors->any())
            <div class="alert alert-error mt-3">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ route('products.store') }}"
              enctype="multipart/form-data"
              class="mt-3">
            @csrf

            {{-- Zdjęcie --}}
            <div class="form-group">
                <label for="image">Zdjęcie produktu (opcjonalnie):</label>
                <input type="file"
                       id="image"
                       name="image"
                       accept="image/*">
                <p class="text-muted" style="font-size: 0.85rem; margin-top: 4px;">
                    Obsługiwane są standardowe formaty obrazów (JPG, PNG). Maks. ok. 2 MB.
                </p>
            </div>

            {{-- Nazwa --}}
            <div class="form-group">
                <label for="name">Nazwa produktu:</label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name') }}"
                       required>
            </div>

            {{-- Cena --}}
            <div class="form-group">
                <label for="price">Cena (w zł):</label>
                <input type="number"
                       id="price"
                       name="price"
                       step="0.01"
                       value="{{ old('price') }}"
                       required>
            </div>

            {{-- Stan magazynu --}}
            <div class="form-group">
                <label for="stock">Stan magazynu (ilość sztuk):</label>
                <input type="number"
                       id="stock"
                       name="stock"
                       value="{{ old('stock', 0) }}"
                       required>
            </div>

            {{-- Opis --}}
            <div class="form-group">
                <label for="description">Opis produktu (opcjonalnie):</label>
                <textarea id="description"
                          name="description">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary mt-2">
                Zapisz produkt
            </button>

            <a href="{{ route('products.index') }}" class="btn btn-secondary mt-2" style="margin-left: 6px;">
                Anuluj
            </a>
        </form>
    </div>
@endsection
