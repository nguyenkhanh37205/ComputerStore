document.addEventListener("DOMContentLoaded", () => {
    const spinner = document.createElement("div");
    spinner.innerHTML = `
        <div id="loading-spinner" 
             style="position: fixed; top: 20px; right: 20px; z-index: 9999; display: none;">
            <div style="
                border: 4px solid #f3f3f3;
                border-top: 4px solid #28a745;
                border-radius: 50%;
                width: 30px;
                height: 30px;
                animation: spin 1s linear infinite;">
            </div>
        </div>
        <style>
            @keyframes spin { 100% { transform: rotate(360deg); } }
            .toast {
                position: fixed;
                top: 70px;
                right: 20px;
                background: #28a745;
                color: white;
                padding: 10px 20px;
                border-radius: 6px;
                font-weight: bold;
                box-shadow: 0 2px 6px rgba(0,0,0,0.2);
                opacity: 0;
                transition: opacity 0.3s ease;
                z-index: 9999;
            }
            .toast.show { opacity: 1; }
        </style>
    `;
    document.body.appendChild(spinner);

    const toast = document.createElement("div");
    toast.classList.add("toast");
    toast.textContent = "Đã cập nhật!";
    document.body.appendChild(toast);

    function showToast() {
        toast.classList.add("show");
        setTimeout(() => toast.classList.remove("show"), 2000);
    }

    function showSpinner(show) {
        document.getElementById("loading-spinner").style.display = show ? "block" : "none";
    }

    // Cập nhật từng dòng
    function updateRow(row) {
        const price = Number(row.querySelector(".price").dataset.price) || 0;
        const qty = parseInt(row.querySelector('input[type="number"]').value) || 1;
        const subtotal = price * qty;
        row.querySelector(".subtotal").textContent = subtotal.toLocaleString("vi-VN") + "đ";
        updateTotal();
    }

    // Cập nhật tổng
    function updateTotal() {
        let total = 0;
        document.querySelectorAll(".item-check:checked").forEach(cb => {
            const row = cb.closest("tr");
            const price = Number(row.querySelector(".price").dataset.price) || 0;
            const qty = parseInt(row.querySelector('input[type="number"]').value) || 1;
            total += price * qty;
        });
        document.getElementById("total").innerHTML = "<b>" + total.toLocaleString("vi-VN") + "đ</b>";
    }

    // Lưu vào DB
    function saveQuantity(cartId, quantity) {
        showSpinner(true);
        fetch(updateQuantityUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({ cartId, quantity })
        })
        .then(res => res.json())
        .then(data => {
            showSpinner(false);
            if (data.success) {
                showToast();
            } else {
                console.error("❌ Lỗi:", data.message);
            }
        })
        .catch(err => {
            showSpinner(false);
            console.error(err);
        });
    }

    // Tăng / giảm
    document.querySelectorAll(".increase, .decrease").forEach(btn => {
        btn.addEventListener("click", function() {
            const row = this.closest("tr");
            const input = row.querySelector('input[type="number"]');
            let value = parseInt(input.value);
            if (this.classList.contains("increase")) value++;
            else if (value > 1) value--;
            input.value = value;
            updateRow(row);
            saveQuantity(row.dataset.id, value);
        });
    });

    // Check all
    document.getElementById("check-all")?.addEventListener("change", function() {
        document.querySelectorAll(".item-check").forEach(cb => cb.checked = this.checked);
        updateTotal();
    });

    // Khi tick chọn từng dòng
    document.querySelectorAll(".item-check").forEach(cb => {
        cb.addEventListener("change", updateTotal);
    });

    updateTotal();
});
