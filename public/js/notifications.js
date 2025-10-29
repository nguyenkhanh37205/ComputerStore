function setupToastr() {
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-bottom-right", // Vị trí hiển thị
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000", // Thời gian hiển thị
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
}
setupToastr();
function showToast(type, message) {
    if (type === 'success') {
        toastr.success(message);
    } else if (type === 'error') {
        toastr.error(message);
    }
}


