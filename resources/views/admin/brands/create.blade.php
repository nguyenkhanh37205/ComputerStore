@extends('admin.layout')
@section('content')
    <div class="content-header">
        <h1>Thêm mới thương hiệu sản phẩm</h1>
        <a href="{{ route('admin.brands.index') }}" class="back-btn">Quay lại danh sách</a>
    </div>
    <form action="{{ route('admin.brands.store') }}" method="POST" class="form-container">
        @csrf
        <div class="form-group">
            <label for="name">Tên thương hiệu:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <button type="submit" class="submit-btn">Lưu</button>
    </form>
@endsection