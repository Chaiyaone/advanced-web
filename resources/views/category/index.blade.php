@extends('layouts.master')
@section('title') | รายการสินค้า @stop
@section('content')
    <div class="container">
        <h1>รายการประเภทสินค้า </h1>
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title"><strong>รายการ</strong></div>
            </div>
            <div class="panel-body">
                <!-- search form -->
                <form action="{{ URL::to('category/search') }}" method="post" class="form-inline">
                    <input type="text" name="q" class="form-control" placeholder="...">
                    <a href="{{ URL::to('category/edit') }}" class="btn btn-success pull-right">เพิ่มประเภทสินค้า
                    </a>
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-primary">ค้นหา</button>
                </form>
            </div>
            <table class="table table-bordered bs-table">
                <thead>
                    <tr>
                        <th>ชื่อประเภทสินค้า</th>
                        <th>การทำงาน</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $c)
                        <tr>
                            <td>{{ $c->name }}</td>
                            <td class="bs-center">
                                <a href="{{ URL::to('category/edit/' . $c->id) }}" class="btn btn-info">
                                    <i class="fa fa-edit"></i> แก้ไข</a>
                                <a href="#" class="btn btn-danger btn-delete" id-delete="{{ $c->id }}"><i
                                        class="fa fa-trash"></i> ลบ</a>

                            </td>

                    </tr> @endforeach
                <tfoot>
                    <tr>
                        <th colspan="2">
                            แสดงข้อมูลทั้งหมด {{ count($categories) }} รายการ
                        </th>
                    </tr>
                </tfoot>
                </tbody>
            </table>
            <div class="panel-footer">
            </div>
        </div>

        <script>
            $(function () {
                $('.btn-delete').on('click', function (e) {
                    e.preventDefault();
                    if (confirm("คุณต้องการลบข้อมูลประเภทสินค้าหรือไม่?")) {
                        var url = "{{ URL::to('category/remove') }}" + '/' + $(this).attr('id-delete');
                        window.location.href = url;
                    }
                });
            });
        </script>
@endsection