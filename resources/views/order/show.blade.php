@extends('layouts.master')

@section('title', 'รายละเอียดการสั่งซื้อ')

@section('content')
<div class="container">
    <h1><i class="fa fa-file-text"></i> รายละเอียดการสั่งซื้อสินค้า</h1>
    
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="panel-title">
                <strong>ข้อมูลการสั่งซื้อ</strong>
            </div>
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="140">เลขที่ใบสั่งซื้อ:</th>
                            <td><strong>{{ $order->order_number }}</strong></td>
                        </tr>
                        <tr>
                            <th>ชื่อลูกค้า:</th>
                            <td>{{ $order->customer_name }}</td>
                        </tr>
                        <tr>
                            <th>อีเมล:</th>
                            <td>{{ $order->email }}</td>
                        </tr>
                    </table>
                </div>
                
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="140">วันที่สั่งซื้อ:</th>
                            <td>{{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>สถานะการชำระเงิน:</th>
                            <td>
                                <form action="{{ url('order/' . $order->id . '/status') }}" method="POST" style="display: inline;">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    
                                    <select name="status" class="form-control" onchange="this.form.submit()" style="width: auto; display: inline-block;">
                                        <option value="ยังไม่ชำระเงิน" 
                                                {{ $order->status == 'ยังไม่ชำระเงิน' ? 'selected' : '' }}
                                                class="text-danger">
                                            ยังไม่ชำระเงิน
                                        </option>
                                        <option value="ชำระเงินแล้ว" 
                                                {{ $order->status == 'ชำระเงินแล้ว' ? 'selected' : '' }}
                                                class="text-success">
                                            ชำระเงินแล้ว
                                        </option>
                                    </select>
                                    
                                    @if($order->status == 'ชำระเงินแล้ว')
                                        <span class="label label-success">
                                            <i class="fa fa-check"></i> ชำระแล้ว
                                        </span>
                                    @else
                                        <span class="label label-danger">
                                            <i class="fa fa-clock-o"></i> รอชำระ
                                        </span>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- รายละเอียดสินค้าที่สั่งซื้อ -->
    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="panel-title">
                <strong>รายการสินค้า</strong>
            </div>
        </div>
        
        <table class="table table-bordered table-striped">
            <thead>
                <tr class="bg-primary">
                    <th width="60" class="text-center">ลำดับ</th>
                    <th>ชื่อสินค้า</th>
                    <th width="120" class="text-right">ราคาต่อหน่วย</th>
                    <th width="80" class="text-center">จำนวน</th>
                    <th width="120" class="text-right">รวมเงิน</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach($order->orderDetails as $detail)
                <tr>
                    <td class="text-center">{{ $i++ }}</td>
                    <td>{{ $detail->product_name }}</td>
                    <td class="text-right">{{ number_format($detail->price, 2) }}</td>
                    <td class="text-center">{{ $detail->quantity }}</td>
                    <td class="text-right">{{ number_format($detail->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-warning">
                    <th colspan="4" class="text-right">รวมเงินทั้งหมด:</th>
                    <th class="text-right" style="font-size: 18px;">
                        {{ number_format($total_amount, 2) }} บาท
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <!-- ปุ่มกลับ -->
    <div class="form-group">
        <a href="{{ url('order') }}" class="btn btn-default">
            <i class="fa fa-arrow-left"></i> กลับไปหน้ารายการ
        </a>
        
        <button onclick="window.print()" class="btn btn-info">
            <i class="fa fa-print"></i> พิมพ์ใบสั่งซื้อ
        </button>
    </div>
</div>

@if(session('success'))
<script>
    alert('{{ session("success") }}');
</script>
@endif

<style>
@media print {
    .btn, .form-control, select {
        display: none !important;
    }
    
    .label {
        border: 1px solid #000 !important;
        padding: 2px 5px !important;
    }
}

select.form-control {
    padding: 5px 10px;
}

.label {
    margin-left: 10px;
}
</style>
@endsection