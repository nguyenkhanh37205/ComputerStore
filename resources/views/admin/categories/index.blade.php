@extends('admin.layout')
@section('content')
    <div class="content-header">
        <h1>Danh sách danh mục sản phẩm</h1>
        <a href="{{ route('admin.categories.create') }}" class="add-btn">Thêm mới</a>
        <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Tên</th>
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
@endsection