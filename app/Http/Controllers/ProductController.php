<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

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
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();

           
            $file->move(public_path('images/products'), $filename);

            
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

   
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image_path && file_exists(public_path($product->image_path))) {
            @unlink(public_path($product->image_path));
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produkt został usunięty.');
    }
}
