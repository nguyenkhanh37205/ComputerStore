<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng</title>
    <link rel="stylesheet" href="{{ asset('assets/css/auth-css/cart.css') }}">
</head>

<body>
    @include('client.layout.header')

    <div class="cart-container">

        <h1>🛒 Giỏ hàng của bạn</h1>

        @if(count($cartItems) < 1) <p class="empty">Chưa có sản phẩm nào trong giỏ hàng.</p>
            @else
            <form id="cart-form" method="POST" action="{{ route('cart.update') }}">
                @csrf
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="check-all"></th>
                            <th>Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Tổng</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @php $total = 0; @endphp
                        @foreach($cartItems as $item)
                        @php
                        $price = $item->variant->price ?? 0;
                        $subtotal = $price * $item->quantity;
                        $total += $subtotal;
                        @endphp

                        <tr data-id="{{ $item->cartId }}">
                            <td><input type="checkbox" class="item-check" checked></td>
                            <td>
                                <img src="{{ asset(($item->variant->image ?? 'no-image.jpg')) }}"
                                alt="{{ $item->variant->product->productName ?? 'Không xác định' }}"
                                    class="product-image">
                            </td>
                            </td>
                            <td class="name">{{ $item->variant->product->productName ?? 'Không xác định' }}</td>
                            <td class="price" data-price="{{ $price }}">
                                {{ number_format($price, 0, ',', '.') }}đ
                            </td>
                            <td class="quantity-box">
                                <button type="button" class="decrease">-</button>
                                <input type="number" name="quantities[{{ $item->cartId }}]"
                                    value="{{ $item->quantity }}" min="1">
                                <button type="button" class="increase">+</button>
                            </td>
                            <td class="subtotal">{{ number_format($subtotal, 0, ',', '.') }}đ</td>
                            <td>
                                <a href="{{ route('cart.destroy', $item->cartId) }}" class="btn-remove">X</a>
                            </td>
                        </tr>

                        @endforeach

                        <tr class="total-row">
                            <td colspan="4" align="right">Tổng cộng:</td>
                            <td colspan="3" id="total"><b>{{ number_format($total, 0, ',', '.') }}đ</b></td>
                        </tr>
                    </tbody>
                </table>

                <div class="cart-actions">
                    <a href="/" class="back-btn">← Tiếp tục mua hàng</a>
                    <a href="{{ route('checkout.index') }}" class="order-btn">Đặt hàng</a>
                </div>

    </div>
    </form>
    @endif
    </div>

    @include('client.layout.footer')

    <script>
    const updateQuantityUrl = "{{ route('cart.updateQuantity') }}";
    const csrfToken = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('assets/js/cart-client.js') }}"></script>


</body>

</html>