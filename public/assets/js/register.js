document.addEventListener('DOMContentLoaded', function () {
            const provinceSelect = document.getElementById('province');
            const districtSelect = document.getElementById('district');
            const wardSelect = document.getElementById('ward');
            const fullAddress = document.getElementById('fullAddress');

            // Hàm cập nhật địa chỉ đầy đủ
            function updateFullAddress() {
                const province = provinceSelect.options[provinceSelect.selectedIndex]?.text || '';
                const district = districtSelect.options[districtSelect.selectedIndex]?.text || '';
                const ward = wardSelect.options[wardSelect.selectedIndex]?.text || '';
                fullAddress.value = [ward, district, province].filter(Boolean).join(', ');
            }

            // Load danh sách tỉnh
            fetch('https://provinces.open-api.vn/api/p/')
                .then(res => res.json())
                .then(provinces => {
                    provinces.forEach(p => {
                        const option = document.createElement('option');
                        option.value = p.code;
                        option.text = p.name;
                        provinceSelect.add(option);
                    });
                });

            // Khi chọn tỉnh
            provinceSelect.addEventListener('change', function () {
                const provinceCode = this.value;
                districtSelect.innerHTML = '<option value="">Chọn quận / huyện</option>';
                wardSelect.innerHTML = '<option value="">Chọn phường / xã</option>';
                districtSelect.disabled = true;
                wardSelect.disabled = true;

                if (provinceCode) {
                    fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
                        .then(res => res.json())
                        .then(data => {
                            data.districts.forEach(d => {
                                const option = document.createElement('option');
                                option.value = d.code;
                                option.text = d.name;
                                districtSelect.add(option);
                            });
                            districtSelect.disabled = false;
                        });
                }
                updateFullAddress();
            });

            // Khi chọn huyện
            districtSelect.addEventListener('change', function () {
                const districtCode = this.value;
                wardSelect.innerHTML = '<option value="">Chọn phường / xã</option>';
                wardSelect.disabled = true;

                if (districtCode) {
                    fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`)
                        .then(res => res.json())
                        .then(data => {
                            data.wards.forEach(w => {
                                const option = document.createElement('option');
                                option.value = w.code;
                                option.text = w.name;
                                wardSelect.add(option);
                            });
                            wardSelect.disabled = false;
                        });
                }
                updateFullAddress();
            });

            // Khi chọn xã
            wardSelect.addEventListener('change', updateFullAddress);
        });