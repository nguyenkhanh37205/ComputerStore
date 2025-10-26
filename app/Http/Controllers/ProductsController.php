<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products; // Import Model Product

// Cần các Model này để load dữ liệu cho dropdown trong form create/edit
use App\Models\Categories;
use App\Models\Brands;
use Illuminate\Support\Facades\Storage; // Để quản lý việc xóa/lưu ảnh

class ProductsController extends Controller
{
    public function index()
    {
        // Sử dụng Eager Loading để tải Category và Brand
        $products = Products::with(['categories', 'brands'])->get(); 
        
        return view('admin.products.index', compact('products'));
    }

    //---------------------------------------------------------

    public function create()
    {
        // Truyền Category và Brand sang form
        $categories = Categories::all();
        $brands = Brands::all();

        return view('admin.products.create', compact('categories', 'brands'));
    }

    //---------------------------------------------------------

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            // Sửa 'name' thành 'productName' (theo form input name)
            'productName' => 'required|string|max:255', 
            'productDescription' => 'nullable|string', // Dùng tên cột DB
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', 
            // Dùng exists:tên_bảng,tên_khóa_chính
            'categoryId' => 'required|exists:categories,categoryId', 
            'brandId' => 'required|exists:brands,brandId', 
        ]);

        $data = $request->except('_token', 'image'); // Loại trừ image để xử lý riêng
        
        // 1. Xử lý Image Upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        } else {
            $data['image'] = null;
        }
        
        // 2. Map lại dữ liệu nếu form input name khác tên cột DB (Nếu cần)
        // Hiện tại không cần vì validation field đã khớp với DB column name (productName, productDescription, categoryId, brandId)
        
        // 3. Tạo sản phẩm mới
        Products::create($data); 

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    //---------------------------------------------------------

    public function edit($id)
    {
        $product = Products::findOrFail($id);
        $categories = Categories::all();
        $brands = Brands::all();

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    //---------------------------------------------------------

    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'productName' => 'required|string|max:255', 
            'productDescription' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
            'categoryId' => 'required|exists:categories,categoryId', 
            'brandId' => 'required|exists:brands,brandId', 
        ]);

        $product = Products::findOrFail($id);
        $data = $request->except('_token', '_method', 'image');
        
        // 1. Xử lý Image Upload/Cập nhật
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ
            if ($product->image) {
                 Storage::disk('public')->delete($product->image);
            }
            // Lưu ảnh mới
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        // 2. Cập nhật sản phẩm
        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    //---------------------------------------------------------

    public function destroy($id)
    {
        $product = Products::findOrFail($id);
        
        // Xóa ảnh của sản phẩm trước khi xóa bản ghi
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}