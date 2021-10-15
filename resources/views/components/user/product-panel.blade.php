@php($s1=\App\Models\Order::where('user_id',auth()->user()->id)->where('status',1)->count())

@if($section=='header')
    <li class="nav-item  ">
        <a class="nav-link hoverable-dark  mx-1 {{str_contains( url()->current(),'/my-products')?' active  text-primary ':''}}"
           href="{{url('panel/my-products')}}">
            <div class="icon icon-shape   shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="400" height="400" viewBox="0, 0, 24,24"
                     fill-rule="evenodd" clip-rule="evenodd">
                    <path xmlns="http://www.w3.org/2000/svg" class="color-background"
                          d="M11.5 23l-8.5-4.535v-3.953l5.4 3.122 3.1-3.406v8.772zm1-.001v-8.806l3.162 3.343 5.338-2.958v3.887l-8.5 4.534zm-10.339-10.125l-2.161-1.244 3-3.302-3-2.823 8.718-4.505 3.215 2.385 3.325-2.385 8.742 4.561-2.995 2.771 2.995 3.443-2.242 1.241v-.001l-5.903 3.27-3.348-3.541 7.416-3.962-7.922-4.372-7.923 4.372 7.422 3.937v.024l-3.297 3.622-5.203-3.008-.16-.092-.679-.393v.002z"/>
                </svg>
            </div>
            <span class="nav-link-text me-1"> محصولات من</span>

        </a>
    </li>

    @foreach (\App\Models\Shop::orWhere('user_id', auth()->user()->id)->orWhereIn('id', \App\Models\Rule::where('user_id', auth()->user()->id)->pluck('shop_id'))->get() as $shop)

        <li class="nav-item  my-0">
            <div class="navbar-nav p-0  ">

                <a class=" nav-link hoverable-info py-2 {{str_contains( url()->full(),'my-orders/search?status=1')?' active ':''}} "
                   href="{{url('panel/my-orders/search?status=1')}}">
                    <i class="fa fa-hourglass-start text-warning mx-1" aria-hidden="true"></i>
                    {{$shop->name}}

                    <span class=" mx-3   p-2 badge rounded-circle bg-warning {{$s1>0?'':'d-none'}}">
                                        {{$s1}}
                                </span>
                </a>


            </div>
        </li>

    @endforeach


@endif

@if($section=='content')
    <div class="row col-12">
        <div class="row">
            <div class="col-md-6  mx-md-auto p-1    ">
                <a href="{{url('panel/my-orders/cart')}}" class="   ">
                    <div class="card">
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
                    <div class="card">
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
                    <div class="card">
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
                    <div class="card">
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
                    <div class="card">
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