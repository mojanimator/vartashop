@extends('layouts.app')
{{--@inject('product', '\App\Models\Product')--}}
{{--@inject('image', '\App\Models\Image')--}}
@section('content')
    <!--<a href="https://vartashop.ir/charge" target="blank" class="position-fixed left-0 bottom-0 z-index-3">-->
    <!--    <img src="http://www.chargereseller.com/img/banner/120-240/banner-11.gif"/>-->
    <!--</a>-->


    <header class="header-2 mt-n7">
        <div class="page-header section-height-75 relative"
             style="background-image: url({{asset('img/curved-images/curved.jpg')}})">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 text-center mx-auto">
                        <h1 class="text-white pt-3 mt-n5">بازارچه آنلاین ورتا</h1>
                        <p class="lead text-white mt-3">حامی کسب و کارهای نوپا و خانگی <br/>
                            محصولات و خدمات خود را رایگان ثبت کنید
                        </p>
                        <div class="input-group  text-white   align-items-baseline pb-4     mx-auto   max-width-500 "
                             dir="ltr">

                            <a href="https://t.me/vartashop" type="text" dir="rtl"
                               class="move-on-hover  text-white rounded-pill-left w-50 px-5 py-3 rounded-pill-left bg-gradient-info"
                            > <i class="fab fa-telegram" aria-hidden="true"></i>
                                تلگرام
                            </a>
                            <a href="https://instagram.com/vartashop_ir" type="text" dir="rtl"
                               class="move-on-hover text-white w-50 px-5 py-3 rounded-pill-right bg-gradient-primary"
                            > <i class="fab fa-instagram" aria-hidden="true"></i>
                                اینستاگرام
                            </a>
                            {{--<input type="hidden" name="shop_id" value="{{$shop->id}}">--}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="position-absolute w-100 z-index-1 bottom-0">
                <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                     viewBox="0 24 150 40" preserveAspectRatio="none" shape-rendering="auto">
                    <defs>
                        <path id="gentle-wave"
                              d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"/>
                    </defs>
                    <g class="moving-waves">
                        <use xlink:href="#gentle-wave" x="48" y="-1" fill="rgba(255,255,255,0.40"/>
                        <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.35)"/>
                        <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.25)"/>
                        <use xlink:href="#gentle-wave" x="48" y="8" fill="rgba(255,255,255,0.20)"/>
                        <use xlink:href="#gentle-wave" x="48" y="13" fill="rgba(255,255,255,0.15)"/>
                        <use xlink:href="#gentle-wave" x="48" y="16" fill="rgba(255,255,255,0.95"/>
                    </g>
                </svg>
            </div>
        </div>


    </header>

    <section class="pt-3 pb-4 " id="count-stats" style="height: 0">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 z-index-2 border-radius-xl mt-n9 mx-auto py-md-3 blur shadow-blur">
                    <div class="row">
                        <div class="col-4 position-relative">
                            <div class="p-3 text-center">
                                <h1 class="text-gradient text-primary"><span id="state1"
                                                                             countTo="{{\App\Models\Shop::count()}}">0</span>+
                                </h1>
                                <h5 class="mt-3">فروشندگان</h5>
                                <p class="text-sm">فروشندگان خانگی و تایید شده توسط شماره همراه</p>
                            </div>
                            <hr class="vertical dark">
                        </div>
                        <div class="col-4 position-relative">
                            <div class="p-3 text-center">
                                <h1 class="text-gradient text-primary"><span id="state2"
                                                                             countTo="{{\App\Models\Product::count()}}">0</span>+
                                </h1>
                                <h5 class="mt-3">محصولات</h5>
                                <p class="text-sm">قیمت های ارزان تر از بازار <br> صرفه جویی در وقت و هزینه شما</p>
                            </div>
                            <hr class="vertical dark">
                        </div>
                        <div class="col-4">
                            <div class="p-3 text-center">
                                <h1 class="text-gradient text-primary" id="state3" countTo="4">0</h1>
                                <h5 class="mt-3">سفارش ها</h5>
                                <p class="text-sm">ثبت سفارش ۲۴ ساعته </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    @php ($products=\App\Models\Product::on(env('DB_CONNECTION'))->inRandomOrder()->take(5)->get() )

    <section>
        <div id="carouselProducts" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach($products as  $idx=>$p)
                    <button type="button" data-bs-target="#carouselProducts" data-bs-slide-to="{{$idx}}"
                            class="{{$idx==0?  'active':''}}"
                            aria-current="true" aria-label="{{$p->name}}"></button>

                @endforeach
            </div>
            <div class="carousel-inner ">

                @foreach($products as  $idx=>$p)
                    @php($img=  \App\Models\Image::on(env('DB_CONNECTION'))->where('type','p')->where('for_id',$p->id)->inRandomOrder()->first())
                    @php($img= $img? $img->id.'.jpg':'')
                    <div class="z-index-1 carousel-item {{$idx==0?  'active':''}}">
                        <img src="{{asset("storage/products/$img")}}" class=" d-block w-75 mx-auto " alt="{{$p->name}}"
                             style="max-height: 25rem;object-fit:cover ;object-position: 0 0;">
                        <div class="carousel-caption   d-md-block  ">
                            <h5 class="text-white bg-gradient-info rounded-pill  d-inline px-3 m-2">{{$p->name}}</h5>
                            <div class="  left-0 right-0 bg-gradient-faded-dark w-100    rounded-lg p-4">
                                <p class="small">{{$p->description}}</p>
                            </div>
                            <a type="button" class="btn bg-gradient-primary w-auto me-1 mb-0 btn-lg"
                               href="{{route('product.view',[$p->name,$p->id])}}">مشاهده جزییات
                            </a>
                        </div>


                    </div>
                @endforeach

            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselProducts"
                    data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselProducts"
                    data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    {{--products section--}}



    {{--@php ($products=\App\Models\Product::on(env('DB_CONNECTION'))->take(4)->get() )--}}

    <section class="my-3 mx-5 bg-gradient-primary p-3 rounded-lg position-relative  ">
        {{--<div class="d-md-inline-block d-sm-none d-none    align-self-center position-absolute right-0 top-50   ">--}}
        {{--<p class="vertical  text-white text-lg    ">جدید ترین ها</p>--}}
        {{--</div>--}}
        <div class="    text-right  ">
            <h2 class="  text-white   my-3  ">جدید ترین ها</h2>
        </div>
        {{--<div id="carouselVitrin" class="carousel slide col-md-9" data-bs-ride="carousel" data-interval="false">--}}
        <products-carousel id="carouselVitrin" data-link="{{route('product.search')}}"
                           root-link="{{route('/')}}" class=" "
                           img-link="{{asset("storage/products/")}}"
                           cart-link="{{route('cart.edit')}}"
                           asset-link="{{asset("img/")}}" :params="{{json_encode([ 'order_by'=>'created_at' ])}}">

        </products-carousel>


    </section>

    <section class="py-3 mx-5 my-4 bg-gradient-faded-light p-3 rounded-lg position-relative">
        <div class="container">
            <div class="row justify-content-start text-right ">
                <div class="col-lg-6 ">
                    <h3 class="mb-2 text-dark">محصولات</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-sm-6">

                    @php($img=asset("storage/products/". \App\Models\Image::on(env('DB_CONNECTION'))->where('type','p')->whereIn('for_id',\App\Models\Product::whereIn('shop_id',\App\Models\Shop::where('group_id',40)->pluck('id'))->pluck('id'))->inRandomOrder()->firstOrNew()->id.'.jpg'))
                    <div class="card card-plain card-blog ">
                        <a href="{{route('products.view',['group_ids'=>serialize(\App\Models\Group::on(env('DB_CONNECTION'))->where('parent',40)->pluck('id'))])}}"
                           class="height-300 ">
                            <img
                                class=" border-radius-lg  move-on-hover shadow w-100 h-100  cover cursor-pointer"
                                src="{{$img}}">
                        </a>

                        <div class="m-card-body flex-column text-right p-2">

                            <h6 class="text-dark font-weight-bold ">آرایشی بهداشتی</h6>

                            <p>
                                زیباترین خودت باش
                            </p>
                            <a href="{{route('products.view',['group_ids'=>serialize(\App\Models\Group::on(env('DB_CONNECTION'))->where('parent',40)->pluck('id'))])}}"
                               class="text-primary icon-move-right">بیشتر
                                <i class="fas fa-arrow-left text-sm"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-sm-6">
                    @php($img=asset("storage/products/". \App\Models\Image::on(env('DB_CONNECTION'))->where('type','p')->whereIn('for_id',\App\Models\Product::whereIn('shop_id',\App\Models\Shop::where('group_id',31)->pluck('id'))->pluck('id'))->inRandomOrder()->firstOrNew()->id.'.jpg'))
                    <div class="card card-plain card-blog ">
                        <a href="{{route('products.view',['group_ids'=>serialize(\App\Models\Group::on(env('DB_CONNECTION'))->where('parent',31)->pluck('id'))])}}"
                           class="height-300 ">
                            <img
                                class=" border-radius-lg  move-on-hover shadow w-100 h-100  cover cursor-pointer"
                                src="{{$img}}">
                        </a>

                        <div class="m-card-body flex-column text-right p-2">

                            <h6 class="text-dark font-weight-bold ">مد و پوشاک</h6>

                            <p>
                                زیباترین ها رو بپوش
                            </p>
                            <a href="{{route('products.view',['group_ids'=>serialize(\App\Models\Group::on(env('DB_CONNECTION'))->where('parent',31)->pluck('id'))])}}"
                               class="text-primary icon-move-right">بیشتر
                                <i class="fas fa-arrow-left text-sm"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12 col-12 text-start  move-on-hover">
                    <div class="card card-blog card-background">
                        <div class="full-background"
                             style="background-image: url({{asset('img/market.jpg')}}); object-fit: cover "></div>
                        <div class="card-body ">
                            <div class="content-left text-end my-auto py-4">

                                <h2 class="card-title text-white">دسته بندی ها</h2>
                                <p class="card-description text-white"> محصولات بازارچه رو در دسته بندی های مختلف
                                    ببین</p>
                                <a href="{{route('products.view')}}"
                                   class=" text-white icon-move-right bg-primary rounded-pill px-2">بیشتر
                                    <i class="fas fa-arrow-left text-sm"></i>
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-3 mx-5 my-4  bg-gradient-faded-light p-3 rounded-lg position-relative">
        @php ($shops=\App\Models\Shop::on(env('DB_CONNECTION'))->inRandomOrder()->with('group')->take(6)->get() )
        <div class="container">
            <div class="d-flex flex-row justify-content-between align-content-center   ">

                <h3 class="mb-2 text-dark d-flex">فروشندگان</h3>
                <a href="{{route('shops.view')}}" class="text-primary icon-move-right d-flex align-self-center">همه
                    فروشندگان
                    <i class="fas fa-arrow-left text-sm align-self-center"></i>
                </a>
            </div>
            <div class="row">
                @foreach($shops as $idx=>$shop)

                    <div class="col-lg-4 col-md-6 col-sm-12 mb-1 {{$idx==3 ?'m-auto':''}}">
                        <div class="card card-plain card-blog d-flex flex-column  justify-content-between">


                            <a href="{{route('shop',['name'=>$shop->name,'id'=>$shop->id])}}"
                               class=" ">
                                <img
                                    class=" border-radius-lg  move-on-hover shadow  w-100 cursor-pointer  mx-auto"

                                    src="{{$shop->image}}">
                            </a>

                            <div
                                class="m-card-body d-flex flex-column text-right p-2 justify-content-between align-items-baseline">

                                <div class="text-dark font-weight-bold ">{{$shop->name}}</div>

                                <div>
                                    @php($dsc=explode("\n",$shop->description))
                                    {{$dsc[0]}} <br>
                                    {{count($dsc)> 1 ?$dsc[1]:''}}
                                </div>
                                <div><a href="{{route('shop',['name'=>$shop->name,'id'=>$shop->id])}}"
                                        class="text-primary icon-move-right">مشاهده
                                        <i class="fas fa-arrow-left text-sm"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>

