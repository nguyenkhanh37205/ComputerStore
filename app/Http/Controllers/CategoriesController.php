<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\File;
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'categoryDescription' => 'nullable|string', 
        ]);

        $data = $request->only('categoryName', 'categoryDescription');
        $data['image'] = null; // Khởi tạo

        // 1. Xử lý Tải ảnh mới
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads');

            // Di chuyển file vào thư mục public/uploads
            $file->move($destinationPath, $fileName);
            
            // Lưu đường dẫn tương đối vào database
            $data['image'] = 'uploads/' . $fileName; 
        }

        Categories::create($data); // SỬ DỤNG $data ĐÃ CÓ ĐƯỜNG DẪN ẢNH

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'categoryDescription' => 'nullable|string', 
        ]);

        $category = Categories::findOrFail($id); 
        $data = $request->only('categoryName', 'categoryDescription');
        
        // Xử lý Ảnh Cập nhật
        if ($request->hasFile('image')) {
            
            // 1. Xóa ảnh cũ
            if ($category->image) {
                 $oldImagePath = public_path($category->image); 
                 if (File::exists($oldImagePath)) {
                     File::delete($oldImagePath); // Sử dụng File::delete an toàn hơn unlink()
                 }
            }
            
            // 2. Lưu ảnh mới
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads');
            $file->move($destinationPath, $fileName);
            
            $data['image'] = 'uploads/' . $fileName;
            
        } else {
            // 3. Giữ nguyên ảnh cũ nếu người dùng không tải file mới
            $data['image'] = $category->image; 
        }

        $category->update($data); // Cập nhật Category

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