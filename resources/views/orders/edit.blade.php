@extends('layouts.master')
@section('title')
    BikeShop | รายละเอียดการสั่งซื้อสินค้า
@endsection

@section('content')
    <div class="container">
        <h3>รายละเอียดการสั่งซื้อสิ้นค้า</h3>
        <div style="background:#f7f7f7; border-radius:6px; padding:10px 20px; margin-bottom:20p">
            <a href="/orders" class="breadcrumb-link">แสดงการสั่งซื้อสินค้า</a>
            <style>
                .breadcrumb-link {
                    color: #1976d2;
                    text-decoration: none;
                    font-weight: bold;
                    transition: background 0.2s;
                }
                .breadcrumb-link:hover {
                    background: #e3f2fd;
                    text-decoration: underline;
                }
            </style>
            <span style="color:#888;"> / </span>
            <span style="color:#888;">แก้ไขสินค้า</span>
        </div>

        <table class="table table-bordered" style="width: 50%; table-layout:fixed;">
            <colgroup>
                <col style="width:33.33%">
                <col style="width:33.33%">
                <col style="width:33.33%">
            </colgroup>
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
                    <td
                        @if ($order->status === 'ชำระเงินแล้ว') style="background-color:#9fff9f" 
                    @elseif($order->status === 'ยังไม่ชำระเงิน') style="background-color:#fdd0a2" @endif>
                        <form method="POST" action="{{ route('orders.edit', $order->id) }}" id="statusForm">
                            @csrf
                            <select name="status" class="form-control"
                                style="width:100%; height:100%; min-width:0; min-height:0; display:block; background-color:inherit; box-sizing:border-box; border:none; outline:none;"
                                onchange="document.getElementById('statusForm').submit();">
                                <option value="ยังไม่ชำระเงิน" {{ $order->status == 'ยังไม่ชำระเงิน' ? 'selected' : '' }}>
                                    ยังไม่ชำระเงิน</option>
                                <option value="ชำระเงินแล้ว" {{ $order->status == 'ชำระเงินแล้ว' ? 'selected' : '' }}>
                                    ชำระเงินแล้ว</option>
                            </select>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="text-align: left; width: 8%;">ลำดับ</th>
                    <th style="text-align: left; width: 42%;">ชื่อสินค้า</th>
                    <th style="text-align: left; width: 18%;">ราคาต่อหน่วย</th>
                    <th style="text-align: left; width: 12%;">จำนวน</th>
                    <th style="text-align: left; width: 20%;">รวมเงิน</th>
                </tr>
            </thead>
            <tbody>
                @php $totalAmount = 0; @endphp
                @foreach ($orderItems as $index => $item)
                    @php
                        $subtotal = $item->price * $item->quantity;
                        $totalAmount += $subtotal;
                    @endphp
                    <tr>
                        <td style="text-align: left;">{{ $index + 1 }}</td>
                        <td style="text-align: left;">{{ $item->product_name }}</td>
                        <td style="text-align: right;">{{ number_format($item->price) }}</td>
                        <td style="text-align: right;">{{ $item->quantity }}</td>
                        <td style="text-align: right;">{{ number_format($subtotal) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4" style="text-align: right; font-weight: bold;">รวมเงิน</td>
                    <td style="text-align: right; font-weight: bold;">{{ number_format($totalAmount) }} บาท</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
