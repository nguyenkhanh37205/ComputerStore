
$(document).ready(function() {
    console.log("✅ jQuery loaded");

    const input = $('#search-input');
    const results = $('#search-results');

    input.on('keyup', function() {
        const query = $(this).val().trim();
        if (query.length === 0) {
            results.hide();
            return;
        }

        $.ajax({
            url: "{{ route('ajax.search') }}",
            type: "GET",
            data: { query },
            success: function(data) {
                console.log("📦 Dữ liệu trả về:", data);
                if (data.length > 0) {
                    let html = '';
                    data.forEach(p => {
                        html += `
                            <a href="/product/${p.productId}">
                                <img src="/${p.image}" alt="">
                                <span>${p.productName}</span>
                            </a>`;
                    });
                    results.html(html).show();
                } else {
                    results.html('<p>Không tìm thấy sản phẩm</p>').show();
                }
            },
            error: function(xhr) {
                console.error("❌ Lỗi AJAX:", xhr.responseText);
            }
        });
    });
});
