<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brands; // Import Model Brand

class BrandsController extends Controller
{
    public function index()
    {
        $brands = Brands::all(); 
        
        return view('admin.brands.index', compact('brands'));
    }

    //---------------------------------------------------------

    public function create()
    {
        return view('admin.brands.create');
    }

    //---------------------------------------------------------

    public function store(Request $request)
    {
        // 1. Validate the request data
        $request->validate([
            'brandName' => 'required|string|max:100|unique:brands', // Tên thương hiệu là duy nhất
            'brandDescription' => 'nullable|string', 
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 2. Tạo Brand mới
        Brands::create([
            'brandName' => $request->brandName,
            'brandDescription' => $request->brandDescription,
            'logo' => $request->file('logo')->store('logos', 'public'),
        ]);

        return redirect()->route('admin.brands.index')->with('success', 'Thương hiệu đã được tạo thành công.');
    }

    //---------------------------------------------------------

    public function edit($id)
    {
        $brand = Brands::findOrFail($id);
        return view('admin.brands.edit', compact('brand'));
    }

    //---------------------------------------------------------

    public function update(Request $request, $id)
    {
        // 1. Validate the request data
        $request->validate([
            // unique:brands,brandName, ngoại trừ brandId hiện tại
            'brandName' => 'required|string|max:100|unique:brands,brandName,' . $id . ',brandId', 
            'brandDescription' => 'nullable|string', 
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 2. Cập nhật Brand
        $brand = Brands::findOrFail($id); 
        $brand->update($request->only([
             'brandName',
             'brandDescription',
             'logo',
        ]));

        return redirect()->route('admin.brands.index')->with('success', 'Thương hiệu đã được cập nhật thành công.');
    }

    //---------------------------------------------------------

    public function destroy($id)
    {
        $brand = Brands::findOrFail($id);
        
        // Kiểm tra xem có sản phẩm nào thuộc thương hiệu này không
        if ($brand->products()->count() > 0) {
            return redirect()->route('admin.brands.index')->with('error', 'Không thể xóa thương hiệu vì vẫn còn sản phẩm liên quan.');
        }

        $brand->delete();

        return redirect()->route('admin.brands.index')->with('success', 'Thương hiệu đã được xóa thành công.');
    }
}