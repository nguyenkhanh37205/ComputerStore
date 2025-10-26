@extends('admin.layout')

@section('content')
<div class="content-header">
    <h1>Danh sách Đơn hàng</h1>
    {{-- Không có nút "Thêm mới" vì đơn hàng được tạo qua frontend --}}
    <link rel="stylesheet" href="{{ asset('assets/css/order.css') }}">
</div>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="order-list-grid">
    {{-- Bắt đầu vòng lặp qua danh sách đơn hàng --}}
    @forelse ($orders as $order)
        <div class="order-card">
            <div class="card-header">
                <span class="card-id">#{{ $order->orderId }}</span>
                {{-- Trạng thái: Dùng tên class động để thay đổi màu --}}
                <span class="card-status status-{{ $order->status }}">
                    {{ $order->status }}
                </span>
            </div>

            <div class="card-body">
                {{-- Tổng tiền --}}
                <p><strong>Tổng tiền:</strong> {{ number_format($order->total, 0, ',', '.') }} đ</p>
                
                {{-- Người đặt hàng --}}
                <p><strong>Khách hàng:</strong> {{ $order->user->fullName ?? $order->user->username ?? 'Khách (ID: ' . $order->userId . ')' }}</p>
                
                {{-- Ngày tạo đơn --}}
                <p><strong>Ngày đặt:</strong> {{ \Carbon\Carbon::parse($order->createdAt)->format('d/m/Y H:i') }}</p>

                {{-- Phương thức thanh toán --}}
                <p><strong>Thanh toán:</strong> {{ $order->paymentMethod ?? 'Chưa xác định' }}</p>
            </div>
            
            <div class="card-actions">
                {{-- Xem chi tiết và hành động (Sửa trạng thái) --}}
                <a href="{{ route('admin.orders.show', $order->orderId) }}" class="btn-detail">Xem Chi tiết</a>
            </div>
        </div>
    @empty
        <p>Không có đơn hàng nào được tìm thấy.</p>
    @endforelse
</div>
@endsection