@extends('layouts.master')
@section('title') | รายการชื่อผู้ใช้ @stop
@section('content')
    <div class="container">
        <h1>รายการชื่อผู้ใช้ </h1>
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title"><strong>รายการ</strong></div>
            </div>
            <div class="panel-body">
                <!-- search form -->
                <form action="{{ URL::to('users/search') }}" method="post" class="form-inline">
                    <input type="text" name="q" class="form-control" placeholder="...">
                    <a href="{{ URL::to('users/edit') }}" class="btn btn-success pull-right">เพิ่มชื่อผู้ใช้
                    </a>
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-primary">ค้นหา</button>
                </form>
            </div>


            <table class="table table-bordered bs-table">
                <thead>
                    <tr>
                        <th>ชื่อ</th>
                        <th>อีเมล</th>

                        <th>สถานะ</th>
                        <th>การทํางาน</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>

                            <td>{{ $u->level }}</td>
                            <td class="bs-center">
                                <a href="{{ URL::to ('users/edit/' .$u->id )}}" class="btn btn-info">
                                    <i class="fa fa-edit"></i> แก้ไข</a>
                                <a href="#" class="btn btn-danger btn-delete" id-delete="{{ $u->id }}">
                                    <i class="fa fa-trash"></i> ลบ</a>
                            </td>

                    </tr> @endforeach
                <tfoot>
                </tfoot>
                </tbody>
            </table>
            <div class="panel-footer">
            </div>
            <script>
            // ใช้เทคนิค jQuery
            $('.btn-delete').on('click', function () {
                if (confirm("คุณต้องการลบข้อมูลสินค้าหรือไม่?")) {
                    var url = "{{ URL::to('users/remove') }}"
                        + '/' + $(this).attr('id-delete'); window.location.href = url;
                }
            });
        </script>
    
@endsection