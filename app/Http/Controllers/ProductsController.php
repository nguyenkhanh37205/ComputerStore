<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
        return view('admin.products.index');
    }
    public function create()
    {
        return view('admin.products.create');
    }
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
        ]);

        // Create a new product
        // Product::create([
        //     'name' => $request->name,
        //     'description' => $request->description,
        //     'price' => $request->price,
        //     'stock' => $request->stock,
        //     'category_id' => $request->category_id,
        //     'brand_id' => $request->brand_id,
        // ]);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }
    public function edit($id)
    {
        // $product = Product::findOrFail($id);
        return view('admin.products.edit'/*, compact('product')*/);
    }
    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
        ]);

        // Update the product
        // $product = Product::findOrFail($id);
        // $product->update($request->only([
        //     'name',
        //     'description',
        //     'price',
        //     'stock',
        //     'category_id',
        //     'brand_id',
        // ]));

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }
    public function destroy($id)
    {
        // $product = Product::findOrFail($id);
        // $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
