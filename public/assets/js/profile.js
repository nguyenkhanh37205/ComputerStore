
document.addEventListener('DOMContentLoaded', () => {

    /* ----------------- LOAD ĐỊA GIỚI HÀNH CHÍNH ----------------- */
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');
    const fullAddressInput = document.getElementById('fullAddress');

    if (provinceSelect && districtSelect && wardSelect && fullAddressInput) {
        fetch("/assets/js/data.json")
            .then(res => res.json())
            .then(data => {
                // Load danh sách tỉnh
                data.forEach(p => {
                    provinceSelect.innerHTML += `<option value="${p.Name}">${p.Name}</option>`;
                });

                // Khi chọn Tỉnh
                provinceSelect.onchange = () => {
                    const selected = data.find(p => p.Name === provinceSelect.value);
                    districtSelect.innerHTML = '<option value="">-- Chọn Quận/Huyện --</option>';
                    wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
                    districtSelect.disabled = !selected;
                    wardSelect.disabled = true;

                    if (selected) {
                        selected.Districts.forEach(d => {
                            districtSelect.innerHTML += `<option value="${d.Name}">${d.Name}</option>`;
                        });
                    }
                    updateAddress();
                };

                // Khi chọn Quận/Huyện
                districtSelect.onchange = () => {
                    const province = data.find(p => p.Name === provinceSelect.value);
                    const district = province?.Districts.find(d => d.Name === districtSelect.value);
                    wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
                    wardSelect.disabled = !district;

                    if (district) {
                        district.Wards.forEach(w => {
                            wardSelect.innerHTML += `<option value="${w.Name}">${w.Name}</option>`;
                        });
                    }
                    updateAddress();
                };

                // Khi chọn Phường/Xã
                wardSelect.onchange = updateAddress;

                // Cập nhật địa chỉ đầy đủ
                function updateAddress() {
                    const full = [wardSelect.value, districtSelect.value, provinceSelect.value]
                        .filter(Boolean)
                        .join(', ');
                    fullAddressInput.value = full;
                }
            })
            .catch(() => console.warn('Không thể tải file data.json (kiểm tra đường dẫn)'));
    }

    /* ----------------- TAB "ĐƠN HÀNG / YÊU THÍCH" ----------------- */
    const tabs = document.querySelectorAll('.tab-nav button');
    const panels = document.querySelectorAll('.tab-panel');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            panels.forEach(p => p.style.display = 'none');

            tab.classList.add('active');
            document.getElementById(tab.textContent.includes('Đơn hàng') ? 'orders' : 'favorites').style.display = 'block';
        });
    });

    /* ----------------- MỞ / ĐÓNG MODAL ----------------- */
    const modal = document.getElementById('editModal');

    window.openModal = function() {
        modal.style.display = 'flex';
    }

    window.closeModal = function() {
        modal.style.display = 'none';
    }

    window.addEventListener('click', e => {
        if (e.target === modal) modal.style.display = 'none';
    });

    /* ----------------- CHUYỂN TAB TRONG MODAL ----------------- */
    window.switchModalTab = function(tabName) {
        const info = document.getElementById('modal-info');
        const pass = document.getElementById('modal-password');
        const btns = document.querySelectorAll('.modal-tabs button');

        info.style.display = 'none';
        pass.style.display = 'none';
        btns.forEach(b => b.classList.remove('active'));

        if (tabName === 'info') {
            info.style.display = 'block';
            btns[0].classList.add('active');
        } else if (tabName === 'password') {
            pass.style.display = 'block';
            btns[1].classList.add('active');
        }
    }

    /* ----------------- XÓA SẢN PHẨM YÊU THÍCH ----------------- */
    window.removeFavorite = function(wishlistId) {
        if (!confirm('Bạn có chắc muốn xóa sản phẩm này khỏi danh sách yêu thích?')) return;

        fetch(`/wishlist/delete/${wishlistId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`wishlist-item-${wishlistId}`)?.remove();
                alert('✅ Đã xóa khỏi danh sách yêu thích.');
            } else {
                alert('❌ Lỗi khi xóa sản phẩm.');
            }
        })
        .catch(() => alert('Lỗi kết nối với máy chủ!'));
    };

});
