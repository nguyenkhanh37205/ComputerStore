<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories; // SỬA: Import Model Category (số ít)

class CategoriesController extends Controller
{
    public function index()
    {
        
        $categories = Categories::all();
        
        return view('admin.categories.index', compact('categories'));
    }

    //---------------------------------------------------------

    public function create()
    {
        return view('admin.categories.create');
    }

    //---------------------------------------------------------

    public function store(Request $request)
    {
        $request->validate([
            'categoryName' => 'required|string|max:100|unique:categories',
            'categoryDescription' => 'nullable|string', 
        ]);

        // SỬA: Gọi Model là Categories::create
        Categories::create($request->only(['categoryName', 'categoryDescription']));

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được tạo thành công.');
    }

    //---------------------------------------------------------

    public function edit($id)
    {
        // SỬA: Gọi Model là Categories::findOrFail
        $category = Categories::findOrFail($id); 
        return view('admin.categories.edit', compact('category'));
    }

    //---------------------------------------------------------

    public function update(Request $request, $id)
    {
        $request->validate([
            'categoryName' => 'required|string|max:100|unique:categories,categoryName,' . $id . ',categoryId', 
            'categoryDescription' => 'nullable|string', 
        ]);

        // SỬA: Gọi Model là Categories::findOrFail
        $category = Categories::findOrFail($id); 
        $category->update($request->only(['categoryName', 'categoryDescription']));

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được cập nhật thành công.');
    }

    //---------------------------------------------------------

    public function destroy($id)
    {
        // SỬA: Gọi Model là Categories::findOrFail
        $category = Categories::findOrFail($id);
        
        // ... (xử lý xóa) ...

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được xóa thành công.');
    }
}