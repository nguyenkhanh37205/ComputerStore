@extends('admin.layout')
@section('content')
    <div class="content-header">
        <h1>Danh sách danh mục sản phẩm</h1>
        <a href="{{ route('admin.categories.create') }}" class="add-btn">Thêm mới</a>
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
                    <th>Hình ảnh</th>
                    <th>Mô tả</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @if($categories->isEmpty())
                    <tr>
                        <td colspan="4" class="text-center">Không có danh mục nào.</td>
                    </tr>
                @else
                    @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->categoryId }}</td>
                            <td>{{ $category->categoryName }}</td>
                            <td>
                                @if($category->image)
                                    <img src="{{ asset($category->image) }}" alt="{{ $category->categoryName }}" style="max-width: 80px;">
                                @else
                                    <span class="no-image">Không có hình ảnh</span>
                                @endif
                            </td>
                            <td>{{ $category->categoryDescription }}</td>

                            <td>
                                <a href="{{ route('admin.categories.edit', $category->categoryId) }}">
                                    <button class="btn-action edit">Sửa</button>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category->categoryId) }}" method="POST"
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