@extends('admin.layout')
@section('content')
    <div class="content-header">
        <h1>Danh sách thương hiệu sản phẩm</h1>
        <a href="{{ route('admin.brands.create') }}" class="add-btn">Thêm mới</a>
        <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Tên</th>
                    <th>Mô tả</th>
                    <th>Logo</th>
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
                                <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->brandName }}" width="100">
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
@endsection