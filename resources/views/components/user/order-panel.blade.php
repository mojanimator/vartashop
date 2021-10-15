@php($s1=\App\Models\Order::where('user_id',auth()->user()->id)->where('status',1)->count())
@php($s2=\App\Models\Order::where('user_id',auth()->user()->id)->where('status',2)->count())
@php($s3=\App\Models\Order::where('user_id',auth()->user()->id)->where('status',3)->count())
@php($s4=\App\Models\Order::where('user_id',auth()->user()->id)->where('status',4)->count())


@if($section=='header')
    <li class="nav-item  ">
        <a class="nav-link hoverable-dark  mx-1 {{str_contains( url()->current(),'/my-orders')?' active ':''}}"
           href="{{url('panel/my-orders')}}">
            <div class="icon icon-shape   shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">
                <svg width="12px" height="20px" viewBox="0 0 40 40" version="1.1"
                     xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <title>سفارشات</title>
                    <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none"
                       fill-rule="evenodd">
                        <g id="Rounded-Icons" transform="translate(-1720.000000, -592.000000)"
                           fill="#FFFFFF" fill-rule="nonzero">
                            <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)">
                                <g id="spaceship" transform="translate(4.000000, 301.000000)">
                                    <path class="color-background"
                                          d="M39.3,0.706666667 C38.9660984,0.370464027 38.5048767,0.192278529 38.0316667,0.216666667 C14.6516667,1.43666667 6.015,22.2633333 5.93166667,22.4733333 C5.68236407,23.0926189 5.82664679,23.8009159 6.29833333,24.2733333 L15.7266667,33.7016667 C16.2013871,34.1756798 16.9140329,34.3188658 17.535,34.065 C17.7433333,33.98 38.4583333,25.2466667 39.7816667,1.97666667 C39.8087196,1.50414529 39.6335979,1.04240574 39.3,0.706666667 Z M25.69,19.0233333 C24.7367525,19.9768687 23.3029475,20.2622391 22.0572426,19.7463614 C20.8115377,19.2304837 19.9992882,18.0149658 19.9992882,16.6666667 C19.9992882,15.3183676 20.8115377,14.1028496 22.0572426,13.5869719 C23.3029475,13.0710943 24.7367525,13.3564646 25.69,14.31 C26.9912731,15.6116662 26.9912731,17.7216672 25.69,19.0233333 L25.69,19.0233333 Z"
                                          id="Shape"></path>
                                    <path class="color-background"
                                          d="M1.855,31.4066667 C3.05106558,30.2024182 4.79973884,29.7296005 6.43969145,30.1670277 C8.07964407,30.6044549 9.36054508,31.8853559 9.7979723,33.5253085 C10.2353995,35.1652612 9.76258177,36.9139344 8.55833333,38.11 C6.70666667,39.9616667 0,40 0,40 C0,40 0,33.2566667 1.855,31.4066667 Z"
                                          id="Path"></path>
                                    <path class="color-background"
                                          d="M17.2616667,3.90166667 C12.4943643,3.07192755 7.62174065,4.61673894 4.20333333,8.04166667 C3.31200265,8.94126033 2.53706177,9.94913142 1.89666667,11.0416667 C1.5109569,11.6966059 1.61721591,12.5295394 2.155,13.0666667 L5.47,16.3833333 C8.55036617,11.4946947 12.5559074,7.25476565 17.2616667,3.90166667 L17.2616667,3.90166667 Z"
                                          id="color-2" opacity="0.598539807"></path>
                                    <path class="color-background"
                                          d="M36.0983333,22.7383333 C36.9280725,27.5056357 35.3832611,32.3782594 31.9583333,35.7966667 C31.0587397,36.6879974 30.0508686,37.4629382 28.9583333,38.1033333 C28.3033941,38.4890431 27.4704606,38.3827841 26.9333333,37.845 L23.6166667,34.53 C28.5053053,31.4496338 32.7452344,27.4440926 36.0983333,22.7383333 L36.0983333,22.7383333 Z"
                                          id="color-3" opacity="0.598539807"></path>

                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
            </div>
            <span class="nav-link-text me-1">سفارشات من</span>

        </a>
    </li>
    @if(str_contains( url()->current(),'/my-orders'))
        <li class="nav-item  my-0">
            <div class="navbar-nav p-0  ">

                <a class=" nav-link hoverable-info py-2 {{str_contains( url()->full(),'my-orders/cart')?' active  text-primary':''}} "
                   href="{{url('panel/my-orders/cart')}}">
                    <i class="fa fa-shopping-cart text-primary mx-1" aria-hidden="true"></i>
                    سبد خرید
                    <span class=" mx-3   p-2 badge rounded-circle bg-danger {{\App\Models\Cart::count()>0?'':'d-none'}}">
                                        {{\App\Models\Cart::count()}}</span>
                </a>


            </div>
        </li>
        <li class="nav-item  my-0">
            <div class="navbar-nav p-0  ">

                <a class=" nav-link hoverable-info py-2 {{str_contains( url()->full(),'my-orders/search?status=1')?' active  text-primary':''}} "
                   href="{{url('panel/my-orders/search?status=1')}}">
                    <i class="fa fa-hourglass-start text-warning mx-1" aria-hidden="true"></i>
                    در انتظار پرداخت

                    <span class=" mx-3   p-2 badge rounded-circle bg-warning {{$s1>0?'':'d-none'}}">
                                        {{$s1}}
                                </span>
                </a>


            </div>
        </li>
        <li class="nav-item  my-0">
            <div class="navbar-nav p-0  ">

                <a class=" nav-link hoverable-info py-2 {{str_contains( url()->full(),'my-orders/search?status=2')?' active  text-primary':''}} "
                   href="{{url('panel/my-orders/search?status=2')}}">
                    <i class="fa fa-hourglass-half text-info mx-1" aria-hidden="true"></i>
                    در حال پردازش

                    <span class=" mx-3   p-2 badge rounded-circle bg-info {{$s2>0?'':'d-none'}}">
                                        {{$s2}}
                                </span>
                </a>


            </div>
        </li>
        <li class="nav-item  my-0">
            <div class="navbar-nav p-0  ">

                <a class=" nav-link hoverable-info py-2 {{str_contains( url()->full(),'my-orders/search?status=3')?' active  text-primary':''}} "
                   href="{{url('panel/my-orders/search?status=3')}}">
                    <i class="fa fa-hourglass-end text-success mx-1" aria-hidden="true"></i>
                    اماده ارسال

                    <span class=" mx-3   p-2 badge rounded-circle bg-success {{$s3>0?'':'d-none'}}">
                                        {{$s3}}
                                </span>
                </a>


            </div>
        </li>
        <li class="nav-item  my-0">
            <div class="navbar-nav p-0  ">

                <a class=" nav-link hoverable-info py-2 {{str_contains( url()->full(),'my-orders/search?status=4')?' active  text-primary':''}} "
                   href="{{url('panel/my-orders/search?status=4')}}">
                    <i class="fa fa-truck text-dark mx-1" aria-hidden="true"></i>
                    ارسال شده

                    <span class=" mx-3   p-2 badge rounded-circle bg-dark {{$s4>0?'':'d-none'}}">
                                        {{$s4}}
                                </span>
                </a>

            </div>
        </li>

    @endif
