document.addEventListener('DOMContentLoaded', () => {

    /* ----------------- CÁC BIẾN CHUNG ----------------- */
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');
    const addressInput = document.getElementById('address'); // input hidden duy nhất
    const modal = document.getElementById('editModal');
    const DATA_PATH = "/assets/js/data.json";

    /* ----------------- CẬP NHẬT ĐỊA CHỈ TỪ 3 SELECT ----------------- */
    function updateAddress() {
        const province = provinceSelect.value;
        const district = districtSelect.value;
        const ward = wardSelect.value;

        addressInput.value = [ward, district, province].filter(Boolean).join(', ');
    }

    /* ----------------- LOAD DATA VÀ KHÔI PHỤC ĐỊA CHỈ ----------------- */
    if (provinceSelect && districtSelect && wardSelect && addressInput) {
        fetch(DATA_PATH)
            .then(res => res.json())
            .then(data => {

                // Load danh sách Tỉnh
                data.forEach(p => {
                    provinceSelect.innerHTML += `<option value="${p.Name}">${p.Name}</option>`;
                });

                // Nếu có địa chỉ hiện tại, khôi phục
                const currentAddressValue = addressInput.value;
                if (currentAddressValue) {
                    const parts = currentAddressValue.split(',').map(p => p.trim());
                    if (parts.length >= 3) {
                        const [wardName, districtName, provinceName] = parts.slice(0, 3);

                        provinceSelect.value = provinceName;
                        provinceSelect.onchange();

                        setTimeout(() => {
                            if (districtSelect.querySelector(`option[value="${districtName}"]`)) {
                                districtSelect.value = districtName;
                                districtSelect.onchange();

                                setTimeout(() => {
                                    if (wardSelect.querySelector(`option[value="${wardName}"]`)) {
                                        wardSelect.value = wardName;
                                    }
                                    updateAddress();
                                }, 100);
                            }
                        }, 100);
                    }
                }

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

            })
            .catch(() => console.warn('Không thể tải file data.json (kiểm tra đường dẫn)'));
    }

    /* ----------------- TAB "ĐƠN HÀNG / YÊU THÍCH" ----------------- */
    const tabs = document.querySelectorAll('.tab-nav button');
    const panels = document.querySelectorAll('.tab-panel');

    window.switchTab = function(target) {
        tabs.forEach(t => t.classList.remove('active'));
        panels.forEach(p => p.style.display = 'none');
        document.querySelector(`.tab-nav button[onclick*="'${target}'"]`)?.classList.add('active');
        document.getElementById(target).style.display = 'block';
    };

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            panels.forEach(p => p.style.display = 'none');

            tab.classList.add('active');
            const targetId = tab.textContent.includes('Đơn hàng') ? 'orders' : 'favorites';
            document.getElementById(targetId).style.display = 'block';
        });
    });

    /* ----------------- MỞ / ĐÓNG MODAL ----------------- */
    window.openModal = () => { if (modal) modal.style.display = 'flex'; };
    window.closeModal = () => { if (modal) modal.style.display = 'none'; };
    window.addEventListener('click', e => { if (e.target === modal) modal.style.display = 'none'; });

    /* ----------------- CHUYỂN TAB TRONG MODAL ----------------- */
    window.switchModalTab = (tabName) => {
        const info = document.getElementById('modal-info');
        const pass = document.getElementById('modal-password');
        const btns = document.querySelectorAll('.modal-tabs button');

        if (info) info.style.display = 'none';
        if (pass) pass.style.display = 'none';
        btns.forEach(b => b.classList.remove('active'));

        if (tabName === 'info' && info) { info.style.display = 'block'; btns[0].classList.add('active'); }
        else if (tabName === 'password' && pass) { pass.style.display = 'block'; btns[1].classList.add('active'); }
    };

    /* ----------------- XÓA SẢN PHẨM YÊU THÍCH ----------------- */
    window.removeFavorite = (wishlistId) => {
        if (!confirm('Bạn có chắc muốn xóa sản phẩm này khỏi danh sách yêu thích?')) return;

        fetch(`/wishlist/${wishlistId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(res => { if(!res.ok) throw new Error(`Server: ${res.status}`); return res.json(); })
        .then(data => {
            if (data.success) { document.getElementById(`wishlist-item-${wishlistId}`)?.remove(); alert('Đã xóa khỏi danh sách yêu thích.'); }
            else { alert('Lỗi khi xóa sản phẩm: ' + (data.message || 'Lỗi không xác định')); }
        })
        .catch(error => { console.error('Lỗi khi xóa sản phẩm:', error); alert('Lỗi kết nối hoặc máy chủ.'); });
    };

});
