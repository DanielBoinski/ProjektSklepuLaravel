<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Wyświetlanie koszyka
     */
    public function index()
    {
        $cart = session()->get('cart', []); // pobierz koszyk z sesji

        return view('cart.index', compact('cart'));
    }

    /**
     * Dodawanie produktu do koszyka
     */
    public function add($productId)
    {
        $product = Product::findOrFail($productId);

        // pobierz aktualny koszyk
        $cart = session()->get('cart', []);

        // jeśli produkt jest już w koszyku → zwiększamy ilość
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            // jeśli nie ma → dodajemy wpis
            $cart[$productId] = [
                'name'     => $product->name,
                'price'    => $product->price,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Dodano do koszyka!');
    }

    /**
     * Usuwanie produktu z koszyka
     */
    public function remove($productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return redirect()
            ->route('cart.index')
            ->with('success', 'Produkt usunięty z koszyka.');
    }

    /**
     * Złożenie zamówienia + aktualizacja stanu magazynu
     */
    public function checkout()
    {
        // pobieramy koszyk z sesji
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Koszyk jest pusty.');
        }

        $total = 0;

        // 1. SPRAWDZAMY STAN MAGAZYNU I LICZYMY SUMĘ
        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);

            // jeśli produkt został usunięty z bazy
            if (!$product) {
                return redirect()
                    ->route('cart.index')
                    ->with('error', 'Produkt o ID ' . $productId . ' został usunięty ze sklepu.');
            }

            // czy mamy wystarczającą ilość na magazynie?
            if ($product->stock < $item['quantity']) {
                return redirect()
                    ->route('cart.index')
                    ->with('error', 'Brak wystarczającej ilości produktu: ' . $product->name);
            }

            // liczymy sumę według aktualnej ceny w bazie
            $total += $product->price * $item['quantity'];
        }

        // 2. TWORZYMY ZAMÓWIENIE
        $order = Order::create([
            'user_id'     => auth()->id(),
            'total_price' => $total,
        ]);

        // 3. TWORZYMY POZYCJE ZAMÓWIENIA I AKTUALIZUJEMY MAGAZYN
        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);

            if (!$product) {
                // teoretycznie już to sprawdziliśmy wyżej, ale na wszelki wypadek
                continue;
            }

            // pozycja zamówienia
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $productId,
                'quantity'   => $item['quantity'],
                'price'      => $product->price, // cena z momentu zakupu
            ]);

            // zmniejszamy stan magazynu
            $product->decrement('stock', $item['quantity']);
            // odpowiednik:
            // $product->stock -= $item['quantity'];
            // $product->save();
        }

        // 4. CZYŚCIMY KOSZYK
        session()->forget('cart');

        return redirect()
            ->route('client.dashboard')
            ->with('success', 'Zamówienie zostało złożone, a stan magazynu zaktualizowany!');
    }
}
