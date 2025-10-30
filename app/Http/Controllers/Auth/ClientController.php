<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;    
use App\Models\Products;
use App\Models\Categories;
use App\Models\Brands;

class ClientController extends Controller{
    //lấy tất cả sản phẩm cùng với danh mục và thương hiệu
    public function getAllProducts(){
        $products = Products::with(['categories', 'brands'])->get(); 
        //Nhóm sản phẩm theo thương hiệu
        $productsByBrand = $products->groupBy(function($product) {
            return $product->brands->brandName; 
        });
        $productsByCategory = $products->groupBy(function($product) {
            return $product->categories->categoryName; 
        });

        return view('auth.home', compact('products', 'productsByBrand', 'productsByCategory'));
        
    }

    public function showProfile(){
        return view('auth.profile');
    }
    
}