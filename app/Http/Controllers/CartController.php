<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

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

        $total = 0;

      
        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);

           
            if (!$product) {
                return redirect()
                    ->route('cart.index')
                    ->with('error', 'Produkt o ID ' . $productId . ' został usunięty ze sklepu.');
            }

            
            if ($product->stock < $item['quantity']) {
                return redirect()
                    ->route('cart.index')
                    ->with('error', 'Brak wystarczającej ilości produktu: ' . $product->name);
            }

            
            $total += $product->price * $item['quantity'];
        }

       
        $order = Order::create([
            'user_id'     => auth()->id(),
            'total_price' => $total,
        ]);

        
        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);

            if (!$product) {
             
                continue;
            }

            
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $productId,
                'quantity'   => $item['quantity'],
                'price'      => $product->price, 
            ]);

            
            $product->decrement('stock', $item['quantity']);
           
        }

      
        session()->forget('cart');

        return redirect()
            ->route('client.dashboard')
            ->with('success', 'Zamówienie zostało złożone, a stan magazynu zaktualizowany!');
    }
}
