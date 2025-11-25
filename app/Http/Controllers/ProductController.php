<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Lista produktów (dla admina i moderatora)
     */
    public function index()
    {
        $products = Product::all();

        return view('products.index', compact('products'));
    }

    /**
     * Formularz dodawania produktu
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Zapis nowego produktu
     */
    public function store(Request $request)
    {
        // walidacja danych, z regexem dla nazwy + obrazek
        $request->validate([
            'name'        => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9ąćęłńóśźżĄĆĘŁŃÓŚŹŻ ]+$/u'],
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048', // maks. ok. 2MB
        ]);

        $imagePath = null;

        // jeśli przesłano plik ze zdjęciem
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();

            // zapisujemy do public/images/products
            $file->move(public_path('images/products'), $filename);

            // ścieżka zapisywana w bazie
            $imagePath = 'images/products/' . $filename;
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

    /**
     * Formularz edycji produktu
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return view('products.edit', compact('product'));
    }

    /**
     * Zapis edycji produktu
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // walidacja z regexem i obrazkiem
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

        // jeśli dodano nowe zdjęcie
        if ($request->hasFile('image')) {

            // usuwamy stare, jeśli istnieje
            if ($product->image_path && file_exists(public_path($product->image_path))) {
                @unlink(public_path($product->image_path));
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/products'), $filename);

            $product->image_path = 'images/products/' . $filename;
        }

        $product->save();

        return redirect()->route('products.index')
            ->with('success', 'Produkt został zaktualizowany.');
    }

    /**
     * Usuwanie produktu
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // usuwamy zdjęcie z dysku, jeśli jest
        if ($product->image_path && file_exists(public_path($product->image_path))) {
            @unlink(public_path($product->image_path));
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produkt został usunięty.');
    }
}