@endsection

@section('script')

    {{--<script src="{{asset('js/plugins/glide.min.js')}}">--}}



    {{--</script>--}}
    {{--<script>--}}
    {{--new Glide('.glide', {--}}

    {{--type: 'carousel',--}}
    {{--focusAt: 'center',--}}
    {{--startAt: 0,--}}
    {{--perView: 3,--}}
    {{--autoplay: 2000--}}
    {{--}).mount();--}}
    {{--</script>--}}


    <script src="{{asset('js/plugins/countup.min.js')}}"></script>


    <script type="text/javascript">


        window.onload = function () {
            let pPage = 2;


            if (document.getElementById('state1')) {
                const countUp = new CountUp('state1', document.getElementById("state1").getAttribute("countTo"));
                if (!countUp.error) {
                    countUp.start();
                } else {
                    console.error(countUp.error);
                }
            }
            if (document.getElementById('state2')) {
                const countUp1 = new CountUp('state2', document.getElementById("state2").getAttribute("countTo"));
                if (!countUp1.error) {
                    countUp1.start();
                } else {
                    console.error(countUp1.error);
                }
            }
            if (document.getElementById('state3')) {
                const countUp2 = new CountUp('state3', document.getElementById("state3").getAttribute("countTo"));
                if (!countUp2.error) {
                    countUp2.start();
                } else {
                    console.error(countUp2.error);
                }

            }
        }


    </script>

@endsection
