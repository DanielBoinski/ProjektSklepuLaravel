@extends('layouts.app')

@section('title', 'Moje zamówienia')

@section('content')

    <div class="card">
        <h1>Moje zamówienia</h1>
        <p class="mt-2 text-muted">
            Tutaj znajdziesz historię wszystkich zamówień złożonych w sklepie.
        </p>

        <div class="mt-3">
            <a href="{{ route('client.dashboard') }}" class="btn btn-secondary">
                ⮐ Powrót do panelu klienta
            </a>
            <a href="{{ route('shop') }}" class="btn btn-primary" style="margin-left: 8px;">
                🛍 Przejdź do sklepu
            </a>
        </div>
    </div>

    <div class="card">
        @if ($orders->isEmpty())
            <p>Nie masz jeszcze żadnych zamówień.</p>
        @else
            <h2 class="home-section-title">Historia zamówień</h2>

            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>Numer zamówienia</th>
                        <th>Data</th>
                        <th>Łączna kwota</th>
                        <th>Produkty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->created_at }}</td>
                            <td>{{ number_format($order->total_price, 2, ',', ' ') }} zł</td>
                            <td>
                                <ul style="padding-left: 18px; margin: 0;">
                                    @foreach ($order->items as $item)
                                        <li>
                                            {{ $item->product->name ?? 'Produkt usunięty' }}
                                            – ilość: {{ $item->quantity }},
                                            cena: {{ number_format($item->price, 2, ',', ' ') }} zł
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

@endsection
