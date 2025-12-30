<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    
    public function index()
    {
        $cart = session()->get('cart', []); 

        return view('cart.index', compact('cart'));
    }

   
    public function add($productId)
    {
        $product = Product::findOrFail($productId);

       
        $cart = session()->get('cart', []);

       
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            
            $cart[$productId] = [
                'name'     => $product->name,
                'price'    => $product->price,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Dodano do koszyka!');
    }

   
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

    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Koszyk jest pusty.');
        }

        try {
            DB::transaction(function () use ($cart) {
                $total = 0;
                $orderItems = [];

                // Sprawdzenie dostępności i obliczenie ceny w transakcji
                foreach ($cart as $productId => $item) {
                    $product = Product::lockForUpdate()->find($productId);

                    if (!$product) {
                        throw new \Exception('Produkt o ID ' . $productId . ' został usunięty ze sklepu.');
                    }

                    if ($product->stock < $item['quantity']) {
                        throw new \Exception('Brak wystarczającej ilości produktu: ' . $product->name);
                    }

                    $total += $product->price * $item['quantity'];

                    $orderItems[] = [
                        'product_id' => $productId,
                        'quantity'   => $item['quantity'],
                        'price'      => $product->price,
                    ];
                }

                // Tworzenie zamówienia
                $order = Order::create([
                    'user_id'     => auth()->id(),
                    'total_price' => $total,
                ]);

                // Tworzenie pozycji zamówienia i dekrementacja stocku
                foreach ($orderItems as $index => $orderItem) {
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $orderItem['product_id'],
                        'quantity'   => $orderItem['quantity'],
                        'price'      => $orderItem['price'],
                    ]);

                    Product::where('id', $orderItem['product_id'])
                        ->decrement('stock', $orderItem['quantity']);
                }
            });

            session()->forget('cart');

            return redirect()
                ->route('client.dashboard')
                ->with('success', 'Zamówienie zostało złożone, a stan magazynu zaktualizowany!');

        } catch (\Exception $e) {
            return redirect()
                ->route('cart.index')
                ->with('error', $e->getMessage());
        }
    }
}
