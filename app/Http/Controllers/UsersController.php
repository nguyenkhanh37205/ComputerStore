<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Đảm bảo dùng tên Model là Users
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }
    
    //---------------------------------------------------------
    
    public function create()
    {
        return view('admin.users.create');
    }
    
    //---------------------------------------------------------
    
    public function store(Request $request)
    {
        // 1. Validate the request data
        $request->validate([
            'username' => 'required|string|max:50|unique:users',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'fullName' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Quy tắc cho file ảnh
            'role' => 'required|in:customer,admin', 
        ]);

        $data = $request->except('password', 'image', '_token', 'password_confirmation');
        
        // 2. Xử lý Password
        $data['password'] = Hash::make($request->password);
        
        // 3. Xử lý Image Upload
        if ($request->hasFile('image')) {
            // Lưu ảnh vào thư mục storage/app/public/users
            $imagePath = $request->file('image')->store('users', 'public');
            $data['image'] = $imagePath;
        }

        // 4. Tạo người dùng mới
        User::create($data); // SỬ DỤNG Users::create

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }
    
    //---------------------------------------------------------
    
    public function edit($id)
    {
        $user = User::findOrFail($id); // SỬ DỤNG Users::findOrFail
        return view('admin.users.edit', compact('user')); 
    }
    
    //---------------------------------------------------------
    
    public function update(Request $request, $id)
    {
        // 1. Validate the request data
        $request->validate([
            // unique:users,username, ngoại trừ userId hiện tại
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($id, 'userId')], 
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('users')->ignore($id, 'userId')], 
            'password' => 'nullable|string|min:8|confirmed',
            'fullName' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role' => 'required|in:customer,admin',
        ]);
    
        $user = User::findOrFail($id); // SỬ DỤNG Users::findOrFail
        $data = $request->except('password', 'image', '_token', 'password_confirmation', '_method');

        // 2. Xử lý Password
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // 3. Xử lý Image Upload/Cập nhật
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ (nếu có)
            if ($user->image) {
                 \Storage::disk('public')->delete($user->image);
            }
            // Lưu ảnh mới
            $imagePath = $request->file('image')->store('users', 'public');
            $data['image'] = $imagePath;
        }

        // 4. Cập nhật người dùng
        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }
    
    //---------------------------------------------------------
    
    public function destroy($id)
    {
        $user = User::findOrFail($id); // SỬ DỤNG Users::findOrFail
        
        // Xóa ảnh của người dùng trước khi xóa bản ghi
        if ($user->image) {
            \Storage::disk('public')->delete($user->image);
        }
        
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}