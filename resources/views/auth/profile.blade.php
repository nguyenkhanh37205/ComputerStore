<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin tài khoản</title>
    <link rel="stylesheet" href="{{ asset('assets/css/client-css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/auth-css/profile.css') }}">

</head>

<body>
    @include('client.layout.header')
    <div class="dashboard-container">
        <div class="dashboard-header">
            <img src="{{ Auth::user()->image ? asset(Auth::user()->image) : 'https://via.placeholder.com/90' }}"
                alt="Avatar" class="user-avatar">
            <div>
                <h2>Xin chào, {{ Auth::user()->username ?? 'Khách hàng' }}</h2>
                <p>Quản lý thông tin tài khoản của bạn</p>
            </div>
        </div>

        <div class="tab-content">
            <h4 class="text-primary">Thông tin cá nhân</h4>
            <div class="info-grid">
                <div class="info-box"><strong>Họ tên:</strong> {{ Auth::user()->username ?? 'N/A' }}</div>
                <div class="info-box"><strong>Email:</strong> {{ Auth::user()->email ?? 'N/A' }}</div>
                <div class="info-box"><strong>Số điện thoại:</strong> {{ Auth::user()->phone ?? 'N/A' }}</div>
                <div class="info-box"><strong>Địa chỉ:</strong> {{ Auth::user()->address ?? 'N/A' }}</div>
            </div>
            <button class="btn btn-primary" onclick="openModal()">Chỉnh sửa thông tin</button>

            <div class="tab-nav">
                <button class="active" onclick="switchTab('orders')">Đơn hàng của tôi</button>
                <button onclick="switchTab('favorites')">Sản phẩm yêu thích</button>
            </div>

            <div id="orders" class="tab-panel active">
                <table>
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
                                <td>{{ $order->order_code }}</td>
                                <td>{{ $order->created_at }}</td>
                                <td>{{ number_format($order->total ?? 0, 0, ',', '.') }}đ</td>
                                <td><span class="order-status status-{{ $order->status }}">{{ $order->status_text }}</span>
                                </td>
                                <td><button class="btn btn-outline">Xem</button></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">Chưa có đơn hàng nào</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="favorites" class="tab-panel" style="display:none;">
                <div class="product-grid">
                    @forelse($favorites ?? [] as $wishlist)
                        @php $product = $wishlist->products; @endphp
                        <div class="product-card" id="wishlist-item-{{ $wishlist->wishlistId }}">
                            <img src="{{ asset($product->image) }}" alt="{{ $product->productName }}">
                            <h5>{{ $product->productName }}</h5>
                            <p style="color:#0d6efd;font-weight:600;">{{ number_format($product->price, 0, ',', '.') }}đ</p>
                            <div style="display:flex;justify-content:center;gap:10px;">
                                <a href="{{ route('products.show', $product->productId) }}" class="btn btn-outline">Xem</a>
                                <button class="btn btn-danger"
                                    onclick="removeFavorite({{ $wishlist->wishlistId }})">Xóa</button>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Chưa có sản phẩm yêu thích nào</p>
                    @endforelse
                </div>
            </div>


            <!-- Modal -->
            <div class="modal" id="editModal">
                <div class="modal-content">
                    <button class="close-btn" onclick="closeModal()">✖</button>
                    <div class="modal-header">Chỉnh sửa thông tin tài khoản</div>

                    <div class="modal-tabs">
                        <button class="active" onclick="switchModalTab('info')">Thông tin cá nhân</button>
                        <button onclick="switchModalTab('password')">Đổi mật khẩu</button>
                    </div>

                    <div class="modal-body" id="modal-info">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <div class="form-group">
                                <label>Họ tên</label>
                                <input type="text" name="username" value="{{ Auth::user()->username }}">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" value="{{ Auth::user()->email }}">
                            </div>
                            <div class="form-group">
                                <label>Số điện thoại</label>
                                <input type="text" name="phone" value="{{ Auth::user()->phone }}">
                            </div>

                            <!-- THAY THẾ BẰNG ĐÂY -->
                            <div class="form-group">
                                <label>Tỉnh/Thành phố</label>
                                <select id="province" name="province">
                                    <option value="">-- Chọn Tỉnh/Thành phố --</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Quận/Huyện</label>
                                <select id="district" name="district" disabled>
                                    <option value="">-- Chọn Quận/Huyện --</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Phường/Xã</label>
                                <select id="ward" name="ward" disabled>
                                    <option value="">-- Chọn Phường/Xã --</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Địa chỉ đầy đủ</label>
                                <input type="text" id="fullAddress" name="address" value="{{ Auth::user()->address }}"
                                    placeholder="Địa chỉ đầy đủ sẽ hiển thị tại đây">
                            </div>

                            <div class="form-group">
                                <label>Ảnh đại diện</label>
                                <input type="file" name="image">
                            </div>
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </form>
                    </div>


                    <div class="modal-body" id="modal-password" style="display:none;">
                        <form action="{{ route('profile.changePassword') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Mật khẩu hiện tại</label>
                                <input type="password" name="current_password" required>
                            </div>
                            <div class="form-group">
                                <label>Mật khẩu mới</label>
                                <input type="password" name="new_password" required>
                            </div>
                            <div class="form-group">
                                <label>Xác nhận mật khẩu mới</label>
                                <input type="password" name="new_password_confirmation" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Cập nhật mật khẩu</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="width: 100%;">@include('client.layout.footer')</div>
    <!-- <script>
        document.addEventListener('DOMContentLoaded', () => {

            /* ---------------- TAB CHÍNH (Đơn hàng / Yêu thích) ---------------- */
            const tabBtns = document.querySelectorAll('.tab-nav button');
            const tabPanels = document.querySelectorAll('.tab-panel');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Bỏ active tất cả nút
                    tabBtns.forEach(b => b.classList.remove('active'));
                    // Active nút hiện tại
                    btn.classList.add('active');

                    // Ẩn tất cả panel
                    tabPanels.forEach(p => p.style.display = 'none');

                    // Hiện panel tương ứng
                    const target = btn.textContent.includes('Đơn') ? 'orders' : 'favorites';
                    document.getElementById(target).style.display = 'block';
                });
            });

            /* ---------------- MỞ / ĐÓNG MODAL ---------------- */
            window.openModal = function () {
                document.getElementById('editModal').style.display = 'flex';
            }
            window.closeModal = function () {
                document.getElementById('editModal').style.display = 'none';
            }

            /* ---------------- TAB CON TRONG MODAL ---------------- */
            const modalTabs = document.querySelectorAll('.modal-tabs button');
            modalTabs.forEach(btn => {
                btn.addEventListener('click', () => {
                    modalTabs.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    document.getElementById('modal-info').style.display =
                        btn.textContent.includes('Thông tin') ? 'block' : 'none';
                    document.getElementById('modal-password').style.display =
                        btn.textContent.includes('Mật khẩu') ? 'block' : 'none';
                });
            });

        });
    </script> -->
    <script src="{{ asset('assets/js/profile.js') }}"></script>
</body>

</html>