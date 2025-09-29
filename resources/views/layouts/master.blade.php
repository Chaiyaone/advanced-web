
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/toastr/toastr.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/angular.min.js') }}"></script>
    <script src="{{ asset('vendor/toastr/toastr.min.js') }}"></script>
    <title>@yield("title", "BikeShop | จำหน่ายอะไหล่รถออนไลน์")</title>
<!-- css -->
<!-- js -->
</head>

<body>
    <nav class="navbar navbar-default navbar-static-top">

        <div class="navbar-header">
                <a href="#" class="navbar-brand">BikeShop</a>
        </div>
        <ul class="nav navbar-nav pull-right">
            @guest
                <li><a href="{{URL::to('home')}}">หน้าแรก</a></li>
                <li><a href="{{ route('login') }}">เข้าสู่ระบบ</a></li>
                <li><a href="{{ route('register') }}">ลงทะเบียน</a></li>
            @endguest

            @auth
                <li><a href="{{URL::to('home')}}">หน้าแรก</a></li>
                @if(Auth::user()->level =='admin')
                    <li><a href="{{ URL::to('product') }}" >ข้อมูลสินค้า</a></li>
                    <li><a href="{{ URL::to('category') }}" >ข้อมูลประเภทสินค้า</a></li>
                    <li><a href="{{URL::to('users')}}">ข้อมูลผู้ใช้</a></li>
                    <li><a href="#">{{ Auth::user()->name }} </a></li>
                    <li><a href="{{ route('logout') }}">ออกจากระบบ </a></li>
                @endif
                @if(Auth::user()->level == 'employee')
                    <li><a href="{{ URL::to('product') }}" >ข้อมูลสินค้า</a></li>
                    <li><a href="{{ URL::to('category') }}" >ข้อมูลประเภทสินค้า</a></li>
                    <li><a href="#" >ข้อมูลการสั่งซื้อสินค้า</a></li> //ยังไม่มีURLคำสั่งซื้อ
                    <li><a href="#">{{ Auth::user()->name }} </a></li>
                    <li><a href="{{ route('logout') }}">ออกจากระบบ </a></li> 
                @endif
                @if(Auth::user()->level == 'customer')
                    <li>
                        <a href="{{URL::to('/cart/view')}}"><i class="fa fa-shopping-cart"></i> ตะกร้า
                            <span class="label label-danger">
                            @if(Session::has('cart_items'))
                                {{count(Session::get('cart_items'))}}
                            @else
                                {{count(array())}}
                            @endif
                            </span>
                        </a>
                    </li>
                    <li><a href="#">{{ Auth::user()->name }} </a></li>
                    <li><a href="{{ route('logout') }}">ออกจากระบบ </a></li>
                @endif
            @endauth
        </div>
    </div>
    </nav> @yield("content")
    
    <!-- js -->
    @if(session('msg'))
        @if(session('ok'))
            <script>toastr.success("{{ session('msg') }}")</script>
        @else
            <script>toastr.error("{{ session('msg') }}")</script>
        @endif
    @endif

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <div class="container">
        <nav class="navbar navbar-default navbar-static-top">

            <div class="navbar-header">
                <a class="navbar-brand" href="#">BikeShop</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">

                    <li><a href="{{ URL::to('home') }}">หน้าแรก</a></li>@guest
                    @else
                        <li><a href="{{ URL::to('product') }}">ข้อมูลสินค้า </a></li>
                    <li><a href="{{ URL::to('category') }}">ข้อมูลประเภทสินค้า</a></li>
                    <li><a href="{{ URL::to('users') }}">ข้อมูลผู้ใช้</a></li>@endguest
                </ul>
                <ul class="nav navbar-nav navbar-right">
    <li>
        <a href="{{ URL::to('cart/view') }}">
            <i class="fa fa-shopping-cart"></i> ตะกร้า
            <span class="label label-danger">
                {!! count(Session::get('cart_items', [])) !!}
            </span>
        </a>
    </li>
    @guest
        <li><a href="{{ route('login') }}">ล็อกอิน</a></li>
        <li><a href="{{ route('register') }}">ลงทะเบียน</a></li>
    @else
        <li><a href="#">{{ Auth::user()->name }} </a></li>
        <li><a href="{{ route('logout') }}">ออกจากระบบ </a></li>
    @endguest
</ul>
            </div>

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    
<!-- js -->

</body>
</html>