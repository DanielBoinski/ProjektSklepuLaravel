<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    
    public function index()
    {
        $products = Product::all();

        return view('products.index', compact('products'));
    }

    
    public function create()
    {
        return view('products.create');
    }

   
    public function store(Request $request)
    {
        
        $request->validate([
            'name'        => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9ąćęłńóśźżĄĆĘŁŃÓŚŹŻ ]+$/u'],
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048', 
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name'        => $request->name,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'description' => $request->description,
            'image_path'  => $imagePath,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Produkt został dodany.');
    }

    
    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return view('products.edit', compact('product'));
    }

    
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        
        $request->validate([
            'name'        => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9ąćęłńóśźżĄĆĘŁŃÓŚŹŻ ]+$/u'],
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
        ]);

        $product->name        = $request->name;
        $product->price       = $request->price;
        $product->stock       = $request->stock;
        $product->description = $request->description;

        if ($request->hasFile('image')) {
            // Usunięcie starego obrazu
            if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }

            $product->image_path = $request->file('image')->store('products', 'public');
        }

        $product->save();

        return redirect()->route('products.index')
            ->with('success', 'Produkt został zaktualizowany.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produkt został usunięty.');
    }
}
