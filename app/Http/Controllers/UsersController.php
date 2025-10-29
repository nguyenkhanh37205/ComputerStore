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

        // 4. Tạo người dùng mới
        User::create($data); // SỬ DỤNG Users::create

        return redirect()->route('admin.users.index')->with('success', 'Tài khoản người dùng đã được tạo thành công.');
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

         if ($request->hasFile('image')) {
        
        // Xóa ảnh cũ khỏi public/uploads
        if ($user->image) {
             // Sử dụng unlink() để xóa file trong thư mục public
             $oldImagePath = public_path($user->image);
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

        // 4. Cập nhật người dùng
        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Tài khoản người dùng đã được cập nhật thành công.');
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

        return redirect()->route('admin.users.index')->with('success', 'Tài khoản người dùng đã được xóa thành công.');
    }
}