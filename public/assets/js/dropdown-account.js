document.addEventListener("DOMContentLoaded", function () {
    const accountToggle = document.getElementById("accountToggle");
    const accountDropdown = document.getElementById("accountDropdown");

    // Khi bấm vào nút "Tài khoản" → bật/tắt menu
    accountToggle.addEventListener("click", function (e) {
        e.preventDefault();
        accountDropdown.classList.toggle("show");
    });

    // Khi click ra ngoài → ẩn menu
    document.addEventListener("click", function (e) {
        if (!accountDropdown.contains(e.target) && !accountToggle.contains(e.target)) {
            accountDropdown.classList.remove("show");
        }
    });
});
