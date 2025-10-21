<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index()
    {
        return view('admin.categories.index');
    }
    public function create()
    {
        return view('admin.categories.create');
    }
    public function store(Request $request)
    {
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'description' => 'nullable|string',
        // ]);
        // CategoriesController::create([
        //     'name' => $request->name,
        //     'description' => $request->description,
        // ]);
        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }
    public function edit($id)
    {
        // $category = Category::findOrFail($id);
        return view('admin.categories.edit'/*, compact('category')*/);
    }
    public function update(Request $request, $id)
    {
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'description' => 'nullable|string',
        // ]);
        // $category = Category::findOrFail($id);
        // $category->update([
        //     'name' => $request->name,
        //     'description' => $request->description,
        // ]);
        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }
    public function destroy($id)
    {
        // $category = Category::findOrFail($id);
        // $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
    
}
