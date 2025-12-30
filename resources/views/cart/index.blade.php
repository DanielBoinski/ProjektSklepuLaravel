@extends('layouts.app')

@section('title', 'Koszyk')

@section('content')
    <div class="card">
        <h1>Twój koszyk</h1>

        @if (session('success'))
            <div class="alert alert-success mt-2">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-error mt-2">{{ session('error') }}</div>
        @endif

        @if (empty($cart))
            <p class="mt-2">Twój koszyk jest pusty.</p>
            <p class="mt-2"><a href="{{ route('shop') }}" class="btn btn-primary">Przejdź do sklepu</a></p>
        @else
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>Produkt</th>
                        <th>Cena</th>
                        <th>Ilość</th>
                        <th>Razem</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach ($cart as $id => $item)
                        @php $line = $item['price'] * $item['quantity']; $total += $line; @endphp
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ number_format($item['price'], 2, ',', ' ') }} zł</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>{{ number_format($line, 2, ',', ' ') }} zł</td>
                            <td>
                                <form action="{{ route('cart.remove', $id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Usuń</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-right"><strong>Razem:</strong></td>
                        <td><strong>{{ number_format($total, 2, ',', ' ') }} zł</strong></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-3">
                <form action="{{ route('cart.checkout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary">Złóż zamówienie</button>
                </form>
                <a href="{{ route('shop') }}" class="btn btn-secondary" style="margin-left: 8px;">Kontynuuj zakupy</a>
            </div>
        @endif
    </div>
@endsection
