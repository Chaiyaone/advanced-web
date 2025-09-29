@extends('layouts.master')
@section('title') BikeShop | รายละเอียดการสั่งซื้อสินค้า @endsection

@section('content')
<div class="container">
    <h3>รายละเอียดการสั่งซื้อสิ้นค้า</h3>

    <table class="table table-bordered" style="width: 50%;">
        <tbody>
            <tr>
                <th>เลขที่ใบสั่งซื้อ</th>
                <td>{{ $order->order_number }}</td>
                <td></td>
            </tr>
            <tr>
                <th>ชื่อลูกค้า</th>
                <td>{{ $order->customer_name ?? '???' }}</td>
                <td></td>
            </tr>
            <tr>
                <th>อีเมล์</th>
                <td><a href="mailto:{{ $order->email }}">{{ $order->email }}</a></td>
                <td></td>
            </tr>
            <tr>
                <th>วันที่สั่งซื้อสินค้า</th>
                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('Y-m-d') }}</td>
                <td></td>
            </tr>
            <tr>
                <th>สถานะการชำระเงิน</th>
                <td @if($order->status === 'ชำระเงินแล้ว') style="background-color:#9fff9f" @elseif($order->status === 'ยังไม่ชำระเงิน') style="background-color:#fdd0a2" @endif>
                    {{ $order->status }}
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered text-center">
        <thead class="table-light">
            <tr>
                <th>ลำดับ</th>
                <th>ชื่อสินค้า</th>
                <th>ราคาต่อหน่วย</th>
                <th>จำนวน</th>
                <th>รวมเงิน</th>
            </tr>
        </thead>
        <tbody>
            @php $totalAmount = 0; @endphp
            @foreach($orderItems as $index => $item)
                @php
                    $subtotal = $item->price * $item->quantity;
                    $totalAmount += $subtotal;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-start">{{ $item->product_name }}</td>
                    <td>{{ number_format($item->price) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($subtotal) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" class="text-end"><strong>รวมเงิน</strong></td>
                <td><strong>{{ number_format($totalAmount) }} บาท</strong></td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
