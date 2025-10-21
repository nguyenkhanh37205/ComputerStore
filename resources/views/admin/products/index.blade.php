@extends('admin.layout')
@section('content')
<div class="content-header">
    <h1>Danh sách sản phẩm</h1>
    <a href="#" class="add-btn">Thêm mới</a>
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>HÌNH ẢNH</th>
                <th>TÊN</th>
                <th>LOẠI</th>
                <th>GIÁ</th>
                <th>HÀNH ĐỘNG</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <button class="btn-action edit">Sửa</button>
                    <button class="btn-action delete">Xóa</button>
                </td>
            </tr>
            
        </tbody>
    </table>
</div>
@endsection