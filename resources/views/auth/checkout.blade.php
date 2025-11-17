<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    {{-- Liên kết đến file CSS thuần --}}
    <link rel="stylesheet" href="{{ asset('assets/css/client-css/checkout.css') }}">
</head>

<body class="checkout-body">
    @include('client.layout.header')

    <div class="container">
        <h2 class="page-title">Thanh toán đơn hàng</h2>

        <div class="checkout-layout">

            {{-- Cột 1: Thông tin giao hàng (70%) --}}
            <div class="info-section card-box">
                <h5>Thông tin giao hàng</h5>
                <form id="checkoutForm" action="{{ route('checkout.place') }}" method="POST">
                    @csrf
                    <input type="hidden" name="total" value="{{ $total }}">
                    
                    {{-- Trường ẩn duy nhất cho address_id, JS sẽ quản lý giá trị này (đã lưu hoặc 0) --}}
                    <input type="hidden" name="address_id" id="finalAddressIdInput"
                           value="{{ $defaultAddress->addressId ?? ($userAddresses->first()->addressId ?? '0') }}">

                    <div class="tab-nav checkout-tab-nav">
                        <button type="button" class="active" onclick="switchAddressTab('saved')">Địa chỉ đã lưu</button>
                        <button type="button" onclick="switchAddressTab('new')">Nhập địa chỉ mới</button>
                    </div>

                    <div id="tab-saved" class="tab-panel active">
                        @if(count($userAddresses) > 0)
                            <div class="address-selection-group">
                                @foreach($userAddresses as $address)
                                    @php
                                        $isChecked = $address->isDefault ? 'checked' : '';
                                        $fullAddress = $address->addressLine . ', ' . $address->ward . ', ' . $address->district . ', ' . $address->city;
                                    @endphp

                                    <div class="address-option">
                                        {{-- Radio buttons chỉ quản lý việc chọn, giá trị được cập nhật vào #finalAddressIdInput --}}
                                        <input type="radio" name="temp_address_selection" id="address-{{ $address->addressId }}"
                                            value="{{ $address->addressId }}" {{ $isChecked }} required 
                                            onchange="document.getElementById('finalAddressIdInput').value = this.value;">
                                        <label for="address-{{ $address->addressId }}" class="address-label">
                                            <strong>{{ $address->recipientName }}</strong> ({{ $address->phone }})
                                            @if($address->isDefault) <span class="badge-default">Mặc định</span> @endif
                                            <p>{{ $fullAddress }}</p>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-warning">
                                ⚠️ Bạn chưa có địa chỉ giao hàng nào được lưu.
                            </div>
                        @endif
                    </div>

                    <div id="tab-new" class="tab-panel" style="display: none;">
                        <div class="alert alert-info">
                            Thông tin này sẽ được lưu thành địa chỉ mặc định mới của bạn.
                        </div>

                        {{-- Các trường nhập liệu mới --}}
                        <div class="form-row">
                            <div class="form-group half-width">
                                <label for="newRecipientName" class="form-label">Tên người nhận (*)</label>
                                <input type="text" id="newRecipientName" name="newRecipientName" class="form-control"
                                    value="{{ Auth::user()->fullName ?? Auth::user()->username }}">
                            </div>
                            <div class="form-group half-width">
                                <label for="newPhone" class="form-label">Số điện thoại (*)</label>
                                <input type="text" id="newPhone" name="newPhone" class="form-control"
                                    value="{{ Auth::user()->phone }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="newCity" class="form-label">Tỉnh/Thành phố (*)</label>
                            {{-- ĐÃ CHUYỂN SANG SELECT --}}
                            <select id="newCity" name="newCity" class="form-control">
                                <option value="">-- Chọn Tỉnh/Thành phố --</option>
                            </select>
                        </div>
                        <div class="form-row">
                            <div class="form-group half-width">
                                <label for="newDistrict" class="form-label">Quận/Huyện (*)</label>
                                {{-- ĐÃ CHUYỂN SANG SELECT --}}
                                <select id="newDistrict" name="newDistrict" class="form-control">
                                    <option value="">-- Chọn Quận/Huyện --</option>
                                </select>
                            </div>
                            <div class="form-group half-width">
                                <label for="newWard" class="form-label">Phường/Xã (*)</label>
                                {{-- ĐÃ CHUYỂN SANG SELECT --}}
                                <select id="newWard" name="newWard" class="form-control">
                                    <option value="">-- Chọn Phường/Xã --</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="newAddressLine" class="form-label">Địa chỉ chi tiết (Số nhà, tên đường...)
                                (*)</label>
                            <input type="text" id="newAddressLine" name="newAddressLine" class="form-control"
                                placeholder="Ví dụ: 123 Đường Nguyễn Huệ">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="payment" class="form-label">Phương thức thanh toán</label>
                        <select name="payment" id="payment" class="form-control" required>
                            <option value="COD">Thanh toán khi nhận hàng (COD)</option>
                            <option value="Bank">Chuyển khoản</option>
                            <option value="VNPay">VNPay</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-full">Đặt hàng ngay</button>
                </form>
            </div>

            {{-- Cột 2: Tóm tắt giỏ hàng (30%) --}}
            <div class="summary-section card-box">
                <h5>Tóm tắt giỏ hàng</h5>
                <table class="summary-table">
                    <tbody>
                        @foreach($cartItems as $item)
                            <tr>
                                <td class="item-image">
                                    <img src="{{ asset($item->product->image ?? 'assets/img/no-image.png') }}" alt=""
                                        width="60px">
                                </td>
                                <td class="item-details">
                                    <strong>{{ $item->product->productName }}</strong><br>
                                    <small>{{ $item->product->ram }} {{ $item->product->rom }}
                                        {{ $item->product->color }}</small><br>
                                    <small>SL: {{ $item->quantity }}</small>
                                </td>
                                <td class="item-price">
                                    {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}₫
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="divider"></div>
                <div class="total-row">
                    <span>Tổng tiền:</span>
                    <span class="total-price">{{ number_format($total, 0, ',', '.') }}₫</span>
                </div>
            </div>
        </div>
    </div>
    @include('client.layout.footer')

    <script>
        // Hàm chuyển đổi Tab và quản lý REQUIRED
        function switchAddressTab(targetId) {
            const tabBtns = document.querySelectorAll('.checkout-tab-nav button');
            const tabPanels = document.querySelectorAll('.info-section .tab-panel');
            const finalAddressIdInput = document.getElementById('finalAddressIdInput');
            const formFields = ['newRecipientName', 'newPhone', 'newCity', 'newDistrict', 'newWard', 'newAddressLine'];

            // 1. Chuyển đổi trạng thái active của nút
            tabBtns.forEach(btn => {
                btn.classList.remove('active');
                if (btn.textContent.toLowerCase().includes(targetId === 'saved' ? 'đã lưu' : 'mới')) {
                    btn.classList.add('active');
                }
            });

            // 2. Ẩn/Hiện nội dung panel
            tabPanels.forEach(panel => {
                panel.style.display = 'none';
            });
            document.getElementById(`tab-${targetId}`).style.display = 'block';

            // 3. Quản lý trường REQUIRED và giá trị address_id
            const isNewAddressTab = (targetId === 'new');

            // Đặt required cho các trường form nhập mới
            formFields.forEach(id => {
                const element = document.getElementById(id);
                // Các trường bắt buộc nhập mới: Tên, SĐT, Tỉnh/TP, Địa chỉ chi tiết (Ward, District không bắt buộc)
                const isRequired = isNewAddressTab && ['newRecipientName', 'newPhone', 'newCity', 'newAddressLine'].includes(id);
                if (element) {
                    element.required = isRequired;
                }
            });

            // Đặt required cho các trường radio chọn địa chỉ cũ
            const oldAddressRadios = document.querySelectorAll('#tab-saved input[name="temp_address_selection"]');
            oldAddressRadios.forEach(radio => {
                radio.required = !isNewAddressTab;
            });

            // 4. Đặt giá trị cho trường address_id ẩn để Controller biết làm gì
            if (isNewAddressTab) {
                // Khi chọn Tab nhập mới, gửi giá trị '0' để Controller tạo mới
                finalAddressIdInput.value = '0';
            } else {
                // Khi chọn Tab địa chỉ đã lưu, lấy giá trị của radio đang được chọn
                const selectedRadio = document.querySelector('#tab-saved input[name="temp_address_selection"]:checked');
                if (selectedRadio) {
                    finalAddressIdInput.value = selectedRadio.value;
                } else if (oldAddressRadios.length > 0) {
                    // Nếu có địa chỉ đã lưu nhưng chưa có cái nào được check (trường hợp hiếm)
                    finalAddressIdInput.value = oldAddressRadios[0].value;
                } else {
                    // Trường hợp không có địa chỉ đã lưu nào (buộc phải chuyển sang tab nhập mới nếu form validation được bật)
                    finalAddressIdInput.value = '';
                }
            }
        }

        // --- Logic Xử lý Địa chỉ (JSON) ---
        let addressData = [];

        // Hàm tải dữ liệu địa lý từ JSON
        function loadAddressData() {
            // Tải data.json từ public assets
            fetch('{{ asset("assets/js/data.json") }}') 
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Không thể tải data.json. Kiểm tra lại đường dẫn.');
                    }
                    return response.json();
                })
                .then(data => {
                    addressData = data;
                    // Bắt đầu điền dữ liệu Tỉnh/TP vào select box mới
                    populateProvinces('newCity');
                })
                .catch(error => console.error('Lỗi tải dữ liệu địa chỉ:', error));
        }

        // Hàm điền dữ liệu Tỉnh/Thành phố
        function populateProvinces(citySelectId) {
            const citySelect = document.getElementById(citySelectId);
            if (!citySelect) return;

            // Xóa tất cả các option cũ (trừ option mặc định)
            citySelect.innerHTML = '<option value="">-- Chọn Tỉnh/Thành phố --</option>';

            addressData.forEach(province => {
                const option = document.createElement('option');
                option.value = province.Name; // Tên Tỉnh/TP (giá trị gửi lên server)
                option.setAttribute('data-id', province.Id); // ID nội bộ để tìm Quận/Huyện
                option.textContent = province.Name;
                citySelect.appendChild(option);
            });
        }

        // Hàm tải Quận/Huyện dựa trên Tỉnh/TP được chọn
        function loadDistricts() {
            const citySelect = document.getElementById('newCity');
            const districtSelect = document.getElementById('newDistrict');
            const wardSelect = document.getElementById('newWard'); // Reset Phường/Xã

            const selectedOption = citySelect.options[citySelect.selectedIndex];
            const selectedCityId = selectedOption ? selectedOption.getAttribute('data-id') : null;

            // Reset Quận/Huyện và Phường/Xã
            districtSelect.innerHTML = '<option value="">-- Chọn Quận/Huyện --</option>';
            wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';

            if (!selectedCityId) return;

            const selectedProvince = addressData.find(p => p.Id === selectedCityId);

            if (selectedProvince && selectedProvince.Districts) {
                selectedProvince.Districts.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.Name; // Tên Quận/Huyện (giá trị gửi lên server)
                    option.setAttribute('data-id', district.Id); // ID nội bộ để tìm Phường/Xã
                    option.textContent = district.Name;
                    districtSelect.appendChild(option);
                });
            }
        }

        // Hàm tải Phường/Xã dựa trên Quận/Huyện được chọn
        function loadWards() {
            const districtSelect = document.getElementById('newDistrict');
            const wardSelect = document.getElementById('newWard');

            const selectedOption = districtSelect.options[districtSelect.selectedIndex];
            const selectedDistrictId = selectedOption ? selectedOption.getAttribute('data-id') : null;

            // Reset Phường/Xã
            wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';

            if (!selectedDistrictId) return;

            let selectedDistrict = null;
            // Tìm Tỉnh và Quận/Huyện dựa trên ID
            for (const province of addressData) {
                selectedDistrict = province.Districts.find(d => d.Id === selectedDistrictId);
                if (selectedDistrict) break;
            }

            if (selectedDistrict && selectedDistrict.Wards) {
                selectedDistrict.Wards.forEach(ward => {
                    const option = document.createElement('option');
                    option.value = ward.Name; // Tên Phường/Xã (giá trị gửi lên server)
                    option.textContent = ward.Name;
                    wardSelect.appendChild(option);
                });
            }
        }

        // --- Event Listeners ---
        document.addEventListener('DOMContentLoaded', () => {
            // Thiết lập trạng thái tab ban đầu
            switchAddressTab('saved'); 

            // Tải dữ liệu địa lý và điền Tỉnh/TP
            loadAddressData();

            // Gán sự kiện thay đổi cho Tỉnh/TP và Quận/Huyện (chỉ khi có tab nhập mới)
            const newCitySelect = document.getElementById('newCity');
            const newDistrictSelect = document.getElementById('newDistrict');

            if (newCitySelect) {
                newCitySelect.addEventListener('change', loadDistricts);
            }
            if (newDistrictSelect) {
                newDistrictSelect.addEventListener('change', loadWards);
            }
        });
    </script>
</body>

</html>