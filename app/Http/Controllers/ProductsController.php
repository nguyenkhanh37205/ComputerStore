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
        $productsByBrand = $products->groupBy(function ($product) {
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

    // thêm sản phẩm
    public function create()
    {
        // Truyền Category và Brand sang form
        $categories = Categories::all();
        $brands = Brands::all();

        return view('admin.products.create', compact('categories', 'brands'));
    }

    // thêm sản phẩm
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

    // chỉnh sửa sản phẩm
    public function edit($id)
    {
        $product = Products::findOrFail($id);
        $categories = Categories::all();
        $brands = Brands::all();

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    //---------------------------------------------------------

    // cập nhật sản phẩm
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

    //xóa sản phẩm
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

    // Tìm kiếm sản phẩm với lọc category và brand
    public function search(Request $request)
    {
        $category = $request->input('categories'); // ?category
        $brand = $request->input('brands');       // ?brand
        $query = $request->input('query');       // ?query

        $products = Products::query();

        // Lọc theo category
        if ($category) {
            $products->whereHas('categories', function ($q) use ($category) {
                $q->where('categoryName', $category);
            });
        }

        // Lọc theo brand
        if ($brand) {
            $products->whereHas('brands', function ($q) use ($brand) {
                $q->where('brandName', $brand);
            });
        }

        // Tìm kiếm theo từ khóa
        if ($query) {
            $products->where('productName', 'LIKE', "%{$query}%");
        }

        // Lấy dữ liệu cùng category và brand
        $products = $products->with(['categories', 'brands'])->get();

        return view('auth.search', [
            'products' => $products,
            'category' => $category,
            'brand' => $brand,
            'query' => $query
        ]);
    }

    // Trong ProductsController.php

    public function showProductDetail($id)
    {
        // Lấy sản phẩm hiện tại, bao gồm brand, category, VÀ variants
        // Giả sử mối quan hệ biến thể trong Model Products là 'variants'
        $product = Products::with(['brands', 'categories', 'variants'])->findOrFail($id);

        // LẤY BIẾN THỂ MẶC ĐỊNH ĐỂ HIỂN THỊ TỒN KHO BAN ĐẦU
        $variant = $product->variants->first(); // Đổi tên biến để khớp với View

        // Xử lý trường hợp không có biến thể nào được tìm thấy
        if (!$variant) {
            // Tạo một đối tượng giả (dummy object) để tránh lỗi 'Undefined variable $variant' trong view
            $variant = (object)['stock' => 0, 'price' => $product->price ?? 0];
        }

        // Lấy các sản phẩm liên quan (Ví dụ: cùng danh mục)
        $relatedProducts = Products::where('categoryId', $product->categoryId)
            ->where('productId', '!=', $id) // Loại trừ sản phẩm hiện tại
            ->limit(6)
            ->get();

        // TRUYỀN BIẾN $variant sang view
        // Vì tên biến đã là $variant, View của bạn sẽ nhận dữ liệu tồn kho đúng
        return view('auth.productdetail', compact('product', 'relatedProducts', 'variant'));
    }


}

