@extends("layouts.master")
@section('content')
<div class="container">
    <h1>ชําระเงิน</h1>

    <div class="breadcrumb">
        <li><a href="{{ URL::to('home') }}"><i class="fa fa-home"></i> หน้าร้าน</a></li>
        <li><a href="{{ URL::to('cart/view') }}">สินค้าในตะกร้า</a></li>
        <li class="active">ชําระเงิน</li>
    </div>
    
    {{-- แสดง Error Messages --}}
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="row">
        <div class="col-md-6">
            <table class="table bs-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>รหัส</th>
                        <th>ชื่อสินค้า</th>
                        <th>จํานวน</th>
                        <th class="bs-price">ราคา</th>
                    </tr>
                </thead>
                <tbody>
<<<<<<< HEAD
                    <?php $sum_price = 0;
                    $sum_qty = 0; ?>
=======
                    @php 
                        $sum_price = 0;
                        $sum_qty = 0;
                    @endphp
>>>>>>> feature_order
                    @foreach($cart_items as $c)
                        <tr>
                            <td><img src="{{ asset($c['image_url']) }}" width="32"></td>
                            <td>{{ $c['code'] }}</td>
                            <td>{{ $c['name'] }}</td>
                            <td>{{ number_format($c['qty'], 0) }}</td>
                            <td class="bs-price">{{ number_format($c['price'], 0) }}</td>
                        </tr>
                        @php
                            $sum_price += ($c['price'] * $c['qty']);
                            $sum_qty += $c['qty'];
                        @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">รวม</th>
                        <th>{{ number_format($sum_qty, 0) }}</th>
                        <th class="bs-price">{{ number_format($sum_price, 0) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title">
                        <strong>ข้อมูลลูกค้า</strong>
                    </div>
                </div>
                <div class="panel-body">
                    <form id="customer-form">
                        <div class="form-group">
                            <label>ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="cust_name" 
                                   placeholder="ชื่อ-นามสกุล" required>
                        </div>
                        <div class="form-group">
                            <label>อีเมล <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="cust_email" 
                                   placeholder="อีเมล์ของท่าน" required>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<<<<<<< HEAD

    </div>
    <a href="{{ URL::to('cart/view') }}" class="btn btn-default">ย้อนกลับ </a>
    <div class="pull-right">
            <a href="{{ URL::to('cart/complete') }}" class="btn btn-warning">พิมพ์ใบสั่งซื้อ</a>
            <button type="button" class="btn btn-primary" onclick="complete()"><i class="fa fa-check"></i>จบการขาย</button>
    </div>
        <form id="finishForm" method="POST" action="{{ route('orders.finish') }}" style="display:none;">
            @csrf
            <input type="hidden" name="cust_name" id="finish_cust_name">
            <input type="hidden" name="cust_email" id="finish_cust_email">
        </form>
        <script type="text/javascript">
            function complete() {
                window.open(
                    "{{ URL::to('cart/complete') }}?cust_name=" + $('#cust_name').val() + '&cust_email=' + $('#cust_email').val(), "_blank"
                );
                // set hidden form values
                document.getElementById('finish_cust_name').value = $('#cust_name').val();
                document.getElementById('finish_cust_email').value = $('#cust_email').val();
                // submit POST form
                document.getElementById('finishForm').submit();
            }
        </script>
=======
    </div>
    
    <a href="{{ URL::to('cart/view') }}" class="btn btn-default">ย้อนกลับ</a>
    <div class="pull-right">
        <a href="javascript:complete()" class="btn btn-primary">
            <i class="fa fa-check"></i> จบการขาย
        </a>
    </div>
    
    {{-- Form สำหรับส่งข้อมูล --}}
    <form id="finish-form" action="{{ URL::to('cart/finish') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="cust_name" id="form_cust_name">
        <input type="hidden" name="cust_email" id="form_cust_email">
    </form>
>>>>>>> feature_order
</div>

<script type="text/javascript">
function complete() {
    // Validate
    var custName = $('#cust_name').val();
    var custEmail = $('#cust_email').val();
    
    if (!custName || !custEmail) {
        alert('กรุณากรอกข้อมูลลูกค้าให้ครบถ้วน');
        return;
    }
    
    // Validate email format
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(custEmail)) {
        alert('รูปแบบอีเมลไม่ถูกต้อง');
        return;
    }
    
    // ถามยืนยัน
    if (confirm('ยืนยันการสั่งซื้อ?')) {
        // ใส่ค่าลง form
        $('#form_cust_name').val(custName);
        $('#form_cust_email').val(custEmail);
        
        // Submit form
        $('#finish-form').submit();
    }
}
</script>
@endsection