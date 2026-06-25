<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index', ['products' => Product::latest()->get()]);
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'sku'                   => 'required|string|max:100|unique:products',
            'name'                  => 'required|string|max:255',
            'description'           => 'nullable|string',
            'weight'                => 'required|numeric|min:0.01',
            'volume'                => 'required|numeric|min:0.01',
            'requires_cold_storage' => 'boolean',
            'is_hazmat'             => 'boolean',
        ]);

        Product::create($request->only('sku', 'name', 'description', 'weight', 'volume') + [
            'requires_cold_storage' => $request->boolean('requires_cold_storage'),
            'is_hazmat'             => $request->boolean('is_hazmat'),
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'sku'                   => 'required|string|max:100|unique:products,sku,' . $product->id,
            'name'                  => 'required|string|max:255',
            'description'           => 'nullable|string',
            'weight'                => 'required|numeric|min:0.01',
            'volume'                => 'required|numeric|min:0.01',
            'requires_cold_storage' => 'boolean',
            'is_hazmat'             => 'boolean',
        ]);

        $product->update($request->only('sku', 'name', 'description', 'weight', 'volume') + [
            'requires_cold_storage' => $request->boolean('requires_cold_storage'),
            'is_hazmat'             => $request->boolean('is_hazmat'),
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted.');
    }
}
