<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('product.index', compact('products'));
    }

    public function create()
    {
        return view('product.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'part_number' => 'required|unique:products,part_number',
            'name' => 'required',
            'category' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'image_url' => 'nullable|url',
            'description' => 'nullable',
        ]);

        Product::create($request->all());

        return redirect()->route('product.index')->with('success', 'Suku cadang berhasil ditambahkan ke stok.');
    }

    public function edit(Product $product)
    {
        return view('product.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'part_number' => 'required|unique:products,part_number,' . $product->id,
            'name' => 'required',
            'category' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'image_url' => 'nullable|url',
            'description' => 'nullable',
        ]);

        $product->update($request->all());

        return redirect()->route('product.index')->with('success', 'Data suku cadang berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('product.index')->with('success', 'Suku cadang berhasil dihapus dari sistem.');
    }
}
