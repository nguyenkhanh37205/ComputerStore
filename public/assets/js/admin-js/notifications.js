// Cấu hình Toastr
function setupToastr() {
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-bottom-right",
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
}

// Khởi tạo cấu hình ngay khi file JS được tải
setupToastr();

// Hàm hiển thị thông báo
// Ta sẽ gọi hàm này từ file Blade, truyền vào loại và nội dung
function showToast(type, message) {
    if (type === 'success') {
        toastr.success(message);
    } else if (type === 'error') {
        toastr.error(message);
    }
    // Bạn có thể thêm các loại khác như 'warning', 'info'
}