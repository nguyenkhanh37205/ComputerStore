@extends('admin.layout')

@section('content')
<div class="content-header">
    <h1>Danh sách Giỏ hàng (Theo dõi)</h1>
    <link rel="stylesheet" href="{{ asset('assets/css/cart.css') }}">
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID Cart</th>
                <th>NGƯỜI DÙNG</th>
                <th>SẢN PHẨM GỐC</th>
                {{-- <th>BIẾN THỂ (RAM/ROM/MÀU)</th> --}}
                <th>GIÁ HIỆN TẠI</th>
                <th>SỐ LƯỢNG</th>
                <th>TỔNG TIỀN</th>
                {{-- Admin có thể thêm cột hành động như xóa/sửa nếu cần --}}
            </tr>
        </thead>
        <tbody>
            @forelse ($cartItems as $item)
                <tr>
                    {{-- ID Cart --}}
                    <td>{{ $item->cartId }}</td> 
                    
                    {{-- NGƯỜI DÙNG (Lấy từ quan hệ user) --}}
                    <td>
                        {{ $item->user->username ?? 'User không tồn tại (ID: ' . $item->userId . ')' }}
                        <br>
                        <small>({{ $item->user->fullName ?? 'Khách' }})</small>
                    </td> 
                    
                    {{-- SẢN PHẨM GỐC --}}
                    <td>{{ $item->variant->product->productName ?? 'Sản phẩm đã xóa' }}</td> 
                    
                    {{-- BIẾN THỂ --}}
                    {{-- <td>
                        {{ $item->variant->ram ?? 'N/A' }} / 
                        {{ $item->variant->rom ?? 'N/A' }} / 
                        {{ $item->variant->color ?? 'N/A' }}
                    </td>  --}}
                    
                    {{-- GIÁ HIỆN TẠI (Giá của biến thể) --}}
                    <td>
                        {{ number_format($item->variant->price ?? 0, 0, ',', '.') }} đ
                    </td>

                    {{-- SỐ LƯỢNG --}}
                    <td>{{ $item->quantity }}</td> 
                    
                    {{-- TỔNG TIỀN --}}
                    <td>
                        <strong>{{ number_format(($item->quantity * $item->variant->price) ?? 0, 0, ',', '.') }} đ</strong>
                    </td>
                    
                    {{-- Thêm nút Xóa (chỉ khi Admin cần)
                    <td>
                        <form action="{{ route('admin.cart.destroy', $item->cartId) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action delete" onclick="return confirm('Xóa sản phẩm này khỏi giỏ hàng của người dùng ID {{ $item->userId }}?');">
                                Xóa
                            </button>
                        </form>
                    </td> 
                    --}}
                </tr>
            @empty
                <tr>
                    <td colspan="7">Không có sản phẩm nào trong giỏ hàng của bất kỳ người dùng nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection