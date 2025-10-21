@extends('admin.layout')
@section('content')
<div class="content-header">
    <h1>Danh sách loại sản phẩm</h1>
    <a href="#" class="add-btn">Thêm mới</a>
    
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>TÊN</th>
                <th>HÀNH ĐỘNG</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>0</td>
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