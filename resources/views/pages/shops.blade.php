@extends('layouts.app')

@section('content')
    <section class="">
        <header class="header-2 mt-n7 ">
            <div class="page-header section-height-75 relative"
                 style="background-image: url({{asset('img/curved-images/curved.jpg')}})">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7 text-center mx-auto">
                            <h1 class="text-white pt-3 mt-5">فروشندگان بازارچه</h1>
                            <p class="lead text-white mt-3">
                                همین الان پیام بده و فروشگاه خودت رو ثبت کن!
                            </p>

                            <div class="input-group  text-white   align-items-baseline pb-4     mx-auto   max-width-500 "
                                 dir="ltr">

                                <a href="https://t.me/develowper" type="text" dir="rtl"
                                   class="move-on-hover text-white rounded-pill-left w-50 px-5 py-3 rounded-pill-left bg-gradient-info"
                                > <i class="fab fa-telegram " aria-hidden="true"></i>
                                    تلگرام
                                </a>
                                <a href="https://instagram.com/develowper" type="text" dir="rtl"
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
        <div class="row col-12">
            @foreach(\App\Models\Shop::where('active',true)->get() as $idx=>$shop)
                @php($c=\App\Models\Product::where('shop_id',$shop->id)->count())
                @if (!$c)
                    @continue
                @endif
                <div class="rounded-lg shadow-card  bg-gradient-light my-2 mx-auto col-10">
                    <div class="container-fluid  p-2 text-right my-3">
                        <h3 class="  w-100  text-dark my-2 d-inline-block">  {{$shop->name}} <span
                                    class="text-dark d-inline-block small">
                                ( {{$c}} محصول
                                )</span></h3>

                        <products-carousel id="{{'carouselVitrin-'.$shop->id}}"
                                           data-link="{{route('product.search')}}"
                                           root-link="{{route('/')}}"
                                           img-link="{{asset("storage/products/")}}"
                                           cart-link="{{route('cart.edit')}}"
                                           asset-link="{{asset("img/")}}"
                                           :params="{{json_encode([   'shop_id'=>$shop->id,'order_by'=>'created_at'])}}">

                        </products-carousel>

                    </div>
                </div>

            @endforeach
        </div>
    </section>
@endsection