@endif

@if($section=='content')
    <div class="row col-12">
        <div class="row">
            <div class="col-md-6  mx-md-auto p-1    ">
                <a href="{{url('panel/my-orders/cart')}}" class="   ">
                    <div class="card move-on-hover">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <h5 class="  mb-0 text-capitalize font-weight-bold">
                                            سبد
                                            خرید
                                        </h5>
                                        <h5 class=" text-sm text-black-50 font-weight-bolder mb-0">
                                            تعداد&nbsp

                                            <span class="text-primary text-sm font-weight-bolder"> {{\App\Models\Cart::count()}}</span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="  ">
                                        <i class="fa fa-2x fa-shopping-cart text-primary m-1"
                                           aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row">


            {{--status 1--}}
            <div class="col-md-6 p-1  ">
                <a href="{{url('panel/my-orders/search?status=1')}}" class="   ">
                    <div class="card move-on-hover">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <h5 class="  mb-0 text-capitalize font-weight-bold">
                                            در
                                            انتظار
                                            پرداخت
                                        </h5>
                                        <h5 class=" text-sm text-black-50 font-weight-bolder mb-0">
                                            تعداد&nbsp

                                            <span class="text-danger text-sm font-weight-bolder"> {{$s1}}</span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="  ">
                                        <i class="fa fa-2x fa-hourglass-start text-warning m-1"
                                           aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            {{--status 2--}}
            <div class="col-md-6 p-1  ">
                <a href="{{url('panel/my-orders/search?status=2')}}" class="  ">
                    <div class="card move-on-hover">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <h5 class="  mb-0 text-capitalize font-weight-bold">
                                            در حال
                                            پردازش
                                        </h5>
                                        <h5 class=" text-sm text-black-50 font-weight-bolder mb-0">
                                            تعداد&nbsp

                                            <span class="text-info text-sm font-weight-bolder"> {{$s2}}</span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="  ">
                                        <i class="fa fa-2x fa-hourglass-half text-info m-1"
                                           aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            {{--status 3--}}
            <div class="col-md-6 p-1  ">
                <a href="{{url('panel/my-orders/search?status=3')}}" class=" ">
                    <div class="card move-on-hover">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <h5 class="  mb-0 text-capitalize font-weight-bold">
                                            آماده
                                            ارسال
                                        </h5>
                                        <h5 class=" text-sm text-black-50 font-weight-bolder mb-0">
                                            تعداد&nbsp

                                            <span class="text-success text-sm font-weight-bolder"> {{$s3}}</span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="  ">
                                        <i class="fa fa-2x fa-hourglass-end text-success m-1"
                                           aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            {{--status 4--}}
            <div class="col-md-6 p-1  ">
                <a href="{{url('panel/my-orders/search?status=4')}}" class="  ">
                    <div class="card move-on-hover">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <h5 class="  mb-0 text-capitalize font-weight-bold">
                                            ارسال
                                            شده
                                        </h5>
                                        <h5 class=" text-sm text-black-50 font-weight-bolder mb-0">
                                            تعداد&nbsp

                                            <span class="text-dark text-sm font-weight-bolder"> {{$s4}}</span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="  ">
                                        <i class="fa fa-2x fa-hourglass-end text-dark m-1"
                                           aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endif