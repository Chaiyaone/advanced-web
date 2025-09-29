@extends('layouts.master')

@section('title', 'รายการสั่งซื้อสินค้า')

@section('content')
<div class="container">
    <h1><i class="fa fa-list"></i> รายการสั่งซื้อสินค้า</h1>
    
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <strong>แสดงรายการสั่งซื้อทั้งหมด</strong>
            </div>
        </div>
        
        <div class="panel-body">
            <!-- ฟอร์มค้นหา -->
            <form action="{{ url('order/search') }}" method="POST" class="form-inline">
                {{ csrf_field() }}
                <input type="text" name="query" class="form-control" 
                       placeholder="ค้นหาเลขที่ใบสั่งซื้อ, ชื่อลูกค้า" 
                       value="{{ isset($query) ? $query : '' }}">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-search"></i> ค้นหา
                </button>
                <a href="{{ url('order') }}" class="btn btn-default">
                    <i class="fa fa-refresh"></i> แสดงทั้งหมด
                </a>
            </form>
        </div>
        
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th width="60">ID</th>
                    <th width="150">เลขที่ใบสั่งซื้อ</th>
                    <th>ชื่อลูกค้า</th>
                    <th>อีเมล</th>
                    <th width="120">วันที่สั่งซื้อ</th>
                    <th width="100" class="text-center">รายละเอียด</th>
                    <th width="130" class="text-center">สถานะการชำระเงิน</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->email }}</td>
                    <td>
                        {{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') : '-' }}
                    </td>
                    <td class="text-center">
                        <a href="{{ url('order/' . $order->id) }}" class="btn btn-info btn-sm">
                            <i class="fa fa-eye"></i> รายละเอียด
                        </a>
                    </td>
                    <td class="text-center">
                        @if($order->status == 'ชำระเงินแล้ว')
                            <span class="label label-success" style="font-size: 14px;">
                                <i class="fa fa-check"></i> ชำระเงินแล้ว
                            </span>
                        @else
                            <span class="label label-danger" style="font-size: 14px;">
                                <i class="fa fa-clock-o"></i> ยังไม่ชำระเงิน
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
                
                @if(count($orders) == 0)
                <tr>
                    <td colspan="7" class="text-center">
                        <h4>ไม่พบข้อมูลการสั่งซื้อ</h4>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<style>
.label {
    display: inline-block;
    padding: 5px 10px;
}
</style>
@endsection