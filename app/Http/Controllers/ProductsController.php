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

        //lấy theo thương hiệu
        $productsByBrand = $products->groupBy(function($product) {
            return $product->brands->brandName; // Giả định brandName là tên thương hiệu
        });

        return view('admin.products.index', compact('products', 'productsByBrand'));
    }

    public function category() 
    {
        // Giả sử tên Model Category là Categories và khóa ngoại là categoryId
        return $this->belongsTo(Categories::class, 'categoryId', 'categoryId');
    }

    // Quan hệ với Thương hiệu (Brand)
    // Tên hàm phải là 'brand' (số ít)
    public function brand() 
    {
        // Giả sử tên Model Brands là Brands và khóa ngoại là brandId
        return $this->belongsTo(Brands::class, 'brandId', 'brandId');
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

   // app/Http/Controllers/ProductsController.php

public function store(Request $request)
{
    // ... (Phần validation giữ nguyên) ...

    $request->validate([
        'productName' => 'required|string|max:255', 
        // ... (các validation khác) ...
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', 
    ]);

    $data = $request->except('_token', 'image');
    
    // Xử lý Image Upload: Thay thế Storage::store() bằng move()
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        // Tạo tên file duy nhất để tránh trùng lặp
        $fileName = time() . '_' . $file->getClientOriginalName();
        // Định nghĩa đường dẫn lưu trữ: PUBLIC/uploads
        $destinationPath = public_path('uploads');

        // Lưu file vào thư mục public/uploads
        $file->move($destinationPath, $fileName);
        
        // Lưu đường dẫn file vào DB (chỉ cần tên file hoặc đường dẫn tương đối)
        // Vì file nằm trong public, ta lưu tên file hoặc đường dẫn 'uploads/tên_file.jpg'
        $data['image'] = 'uploads/' . $fileName; 
    } else {
        $data['image'] = null;
    }
    
    // Tạo sản phẩm mới
    Products::create($data); // Lưu ý: Nên đổi thành Product::create($data);

    return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công.');
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

    // app/Http/Controllers/ProductsController.php

public function update(Request $request, $id)
{
    // ... (Phần validation giữ nguyên) ...

    $product = Products::findOrFail($id); // Nên dùng Route Model Binding: Product $product
    $data = $request->except('_token', '_method', 'image');
    
    // Xử lý Image Upload/Cập nhật
    if ($request->hasFile('image')) {
        
        // Xóa ảnh cũ khỏi public/uploads
        if ($product->image) {
             // Sử dụng unlink() để xóa file trong thư mục public
             $oldImagePath = public_path($product->image); 
             if (file_exists($oldImagePath)) {
                 unlink($oldImagePath);
             }
        }
        // Lưu ảnh mới (giống hàm store)
        $file = $request->file('image');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $destinationPath = public_path('uploads');
        $file->move($destinationPath, $fileName);
        
        $data['image'] = 'uploads/' . $fileName;
    }
    // Ghi chú: Nếu không có file mới, trường image không nên có trong $data để không ghi đè giá trị cũ.
    // Vì bạn dùng $request->except('_token', '_method', 'image'), nên chỉ khi có file mới, $data['image'] mới được thêm vào. -> Logic này là đúng.

    // Cập nhật sản phẩm
    $product->update($data);

    return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công.');
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

        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm thành công.');
    }
}