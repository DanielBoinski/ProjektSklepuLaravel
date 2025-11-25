@extends('layouts.app')

@section('title', 'Edytuj produkt')

@section('content')

<div class="card auth-wrapper" style="max-width: 600px; margin: 0 auto;">

    <h2>Edytuj produkt</h2>

    {{-- Błędy walidacji --}}
    @if ($errors->any())
        <div class="alert alert-error mb-3">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" 
          action="{{ route('products.update', $product->id) }}" 
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        {{-- Nazwa --}}
        <div class="form-group">
            <label>Nazwa produktu:</label>
            <input type="text" 
                   name="name" 
                   value="{{ old('name', $product->name) }}" 
                   required>
        </div>

        {{-- Cena --}}
        <div class="form-group">
            <label>Cena (zł):</label>
            <input type="number" step="0.01" 
                   name="price" 
                   value="{{ old('price', $product->price) }}" 
                   required>
        </div>

        {{-- Stan magazynu --}}
        <div class="form-group">
            <label>Stan magazynu (ilość):</label>
            <input type="number" 
                   name="stock" 
                   value="{{ old('stock', $product->stock) }}" 
                   required>
        </div>

        {{-- Opis --}}
        <div class="form-group">
            <label>Opis:</label>
            <textarea name="description">{{ old('description', $product->description) }}</textarea>
        </div>

        {{-- Aktualne zdjęcie --}}
        <div class="form-group">
            <label>Aktualne zdjęcie:</label><br>

            @if($product->image_path)
                <img src="{{ asset($product->image_path) }}" 
                     alt="Zdjęcie produktu"
                     style="max-width: 200px; 
                            border-radius: 6px;
                            margin-bottom: 10px;">
            @else
                <p class="text-muted">Brak zdjęcia</p>
            @endif
        </div>

        {{-- Nowe zdjęcie --}}
        <div class="form-group">
            <label>Nowe zdjęcie (opcjonalnie):</label>
            <input type="file" 
                   name="image" 
                   accept="image/*">
        </div>

        {{-- Zapis --}}
        <button type="submit" class="btn btn-primary mt-2">
            Zapisz zmiany
        </button>
    </form>

    <div class="mt-3">
        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            Powrót do listy produktów
        </a>
    </div>

</div>

@endsection
