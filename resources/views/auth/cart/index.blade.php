{{-- resources/views/client/cart/index.blade.php --}}
@extends('auth.home')

@section('title', 'Giỏ hàng')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 text-center">🛒 Giỏ hàng của bạn</h1>

    @if($cartItems->isEmpty())
    <div class="alert alert-info text-center">
        Giỏ hàng của bạn đang trống.
    </div>
    @else
    <table class="table table-bordered align-middle">
        <thead class="table-dark text-center">
            <tr>
                <th>Sản phẩm</th>
                <th>Biến thể</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Tổng</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cartItems as $item)
            <tr>
                <td>{{ $item->variant->product->name ?? 'Sản phẩm không tồn tại' }}</td>
                <td>{{ $item->variant->name ?? 'Không có thông tin' }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-end">{{ number_format($item->variant->price ?? 0) }} ₫</td>
                <td class="text-end">{{ number_format($item->quantity * ($item->variant->price ?? 0)) }} ₫</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-end mt-4">
        <h4>Tổng cộng: <strong>{{ number_format($subtotal) }} ₫</strong></h4>
        <a href="{{ route('checkout.index') }}" class="btn btn-primary mt-2">Thanh toán</a>
    </div>
    @endif
</div>
<!-- @endsection -->