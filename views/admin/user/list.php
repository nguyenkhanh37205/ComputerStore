@extends('admin.layout')

@section('content')
<div class="content-header">
    <h1>Danh sách người dùng</h1>
    <a href="{{ route('admin.users.create') }}" class="add-btn">Thêm mới</a>
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}"> --}}
</div>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>TÀI KHOẢN</th>
                <th>TÊN ĐẦY ĐỦ</th>
                <th>EMAIL</th>
                <th>SỐ ĐIỆN THOẠI</th>
                <th>QUYỀN</th>
                <th>NGÀY TẠO</th> <th>HÀNH ĐỘNG</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->userId }}</td> <td>{{ $user->username }}</td>
                <td>{{ $user->fullName }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone }}</td>
                <td>{{ $user->role }}</td>
                <td>{{ $user->createdat }}</td> <td>
                    <a href="{{ route('admin.users.edit', $user->userId) }}" class="btn-action edit">Sửa</a>
                    
                    <form action="{{ route('admin.users.destroy', $user->userId) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action delete" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng {{ $user->username }} không?');">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
            
            @if ($users->isEmpty())
                <tr>
                    <td colspan="8" style="text-align: center;">Không có người dùng nào được tìm thấy.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection