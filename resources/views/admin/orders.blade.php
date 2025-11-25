@extends('layouts.app')

@section('title', 'Wszystkie zamówienia')

@section('content')

    <div class="card">
        <h1>Wszystkie zamówienia</h1>

        <p class="mt-2 text-muted">
            Poniżej znajduje się lista wszystkich zamówień złożonych w sklepie.
        </p>

        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mt-2">
            ⮐ Powrót do panelu admina
        </a>
    </div>

    <div class="card">
        @if ($orders->isEmpty())
            <p>Brak zamówień w systemie.</p>
        @else

            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>Nr zamówienia</th>
                        <th>Klient</th>
                        <th>Data</th>
                        <th>Łączna kwota</th>
                        <th>Produkty</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            {{-- Numer zamówienia --}}
                            <td>#{{ $order->id }}</td>

                            {{-- Klient --}}
                            <td>
                                {{ $order->user->name ?? 'Użytkownik usunięty' }}  
                                <br>
                                <span class="text-muted" style="font-size: 0.85rem;">
                                    ID: {{ $order->user_id }}
                                </span>
                            </td>

                            {{-- Data --}}
                            <td>{{ $order->created_at }}</td>

                            {{-- Kwota --}}
                            <td>
                                <strong>
                                    {{ number_format($order->total_price, 2, ',', ' ') }} zł
                                </strong>
                            </td>

                            {{-- Produkty --}}
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
