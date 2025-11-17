// Logic điều chỉnh số lượng (Không cần sửa nếu bạn đã đặt max đúng trong HTML)
        function updateQty(change) {
            const qtyInput = document.getElementById('quantity');
            let currentValue = parseInt(qtyInput.value);
            let newValue = currentValue + change;
            let max = parseInt(qtyInput.max); // Lấy giá trị max đã đặt bằng $variant->stock

            if (newValue >= 1 && newValue <= max) {
                qtyInput.value = newValue;
            }
        }

        // Logic chuyển Tab Mô tả/Thông số
        document.querySelectorAll('.tab-btn').forEach(button => {
            button.addEventListener('click', function () {
                // Xóa active khỏi tất cả các nút và pane
                document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));

                // Thêm active vào nút được click
                this.classList.add('active');

                // Hiển thị tab content tương ứng
                const targetId = this.getAttribute('data-target');
                document.querySelector(targetId).classList.add('active');
            });
        });
        let selectedColor = null;
        let selectedRom = null;

        // highlight button
        function updateButtons(selector, activeValue, type) {
            document.querySelectorAll(selector).forEach(btn => {
                btn.classList.toggle("active", btn.dataset[type] == activeValue);
            });
        }

        // cập nhật variant khi có màu & rom
        function updateVariant() {
            if (!selectedColor || !selectedRom) return;

            const variant = variants.find(v =>
                v.color == selectedColor && v.rom == selectedRom
            );

            if (variant) {
                document.getElementById("productPrice").innerText =
                    Number(variant.price).toLocaleString("vi-VN") + " VNĐ";

                document.getElementById("productStock").innerText = variant.stock;

                if (variant.image) {
                    document.getElementById("productImage").src = "/" + variant.image;
                }

                document.getElementById("selectedVariantId").value = variant.variantId;

                // cập nhật max số lượng
                document.getElementById("quantity").max = variant.stock;

                // nếu hết hàng → disable nút mua
                document.querySelector(".btn-buy").disabled = variant.stock <= 0;
            }
        }

        // click chọn màu
        document.querySelectorAll(".color-btn").forEach(btn => {
            btn.addEventListener("click", () => {
                selectedColor = btn.dataset.color;
                updateButtons(".color-btn", selectedColor, "color");
                updateVariant();
            });
        });

        // click chọn ROM
        document.querySelectorAll(".rom-btn").forEach(btn => {
            btn.addEventListener("click", () => {
                selectedRom = btn.dataset.rom;
                updateButtons(".rom-btn", selectedRom, "rom");
                updateVariant();
            });
        });