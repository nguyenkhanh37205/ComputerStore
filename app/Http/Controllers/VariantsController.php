<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Products;
use App\Models\Variant;
use Illuminate\Http\Request;
use App\Models\Variants;
use App\Models\Product; // Cần thiết để chọn sản phẩm gốc
use App\Models\Category; // 💡 FIX 1: Import Model Category
use App\Models\Brands;   // 💡 FIX 2: Import Model Brand
use Illuminate\Support\Facades\Storage;

class VariantsController extends Controller
{
    public function index()
    {
        // Lấy biến thể, kèm thông tin sản phẩm gốc
        $variants = Variant::with('product')->get();
        
        return view('admin.variants.index', compact('variants'));
    }

    //---------------------------------------------------------

    public function create()
    {
        // Truyền danh sách sản phẩm để người dùng chọn biến thể cho sản phẩm nào
        $products = Products::all();
        
        // 💡 FIX 2: Lấy danh sách Categories và truyền vào view
        $categories = Categories::all(); 
        $brands = Brands::all();

        // Truyền cả $products và $categories vào view
        return view('admin.variants.create', compact('products', 'categories', 'brands'));
    }

    //---------------------------------------------------------

    public function store(Request $request)
    {
        // 1. Validate the request data
        $request->validate([
            'productId' => 'required|exists:products,productId', 
            'ram' => 'nullable|string|max:50',
            'rom' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', 
        ]);

        $data = $request->except('_token', 'image'); 
        
        // 2. Xử lý Image Upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('variants', 'public');
            $data['image'] = $imagePath;
        } else {
            $data['image'] = null;
        }
        
        // 3. Tạo biến thể mới
        Variant::create($data);

        return redirect()->route('admin.variants.index')->with('success', 'Biến thể đã được tạo thành công.');
    }

    //---------------------------------------------------------

    public function edit($id)
    {
        $variant = Variant::findOrFail($id);
        $products = Products::all();
        
        return view('admin.variants.edit', compact('variant', 'products'));
    }

    //---------------------------------------------------------

    public function update(Request $request, $id)
    {
        // 1. Validate the request data
        $request->validate([
            'productId' => 'required|exists:products,productId', 
            'ram' => 'nullable|string|max:50',
            'rom' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', 
        ]);

        $variant = Variant::findOrFail($id);
        $data = $request->except('_token', '_method', 'image');
        
        // 2. Xử lý Image Upload/Cập nhật
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ
            if ($variant->image) {
                 Storage::disk('public')->delete($variant->image);
            }
            // Lưu ảnh mới
            $imagePath = $request->file('image')->store('variants', 'public');
            $data['image'] = $imagePath;
        }

        // 3. Cập nhật biến thể
        $variant->update($data);

        return redirect()->route('admin.variants.index')->with('success', 'Biến thể đã được cập nhật thành công.');
    }

    //---------------------------------------------------------

    public function destroy($id)
    {
        $variant = Variant::findOrFail($id);
        
        // Xóa ảnh của biến thể trước khi xóa bản ghi
        if ($variant->image) {
            Storage::disk('public')->delete($variant->image);
        }
        
        $variant->delete();

        return redirect()->route('admin.variants.index')->with('success', 'Biến thể đã được xóa thành công.');
    }
}