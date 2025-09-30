@extends("layouts.master") @section('title') BikeShop | แก้ไขข้อมูลผู้ใช้ @stop
@section('content')


    @if($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

        <div class="container">
    <h1>แก้ไขข้อมูลผู้ใช้  </h1>
    <ul class="breadcrumb">
        <li><a href="{{ URL::to('users') }}">หน้าแรก</a></li>
        <li class="active">แก้ไขข้อมูลผู้ใช้  </li>
    </ul>

{!! Form::model($user, array('url' => 'users/update', 'method' => 'POST', 'enctype' => 'multipart/form-data')) !!}
<input type="hidden" name="id" value="{{ $user->id }}">


    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <strong>ข้อมูลผู้ใช้ </strong>
            </div>
        </div>
        <div class="panel-body">
            <table>
                <tr>
                    <td>{{ Form::label('name', 'ชื่อผู้ใช้ ') }}</td>
                    <td>{{ Form::text('name', $user->name, ['class' => 'form-control']) }}</td>
                </tr>
                <tr>
                    <td>{{ Form::label('email', 'อีเมล') }}</td>
                    <td>{{ Form::text('email', $user->email, ['class' => 'form-control']) }}</td>
                </tr>
                <tr>
                    <td>{{ Form::label('level', 'ประเภทผู้ใช้ ') }}</td>
                    <td>{{ Form::select('level', $user_levels, $user->level, ['class' => 'form-control']) }}</td>
                </tr>

            </table>
        </div>
        <div class="panel-footer">
            <button type="reset" class="btn btn-danger">ยกเลิก</button>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> บันทึก</button>
        </div>
    </div>

{!! Form::close() !!} 
@endsection