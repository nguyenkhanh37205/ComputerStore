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
        $request->validate([
            'brandName' => 'required|string|max:100|unique:brands',
            'brandDescription' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only('brandName', 'brandDescription');
        $data['logo'] = null; // Khởi tạo logo

        // Xử lý Logo Upload: SỬ DỤNG move()
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads');

            $file->move($destinationPath, $fileName);

            // LƯU ĐƯỜNG DẪN TƯƠNG ĐỐI VÀO DB
            $data['logo'] = 'uploads/' . $fileName;
        }

        // TẠO BRAND MỚI CHỈ MỘT LẦN
        Brands::create($data);

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
        // ... (Validation giữ nguyên) ...

        $brand = Brands::findOrFail($id);
        $data = $request->only('brandName', 'brandDescription');

        // Xử lý Logo Upload/Cập nhật
        if ($request->hasFile('logo')) {

            // 1. Xóa ảnh cũ khỏi public/uploads
            if ($brand->logo) {
                $oldImagePath = public_path($brand->logo);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // 2. Lưu ảnh mới vào public/uploads
            $file = $request->file('logo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads');
            $file->move($destinationPath, $fileName);

            $data['logo'] = 'uploads/' . $fileName;

        } else {
            // 3. Giữ lại logo cũ nếu không có file mới
            $data['logo'] = $brand->logo;
        }

        // Cập nhật sản phẩm
        $brand->update($data);

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