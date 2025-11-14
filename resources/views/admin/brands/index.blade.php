@extends('admin.layout')
@section('content')
    <div class="content-header">
        <h1>Danh sách thương hiệu sản phẩm</h1>
        <a href="{{ route('admin.brands.create') }}" class="add-btn">Thêm mới</a>
        <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    </div>

    <div class="table-container">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Tên</th>
                    <th>Logo</th>
                    <th>Mô tả</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @if($brands->isEmpty())
                    <tr>
                        <td colspan="4">Không có thương hiệu nào</td>
                    </tr>
                @else
                    @foreach($brands as $brand)
                        <tr>
                            <td>{{ $brand->brandId }}</td>
                            <td>{{ $brand->brandName }}</td>
                            <td>
                                @if($brand->logo)
                                    <img src="{{ asset($brand->logo) }}" alt="{{ $brand->brandName }}" style="max-width: 80px;">
                                @else
                                    Không có logo
                                @endif
                            </td>
                            <td>{{ $brand->brandDescription }}</td>

                            <td>
                                <a href="{{ route('admin.brands.edit', $brand->brandId) }}">
                                    <button class="btn-action edit">Sửa</button>
                                </a>
                                <form action="{{ route('admin.brands.destroy', $brand->brandId) }}" method="POST"
                                    style="display: inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action delete"
                                        onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <script src="{{ asset('assets/js/admin-js/notifications.js') }}"></script>
    <script>
        @if (Session::has('success'))
            // Gọi hàm JS từ file notification.js
            showToast('success', "{{ Session::get('success') }}");
        @endif

        @if (Session::has('error'))
            showToast('error', "{{ Session::get('error') }}");
        @endif
    </script>
@endsection