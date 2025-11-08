<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin tài khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
    body {
        background-color: #f8f9fb;
        font-family: "Segoe UI", sans-serif;
    }

    .dashboard-container {
        max-width: 1100px;
        margin: 50px auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .dashboard-header {
        background: linear-gradient(135deg, #0d6efd, #0b5ed7);
        color: #fff;
        padding: 30px 40px;
    }

    .dashboard-header h2 {
        font-size: 26px;
        margin-bottom: 5px;
    }

    .tab-content {
        padding: 30px;
    }

    .table th {
        background-color: #f0f4ff;
        color: #333;
    }

    .table-hover tbody tr:hover {
        background-color: #f8fbff;
    }

    .order-status {
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 13px;
        color: #fff;
        font-weight: 600;
    }

    .status-pending {
        background: #ffc107;
    }

    .status-confirmed {
        background: #28a745;
    }

    .status-shipping {
        background: #17a2b8;
    }

    .status-delivered {
        background: #198754;
    }

    .product-card {
        border: 1px solid #eee;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        transition: 0.3s;
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .product-card img {
        width: 100%;
        height: 170px;
        object-fit: cover;
        border-radius: 8px;
    }

    .product-card h5 {
        font-size: 16px;
        margin: 10px 0 5px;
    }

    .product-card p {
        color: #0d6efd;
        font-weight: 600;
    }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="dashboard-header">
            <h2>Xin chào, {{ Auth::user()->name ?? 'Khách hàng' }}</h2>
            <p>Quản lý thông tin tài khoản của bạn</p>
        </div>

        <!-- Nội dung -->
        <div class="tab-content">
            <!-- Thông tin cá nhân -->
            <div class="mb-4">
                <h4 class="mb-3 text-primary">Thông tin cá nhân</h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 border rounded bg-light">
                            <strong>Họ tên:</strong> {{ Auth::user()->name ?? 'N/A' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded bg-light">
                            <strong>Email:</strong> {{ Auth::user()->email ?? 'N/A' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded bg-light">
                            <strong>Số điện thoại:</strong> {{ Auth::user()->phone ?? 'N/A' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded bg-light">
                            <strong>Địa chỉ:</strong> {{ Auth::user()->address ?? 'N/A' }}
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary mt-3">Chỉnh sửa thông tin</button>
            </div>

            <hr>

            <!-- Tabs điều hướng -->
            <ul class="nav nav-tabs mb-3" id="accountTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders"
                        type="button" role="tab">
                        Đơn hàng của tôi
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="favorites-tab" data-bs-toggle="tab" data-bs-target="#favorites"
                        type="button" role="tab">
                        Sản phẩm yêu thích
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="accountTabsContent">
                <!-- Đơn hàng -->
                <div class="tab-pane fade show active" id="orders" role="tabpanel">
                    <h5 class="mb-3">Lịch sử đơn hàng</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Mã đơn hàng</th>
                                    <th>Ngày đặt</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders ?? [] as $order)
                                <tr>
                                    <td>{{ $order->order_code ?? '' }}</td>
                                    <td>{{ $order->created_at ?? '' }}</td>
                                    <td>{{ number_format($order->total ?? 0, 0, ',', '.') }}đ</td>
                                    <td>
                                        <span class="order-status status-{{ $order->status ?? 'pending' }}">
                                            {{ $order->status_text ?? 'Đang xử lý' }}
                                        </span>
                                    </td>
                                    <td><a href="#" class="btn btn-outline-primary btn-sm">Xem chi tiết</a></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-muted text-center">Chưa có đơn hàng nào</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Sản phẩm yêu thích -->
                <div class="tab-pane fade" id="favorites" role="tabpanel">
                    <h5 class="mb-3">Sản phẩm yêu thích</h5>
                    <div class="row g-3">
                        @forelse($favorites ?? [] as $product)
                        <div class="col-md-4 col-sm-6">
                            <div class="product-card">
                                <img src="{{ $product->image ?? '' }}" alt="{{ $product->productName ?? '' }}">
                                <h5>{{ $product->productName ?? '' }}</h5>
                                <p>{{ number_format($product->price ?? 0, 0, ',', '.') }}đ</p>
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-outline-primary">Xem</button>
                                    <button class="btn btn-sm btn-danger"
                                        onclick="removeFavorite({{ $product->id ?? 0 }})">Xóa</button>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted text-center">Chưa có sản phẩm yêu thích nào</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function removeFavorite(productId) {
        if (confirm('Bạn có chắc muốn xóa sản phẩm này khỏi danh sách yêu thích?')) {
            alert('Đã xóa sản phẩm ID: ' + productId);
        }
    }
    </script>
</body>

</html>