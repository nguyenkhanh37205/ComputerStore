<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        // dd($request->all());
        $user = Auth::user();

        // Validate dữ liệu
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->userId . ',userId',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Lấy dữ liệu được gửi lên
        $data = $request->only(['username', 'email', 'phone', 'address']);

        // Nếu có ảnh mới
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($user->image && Storage::exists(str_replace('storage/', 'public/', $user->image))) {
                Storage::delete(str_replace('storage/', 'public/', $user->image));
            }

            // Lưu ảnh mới
            $path = $request->file('image')->store('uploads/users', 'public');
            $data['image'] = 'storage/' . $path;
        }

        // Cập nhật DB
        $user->update($data);

        return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
    }
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        // Xác thực dữ liệu nhập vào
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Mật khẩu hiện tại không chính xác.',
            ]);
        }

        // Cập nhật mật khẩu
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Đổi mật khẩu thành công!');
    }

    public function showChangeEmailForm()
    {
        return view('auth.profile.changeemail');
    }

    // Email
    public function sendChangeEmailOtp(Request $request)
    {
        $request->validate([
            'new_email' => 'required|email|unique:users,email',
        ]);

        $otp = rand(100000, 999999);
        session(['email_otp' => $otp, 'new_email' => $request->new_email]);

        // Gửi mail OTP
        Mail::raw("Mã xác nhận đổi email của bạn là: $otp", function ($message) use ($request) {
            $message->to($request->new_email)->subject('Xác nhận đổi email');
        });

        return back()->with('success', 'Đã gửi mã xác minh đến email mới của bạn.');
    }

    public function verifyEmailOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);

        if ($request->otp == session('email_otp')) {
            $user = Auth::user();
            $user->email = session('new_email');
            $user->email_verified_at = now();
            $user->save();

            session()->forget(['email_otp', 'new_email']);
            return redirect()->route('profile.show')->with('success', 'Cập nhật email thành công!');
        }

        return back()->with('error', 'Mã OTP không đúng!');
    }

    // Số điện thoại
    public function showChangePhoneForm()
    {
        return view('auth.profile.changephone');
    }

    public function sendChangePhoneOtp(Request $request)
    {
        $request->validate([
            'new_phone' => 'required|digits_between:9,11|unique:users,phone',
        ]);

        $otp = rand(100000, 999999);
        session(['phone_otp' => $otp, 'new_phone' => $request->new_phone]);

        // 🔹 Ở đây giả lập gửi OTP qua SMS — bạn có thể in tạm ra log hoặc gửi API SMS
        info("OTP xác minh số điện thoại: " . $otp);

        return back()->with('success', 'Đã gửi mã OTP xác minh đến số điện thoại mới.');
    }

    public function verifyPhoneOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);

        if ($request->otp == session('phone_otp')) {
            $user = Auth::user();
            $user->phone = session('new_phone');
            $user->phone_verified_at = now();
            $user->save();

            session()->forget(['phone_otp', 'new_phone']);
            return redirect()->route('profile.show')->with('success', 'Cập nhật số điện thoại thành công!');
        }

        return back()->with('error', 'Mã OTP không đúng!');
    }


}
