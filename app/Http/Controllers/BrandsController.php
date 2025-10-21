<?php

namespace App\Http\Controllers;

use App\Models\Brands;
use Illuminate\Http\Request;
use DB;
use App\Models\Brand;
use Session;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
class BrandsController extends Controller
{
    public function index()
    {
        $brands = Brands::all();
        // dd($brands);
        return view('admin.brands.index', compact('brands'));
    }
    public function create()
    {
        return view('admin.brands.create');
    }
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'brandName' => 'required|string|max:255',
            'brandDescription' => 'nullable|string',
        ]);
        // Create a new brand
        Brands::create([
            'brandName' => $request->brandName,
            'brandDescription' => $request->brandDescription,
        ]);
        
        
        return redirect()->route('admin.brands.index')->with('success', 'Tạo thương hiệu thành công.');
    }
    public function edit($id)
    {
        // $brand = Brand::findOrFail($id);
        return view('admin.brands.edit'/*, compact('brand')*/);
    }

    //update thương hiệu
    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        // $brand = Brand::findOrFail($id);
        // $brand->update([
        //     'name' => $request->name,
        //     'description' => $request->description,
        // ]);
        return redirect()->route('admin.brands.index')->with('success', 'Cập nhật thương hiệu thành công.');
    }

    //xóa thương hiệu
    public function destroy($id)
    {
        // $brand = Brand::findOrFail($id);
        // $brand->delete();
        return redirect()->route('admin.brands.index')->with('success', 'Xóa Sản phẩm thương hiệu thành công.');
    }
}