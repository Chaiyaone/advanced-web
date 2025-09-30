@extends("layouts.master")
@section('title') BikeShop | อุปกรณ์จักรยาน, อะไหล่, ชุดแข่ง และอุปกรณ์ตกแต่ง @endsection
@section('content')
<div class="container">
    <h3>แสดงการสั่งซื้อสินค้า</h3>

    <table class="table table-bordered text-center align-middle">
        <thead class="table-light">
            <tr>
                <th class="text-center">OrderID</th>
                <th class="text-center">เลขที่ใบสั่งซื้อ</th>
                <th class="text-center">ชื่อลูกค้า</th>
                <th class="text-center">วันที่สั่งซื้อสินค้า</th>
                <th class="text-center">รายละเอียด</th>
                <th class="text-center">สถานะการชำระเงิน</th>
            </tr>
        </thead>
        <tbody>
                @foreach($orders->sortBy(function($order) { return substr($order->order_number, -3); }) as $order)
                    <tr>
                        <td class="text-left">{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->customer_name ?? '???' }}</td>
                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-outline-primary detail-link">
                            รายละเอียด
                        </a>
                        <style>
                            .detail-link:hover {
                                text-decoration: underline;
                            }
                        </style>
                    </td>
                    <td 
                        @if($order->status === 'ชำระเงินแล้ว') style="background-color:#9fff9f" 
                        @elseif($order->status === 'ยังไม่ชำระเงิน') style="background-color:#fdd0a2" 
                        @endif>
                        {{ $order->status }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $orders->links() }}
</div>
@endsection
