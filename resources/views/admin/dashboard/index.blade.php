@extends('admin.layout')
@section('content')

<div class="dashboard-container">
    <h2>📊 Dashboard Tổng Hợp & Phân Tích Dữ Liệu</h2>

    <div class="section-header">Phân Tích Tổng Quan (Theo bố cục 5 phần)</div>
    <div class="top-data-grid">
        
        <div class="data-card stat-bar-card">
            <h3>Doanh thu theo khu vực</h3>
            <div class="chart-content">
                <div class="bar-item"><span class="label">Miền Bắc</span><div class="bar bar-1"></div></div>
                <div class="bar-item"><span class="label">Miền Nam</span><div class="bar bar-2"></div></div>
                <div class="bar-item"><span class="label">Miền Trung</span><div class="bar bar-3"></div></div>
                <div class="bar-item"><span class="label">Quốc Tế</span><div class="bar bar-4"></div></div>
            </div>
        </div>

        <div class="data-card chart-area-card span-2-col">
            <h3>Tổng Giá Trị Đơn Hàng</h3>
            <canvas id="areaChart"></canvas>
        </div>

        <div class="data-card chart-doughnut-card">
            <h3>Tỷ lệ Khách hàng mới/cũ</h3>
            <canvas id="doughnutChart"></canvas>
        </div>
        
        <div class="data-card chart-line-multi-card span-3-col">
            <h3>Sản lượng bán Laptop và Điện thoại</h3>
            <canvas id="multiLineChart"></canvas>
        </div>
        
        <div class="data-card chart-line-single-card">
            <h3>Tỷ suất lợi nhuận</h3>
            <canvas id="singleLineChart"></canvas>
        </div>
        
    </div>

    <div class="section-header">Thống kê Kho và Doanh số Bán hàng</div>

    <div class="stats-grid">
        <div class="stat-card total-products">
            <i class="fas fa-boxes stat-icon"></i>
            <div>
                <h3>Tổng Sản Phẩm Trong Kho</h3>
                <p class="stat-value">1,500</p>
            </div>
        </div>
        
        <div class="stat-card low-stock">
            <i class="fas fa-exclamation-triangle stat-icon"></i>
            <div>
                <h3>Sản Phẩm Cần Nhập Thêm</h3>
                <p class="stat-value">55</p>
            </div>
        </div>
        
        <div class="stat-card out-of-stock">
            <i class="fas fa-times-circle stat-icon"></i>
            <div>
                <h3>Sản Phẩm Hết Hàng</h3>
                <p class="stat-value">12</p>
            </div>
        </div>
    </div>

    <div class="charts-grid">
        <div class="chart-card">
            <h3>📈 Sản Lượng Bán Ra Theo Tháng</h3>
            <canvas id="monthlySalesChart"></canvas>
        </div>

        <div class="chart-card">
            <h3>💰 Tổng Doanh Thu theo Danh Mục</h3>
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
</div>

<script>
    // Dữ liệu mẫu chung
    const labelsMonth = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'];
    const primaryColor = '#00bcd4';
    const secondaryColor = '#007bff';

    // ==========================================================
    // KHỞI TẠO BIỂU ĐỒ PHẦN 1 (Bố cục 5 phần)
    // ==========================================================

    // Biểu đồ 2: Area Chart (Tổng Giá Trị Đơn Hàng)
    const areaCtx = document.getElementById('areaChart').getContext('2d');
    new Chart(areaCtx, {
        type: 'line',
        data: {
            labels: labelsMonth.slice(0, 8), // Dùng 8 tháng đầu
            datasets: [{
                label: 'Giá trị đơn hàng',
                data: [350, 420, 390, 510, 580, 490, 650, 600],
                backgroundColor: 'rgba(0, 188, 212, 0.4)',
                borderColor: primaryColor,
                fill: true,
                tension: 0.4
            }]
        },
        options: { responsive: true, scales: { y: { display: false }, x: { display: false } }, plugins: { legend: { display: false } } }
    });

    // Biểu đồ 3: Doughnut Chart (Tỷ lệ Khách hàng mới/cũ)
    const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
    new Chart(doughnutCtx, {
        type: 'doughnut',
        data: {
            labels: ['Khách hàng Mới', 'Khách hàng Cũ'],
            datasets: [{
                data: [40, 60],
                backgroundColor: [primaryColor, secondaryColor],
                hoverOffset: 4
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    // Biểu đồ 4: Multi Line Chart (Sản lượng Laptop và Điện thoại)
    const multiLineCtx = document.getElementById('multiLineChart').getContext('2d');
    new Chart(multiLineCtx, {
        type: 'line',
        data: {
            labels: labelsMonth.slice(0, 8),
            datasets: [{
                label: 'Laptop',
                data: [12, 15, 10, 18, 22, 16, 25, 20],
                borderColor: primaryColor,
                tension: 0.2,
                fill: false
            }, {
                label: 'Điện thoại',
                data: [8, 10, 14, 12, 16, 20, 18, 24],
                borderColor: secondaryColor,
                tension: 0.2,
                fill: false
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });

    // Biểu đồ 5: Single Line Chart (Tỷ suất lợi nhuận)
    const singleLineCtx = document.getElementById('singleLineChart').getContext('2d');
    new Chart(singleLineCtx, {
        type: 'line',
        data: {
            labels: labelsMonth.slice(0, 8),
            datasets: [{
                label: 'Tỷ suất (%)',
                data: [15, 18, 16, 20, 19, 17, 21, 19],
                borderColor: 'lightblue',
                backgroundColor: 'rgba(173, 216, 230, 0.4)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: primaryColor
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: false } }, plugins: { legend: { display: false } } }
    });


    // ==========================================================
    // KHỞI TẠO BIỂU ĐỒ PHẦN 2 (Biểu đồ chính)
    // ==========================================================

    // Biểu đồ Sản lượng bán ra theo tháng (Line Chart)
    const monthlySalesCtx = document.getElementById('monthlySalesChart').getContext('2d');
    new Chart(monthlySalesCtx, {
        type: 'line',
        data: {
            labels: labelsMonth,
            datasets: [{
                label: 'Sản lượng bán ra (SP)',
                data: [120, 150, 130, 200, 250, 220, 300, 280, 350, 400, 380, 450],
                borderColor: secondaryColor,
                tension: 0.1,
                fill: false
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });

    // Biểu đồ Tổng Doanh Thu theo Danh Mục (Doughnut Chart)
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'doughnut',
        data: {
            labels: ['Laptop', 'Điện thoại', 'Linh kiện', 'Phụ kiện'],
            datasets: [{
                label: 'Doanh thu',
                data: [450, 320, 180, 90], // Đơn vị: Triệu VNĐ
                backgroundColor: [
                    '#ff6384', // Hồng
                    '#36a2eb', // Xanh dương
                    '#ffcd56', // Vàng
                    '#4bc0c0'  // Xanh ngọc
                ],
                hoverOffset: 4
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
</script>

@endsection