<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">
        <h2 class="text-center mb-4 text-primary">Thanh toán đơn hàng</h2>

        <div class="row">
            <div class="col-md-7">
                <div class="card p-4">
                    <h5>Thông tin giao hàng</h5>
                    <form action="{{ route('checkout.place') }}" method="POST">
                        @csrf
                        <input type="hidden" name="total" value="{{ $total }}">

                        @if($defaultAddress)
                            <div class="mb-3">
                                <label class="form-label">Người nhận</label>
                                <input type="text" class="form-control" value="{{ $defaultAddress->recipientName }}"
                                    readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" value="{{ $defaultAddress->phone }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Địa chỉ</label>
                                <textarea class="form-control"
                                    readonly>{{ $defaultAddress->addressLine }}, {{ $defaultAddress->ward }}, {{ $defaultAddress->district }}, {{ $defaultAddress->city }}</textarea>
                            </div>
                            <input type="hidden" name="address_id" value="{{ $defaultAddress->addressId }}">
                        @else
                            <div class="alert alert-warning">⚠️ Bạn chưa có địa chỉ giao hàng mặc định.</div>
                        @endif

                        <div class="mb-3">
                            <label for="payment" class="form-label">Phương thức thanh toán</label>
                            <select name="payment" id="payment" class="form-select" required>
                                <option value="COD">Thanh toán khi nhận hàng (COD)</option>
                                <option value="Bank">Chuyển khoản</option>
                                <option value="VNPay">VNPay</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Đặt hàng ngay</button>
                    </form>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card p-4">
                    <h5>Tóm tắt giỏ hàng</h5>
                    <table class="table">
                        <tbody>
                            @foreach($cartItems as $item)
                                <tr>
                                    <td>
                                        <img src="{{ asset($item->product->image ?? 'assets/img/no-image.png') }}" alt="" width="60px">
                                    </td>
                                    <td>
                                        <strong>{{ $item->product->productName }}</strong><br>
                                        {{ $item->product->ram }}  {{ $item->product->rom }}  {{ $item->product->color }}<br>
                                        SL: {{ $item->quantity }}
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}₫
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Tổng tiền:</span>
                        <span>{{ number_format($total, 0, ',', '.') }}₫</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>