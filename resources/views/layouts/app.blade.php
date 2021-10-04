<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }} " dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="RLPFP2ey8-bMtn1JIqJHxMQCRyTNYmLEmhha4V5rBVU">


    <script type="text/javascript">
        function callbackThen(response) {
            // read HTTP status
            console.log(response.status);

            // read Promise object
            response.json().then(function (data) {
                console.log(data);
            });
        }

        function callbackCatch(error) {
            console.error('Error:', error)
        }
    </script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! htmlScriptTagJsApi() !!}


    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body class="index-page {{Route::is('panel.view')?'g-sidenav-show rtl':''}} ">


<div id="app">
    @if(! str_contains( url()->current(),'/panel'))
        <div class="container position-sticky z-index-sticky top-0">
            <div class="row">
                <div class="col-12">

                    <nav class="   navbar navbar-expand-lg  blur blur-rounded top-0 z-index-fixed shadow position-absolute my-3   start-0 end-0 mx-4">
                        <div class="container-fluid">
                            <a class="navbar-brand font-weight-bolder ms-sm-3"
                               href="{{route('/')}}" rel="tooltip"
                               title="بازارچه ورتا" data-placement="bottom">
                                بازارچه ورتا
                            </a>
                            @if(!auth()->user() || auth()->user()->role=='us')
                                <div class="navbar-nav nav-item mr-auto ">
                                    <a href="{{auth()->user()? url('panel/my-orders/cart') : route('cart.view')}}"
                                       class="  position-relative   ">
                                        <i class="fa fa-2x fa-cart-plus move-on-hover " aria-hidden="true"></i>
                                        <span class=" position-absolute top-0 start-0 translate-middle p-2 badge rounded-circle bg-danger">
    {{\App\Models\Cart::count()}}
                                            <span class="visually-hidden">سبد خرید</span>
  </span>
                                    </a>
                                </div>
                            @endif
                            <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false"
                                    aria-label="Toggle navigation">
              <span class="navbar-toggler-icon mt-2">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </span>

                            </button>

                            <div class="collapse navbar-collapse   py-md-0 w-100" id="navigation">
                                <ul class="navbar-nav navbar-nav-hover      align-items-baseline">
                                    <li class="nav-item dropdown dropdown-hover   w-nlg-100 ">
                                        <a class="nav-link ps-2 d-flex justify-content-between cursor-pointer align-items-center"
                                           id="dropdownMenuPages" data-bs-toggle="dropdown" aria-expanded="false">
                                            محصولات
                                            <img src="{{asset("img/down-arrow-dark.svg")}}" alt="down-arrow"
                                                 class="arrow ms-1">
                                        </a>
                                        <div class="  dropdown-menu dropdown-menu-animation dropdown-md p-3 border-radius-lg mt-3   left-0 right-0  position-lg-fixed  "
                                             aria-labelledby="dropdownMenuPages">
                                            <div class="   row   text-right">
                                                @foreach(\App\Models\Group::on(env('DB_CONNECTION'))->whereIn('id',[31,40])->orderByDesc('id')->get() as $g)
                                                    <div class="col-md-6  ">
                                                        <div class="dropdown-header text-dark font-weight-bolder      ">
                                                            <i class="fa fa-cart-arrow-down   mx-1"
                                                               aria-hidden="true"></i>
                                                            {{$g->name}}

                                                        </div>

                                                        <div class="px-3 py-2 bg-gradient-info rounded-lg shadow-blur ">
                                                            @foreach(\App\Models\Group::on(env('DB_CONNECTION'))->where('parent',$g->id)->get() as $gg)

                                                                <a href="{{route('products.view',['group_ids'=>$gg->id])}}"
                                                                   class="d-block px-3 py-1  border-radius-md text-white hoverable">
                                                                    <span class="p-3">{{$gg->name}}</span>
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach

                                            </div>
                                            {{--<div class="d-lg-none">--}}

                                            {{--</div>--}}
                                        </div>
                                    </li>
                                    <li class="nav-item dropdown dropdown-hover   w-nlg-100">
                                        <a class="nav-link ps-2 d-flex justify-content-between cursor-pointer align-items-center"
                                           id="dropdownMenuBlocks" data-bs-toggle="dropdown" aria-expanded="false">
                                            فروشندگان
                                            <img src="{{asset('img/down-arrow-dark.svg')}}" alt="down-arrow"
                                                 class="arrow ms-1">
                                        </a>
                                        <ul class="   dropdown-menu   dropdown-menu-animation dropdown-lg dropdown-lg-responsive p-3 border-radius-lg mt-3 right-0 position-lg-fixed"
                                            aria-labelledby="dropdownMenuBlocks">

                                            <div class="  row ">
                                                @foreach(\App\Models\Shop::on(env('DB_CONNECTION'))->where('active',true)->get() as $shop)
                                                    <li class="nav-item dropdown   dropdown-subitem  col-lg-4 col-md-6 p-0 ">
                                                        <a class="dropdown-item  border-radius-md "
                                                           href="{{route('shop',['name'=>$shop->name,'id'=>$shop->id])}}">
                                                            <div class="d-flex row">
                                                                <div class=" col-3">
                                                                    {{--<i class="ni ni-single-copy-04 text-gradient text-primary"></i>--}}
                                                                    <img src="{{$shop->image}}"
                                                                         class="rounded-circle w-100 "
                                                                         alt="">
                                                                </div>
                                                                <div class="  align-items-center justify-content-between col-9">
                                                                    <div>
                                                                        <h6 class="dropdown-header text-dark font-weight-bolder d-flex justify-content-cente align-items-center p-0">
                                                                            {{$shop->name}}</h6>
                                                                        <span class="text-sm">{{$shop->group->name}}</span>
                                                                    </div>
                                                                    {{--<img src="{{asset('img/down-arrow.svg')}}"--}}
                                                                    {{--alt="down-arrow"--}}
                                                                    {{--class="arrow">--}}
                                                                </div>
                                                            </div>
                                                        </a>
                                                        {{--<div class="dropdown-menu mt-0 py-3 px-2 mt-3">--}}
                                                        {{--<a class="dropdown-item ps-3 border-radius-md mb-1"--}}
                                                        {{--href="./sections/page-sections/hero-sections.html">--}}
                                                        {{--Page Headers--}}
                                                        {{--</a>--}}
                                                        {{--<a class="dropdown-item ps-3 border-radius-md mb-1"--}}
                                                        {{--href="./sections/page-sections/features.html">--}}
                                                        {{--Features--}}
                                                        {{--</a>--}}
                                                        {{--</div>--}}
                                                    </li>
                                                @endforeach
                                            </div>
                                            {{--<div class="row d-lg-none">--}}
                                            {{----}}
                                            {{--</div>--}}
                                        </ul>
                                    </li>
                                    <li class="nav-item  mx-2 w-nlg-100">
                                        <a class="nav-link ps-2 d-flex justify-content-between cursor-pointer align-items-center text-dark"
                                           id="weblog" aria-expanded="false"
                                           href="{{route('blog.view')}}">
                                            وبلاگ
                                        </a>
                                    </li>

                                    @guest

                                        <li class="my-1   w-100">
                                            <div class="navbar-nav mx-1">
                                                <!-- Authentication Links -->

                                                <div class=" btn-group    " dir="ltr">
                                                    <a class="btn  bg-gradient-info my-0 " type="button"

                                                       href=" {{ url('login') }} ">
                                                        ورود
                                                    </a>
                                                    <a class="btn bg-gradient-primary my-0" type="button"

                                                       href=" {{ url('register') }} ">
                                                        ثبت نام
                                                    </a>

                                                </div>
                                            </div>
                                        </li>
                                    @endguest
                                    @auth
                                        <li class="nav-item dropdown dropdown-hover mx-2 w-nlg-100">
                                            <a class="nav-link ps-2 d-flex justify-content-between cursor-pointer align-items-center text-primary"
                                               id="dropdownMenuBlocks" data-bs-toggle="dropdown" aria-expanded="false">
                                                {{ Auth::user()->name }}
                                                <img src="{{asset('img/down-arrow-dark.svg')}}" alt="down-arrow"
                                                     class="arrow ms-1">
                                            </a>
                                            <ul class="   dropdown-menu   dropdown-menu-animation dropdown-lg dropdown-lg-responsive   border-radius-lg   right-0 "
                                                aria-labelledby="dropdownMenuuser">

                                                <li class="d-none d-lg-block   w-100  text-right">
                                                    <ul class="dropdown p-0 ">
                                                        <li class="dropdown-item        py-2 ">
                                                            <a class="" href="{{ route('panel.view') }}">

                                                                پنل کاربری
                                                            </a>

                                                        </li>
                                                        <li class=" dropdown-item     py-2 " onclick="event.preventDefault();
    document.getElementById('logout-form').submit();">


                                                            <a class=" " href="{{ route('logout') }}"
                                                            >
                                                                خروج
                                                            </a>

                                                            <form id="logout-form" action="{{ route('logout') }}"
                                                                  method="POST"
                                                                  class="d-none">
                                                                @csrf
                                                            </form>


                                                        </li>
                                                    </ul>
                                                </li>
                                                <li class="  d-lg-none">
                                                    <div class="col-md-12">
                                                        <ul class="dropdown p-0 m-0 text-right">
                                                            <li class="dropdown-item  ">
                                                                <a class="" href="{{ route('panel.view') }}">

                                                                    پنل کاربری
                                                                </a>

                                                            </li>
                                                            <li class=" dropdown-item  ">


                                                                <a class=" " href="{{ route('logout') }}"
                                                                   onclick="event.preventDefault();
    document.getElementById('logout-form').submit();">
                                                                    خروج
                                                                </a>

                                                                <form id="logout-form" action="{{ route('logout') }}"
                                                                      method="POST"
                                                                      class="d-none">
                                                                    @csrf
                                                                </form>


                                                            </li>
                                                        </ul>


                                                    </div>
                                                </li>

                                            </ul>

                                        </li>


                                    @endauth

                                </ul>
                                <div class="flex-fill mr-3 " dir="ltr">
                                    <form id="search-form" class="" action="{{ route('products.view') }}" method="GET">

                                        <div class="input-group   align-items-baseline   mr-auto   max-width-300 ">
                                            <a class="btn bg-gradient-primary my-0 px-3  " type="submit"
                                               id="button-addon1" onclick="event.preventDefault();
   if(document.getElementById('search-input-all').value); document.getElementById('search-form').submit();">
                                                <i class="fa fa-search" aria-hidden="true"></i>

                                            </a>
                                            <input id="search-input-all" type="text" class="form-control   px-2 "
                                                   placeholder="نام محصول..." dir="rtl"
                                                   aria-label="جست و جوی محصول"
                                                   aria-describedby="button-addon1" name="search" required>


                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </nav>
                    <!-- End Navbar -->

                </div>
            </div>
        </div>
    @endif

    {{--<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">--}}
    {{--<div class="container">--}}
    {{--<a class="navbar-brand" href="{{ url('/') }}">--}}
    {{--{{ config('app.name', 'Laravel') }}--}}
    {{--</a>--}}
    {{--<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"--}}
    {{--aria-controls="navbarSupportedContent" aria-expanded="false"--}}
    {{--aria-label="{{ __('Toggle navigation') }}">--}}
    {{--<span class="navbar-toggler-icon"></span>--}}
    {{--</button>--}}

    {{--<div class="collapse navbar-collapse" id="navbarSupportedContent">--}}
    {{--<!-- Left Side Of Navbar -->--}}
    {{--<ul class="navbar-nav mr-auto">--}}

    {{--</ul>--}}

    {{--<!-- Right Side Of Navbar -->--}}
    {{--<ul class="navbar-nav ml-auto">--}}
    {{--<!-- Authentication Links -->--}}
    {{--@guest--}}
    {{--@if (Route::has('login'))--}}
    {{--<li class="nav-item">--}}
    {{--<a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>--}}
    {{--</li>--}}
    {{--@endif--}}

    {{--@if (Route::has('register'))--}}
    {{--<li class="nav-item">--}}
    {{--<a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>--}}
    {{--</li>--}}
    {{--@endif--}}
    {{--@else--}}
    {{--<li class="nav-item dropdown">--}}
    {{--<a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"--}}
    {{--data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>--}}
    {{--{{ Auth::user()->name }}--}}
    {{--</a>--}}

    {{--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">--}}
    {{--<a class="dropdown-item" href="{{ route('logout') }}"--}}
    {{--onclick="event.preventDefault();--}}
    {{--document.getElementById('logout-form').submit();">--}}
    {{--{{ __('Logout') }}--}}
    {{--</a>--}}

    {{--<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">--}}
    {{--@csrf--}}
    {{--</form>--}}
    {{--</div>--}}
    {{--</li>--}}
    {{--@endguest--}}
    {{--</ul>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</nav>--}}

    <main class="py-0 mt-7  ">

        @if (session()->has('error-alert'))

            <div class="alert alert-danger alert-dismissible fade show top-5 left-2 z-index-3 text-right position-absolute"
                 role="alert">
                <strong>  {{session()->get('error-alert')}}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @php(session()->forget('error-alert'))

        @elseif (session()->has('success-alert'))

            <div class="alert alert-success alert-dismissible fade show top-5 left-2 z-index-3 text-right position-absolute"
                 role="alert">
                <strong>   {!! session()->get('success-alert') !!}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @php(session()->forget('success-alert'))
        @endif

        <pusher-chat key="{{env('PUSHER_APP_KEY')}}"
                     cluster="{{env('PUSHER_APP_CLUSTER')}}" ip="{{request()->ip()}}"
                     broadcast-link="{{route('chat.broadcast')}}"
                     support-history-link="{{route('chat.support.history')}}"
                     id="chat-support"></pusher-chat>
        {{--@php( event(new App\Events\ChatEvent(null, null, "his")))--}}
        @yield('content')

    </main>
</div>

<footer class="footer pt-5 mt-5  ">
    {{--<hr class="horizontal dark mb-5">--}}
    <div class=" col-12  ">
        <div class=" row text-end col-12">
            <div class="col-md-4     ">
                <div>
                    <h6 class="text-gradient text-primary font-weight-bolder">ارتباط با ما</h6>
                </div>
                <div class=" ">

                    <ul class="d-flex flex-row-reverse   nav pr-0  ">
                        <li class="nav-item ">
                            <a class="nav-link pe-1" href="https://www.instagram.com/varta.shop/" target="_blank"
                               data-bs-toggle="tooltip" data-bs-placement="bottom" title="اینستاگرام">
                                <i class="fab fa-instagram text-lg opacity-8 "></i>
                            </a>
                        </li>
                        @php( $adminPhone = str_replace('09', '9', \Helper::$admin_phone))
                        <li class="nav-item">
                            <a class="nav-link pe-1 " href="https://wa.me/98{{$adminPhone}}" target="_blank"
                               data-bs-toggle="tooltip" data-bs-placement="bottom" title="واتساپ">
                                <i class="fab fa-whatsapp text-lg opacity-8"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link pe-1" href="https://t.me/develowper" target="_blank"
                               data-bs-toggle="tooltip" data-bs-placement="bottom" title="تلگرام">
                                <i class="fab fa-telegram text-lg opacity-8"></i>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link pe-1" href="https://www.youtube.com/channel/UCzwQ6GnoNQG1PwpqZhkIogA"
                               target="_blank" data-bs-toggle="tooltip" data-bs-placement="bottom" title="یوتیوب">
                                <i class="fab fa-youtube text-lg opacity-8"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4      ">
                <div>
                    <h6 class="text-gradient text-primary text-sm">دسترسی سریع</h6>
                    <ul class="flex-column  nav  pr-0  ">
                        <li class="nav-item hoverable-text-primary">
                            <a class="nav-link" href="{{route('products.view')}}" target="_blank">
                                محصولات
                            </a>
                        </li>
                        <li class="nav-item hoverable-text-primary">
                            <a class="nav-link" href="{{route('shops.view')}}" target="_blank">
                                فروشندگان
                            </a>
                        </li>
                        <li class="nav-item hoverable-text-primary">
                            <a class="nav-link" href="{{route('panel.view')}}" target="_blank">
                                پنل کاربر
                            </a>
                        </li>
                        <li class="nav-item hoverable-text-primary">
                            <a class="nav-link " href="{{route('blog.view')}}"
                               target="_blank">
                                وبلاگ
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4  text-center ">
                <a href="{{route('/')}}">
                    <img src="{{asset('img/vartashop_logo.png')}}" alt=""
                         class="rounded-lg    move-on-hover   " height="300" width="300">
                </a>
            </div>
            <hr class="horizontal dark  ">
            <div class="col-12">
                <div class="text-center">
                    <p class="my-4 text-sm">
                        <a
                                href="https://www.instagram.com/develowper" target="_blank">
                            طراحی با

                            <i class="fa fa-heart text-danger" aria-hidden="true"></i>
                        </a>
                        توسط
                        <a href="https://www.instagram.com/vartastudio" target="_blank">استودیو
                            ورتا ©
                            <script>
                                document.write(new Date().getFullYear())
                            </script>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
{{--<script src="{{asset('js/plugins/popper.min.js')}}" type="text/javascript"></script>--}}
{{--<script src="{{asset('js/plugins/bootstrap.min.js')}}"></script>--}}
{{--<script src="{{asset('js/plugins/perfect-scrollbar.min.js')}}"></script>--}}
{{--<script src="{{asset('js/plugins/choices.min.js')}}"></script>--}}
{{--<script src="{{asset('js/plugins/prism.min.js')}}"></script>--}}
{{--<script src="{{asset('js/plugins/rellax.min.js')}}"></script>--}}
{{--<script src="{{asset('js/plugins/tilt.min.js')}}"></script>--}}
{{--<script src="{{asset('js/plugins/parallax.min.js')}}"></script>--}}
{{--<script src="{{asset('js/plugins/soft-design-system.min.js')}}?v=1.0.4" type="text/javascript"></script>--}}


<!--<script src="./assets/js/plugins/highlight.min.js"></script>-->
<!--  Plugin for Parallax, full documentation here: https://github.com/dixonandmoe/rellax -->

<!--  Plugin for TiltJS, full documentation here: https://gijsroge.github.io/tilt.js/ -->

<!--  Plugin for Selectpicker - ChoicesJS, full documentation here: https://github.com/jshjohnson/Choices -->

<!--  Plugin for Parallax, full documentation here: https://github.com/wagerfield/parallax  -->

<!-- Control Center for Soft UI Kit: parallax effects, scripts for the example pages etc -->
{{--<script src="{{asset('js/plugins/jquery.min.js')}}" type="text/javascript"></script>--}}
<script src="{{asset('js/plugins/jquery.min.js')}}" type="text/javascript"></script>

<script>
    $(document).ready(function () {

    });
</script>

@if(session()->has('success'))


    <script>
        $(document).ready(function () {
            let s = '{!!session('success')!!}';
            @php(session()->forget('success')  )

            //            if (s !== '') {
            //                toastr.options = {
            //                    closeButton: false,
            //                    closeMethod: 'fadeOut',
            //                    timeOut: 3000,
            //                    extendedTimeOut: 4000,
            //                    progressBar: true,
            //                    closeEasing: 'swing',
            //                    positionClass: "toast-bottom-right",
            //                };
            //                toastr.success('', s);
            ////                window.location.reload(true);
            //            }
            if (s !== '') {
                swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: s,
                    showConfirmButton: false,
                    timer: 1500
                })
            }


        });


    </script>
    <script type="text/javascript">

    </script>
@endif
@yield('script')
</body>
</html>
