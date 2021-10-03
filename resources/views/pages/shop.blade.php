@extends('layouts.app')

@section('content')
    <section class="mt-7    mx-md-5 mx-sm-3 text-right">
        <div class="card row shadow-card p-3">
            <div class="position-relative rounded-lg height-200   col-12 overflow-hidden card-header p-5">

                <img src="{{asset('img/curved-images/curved11.jpg')}}" alt=""
                     class="position-absolute  w-100  top-0 left-0 filter-blur">
                {{--<div class="position-absolute  w-100 h-100  top-0 left-0 bg-light-transparent"></div>--}}
                <div class="d-flex flex-row position-absolute  w-100 h-100  top-0 left-0 p-3 text-white">
                    <img src="{{$shop->image}}" alt="" class="rounded-lg">
                    <div class="d-flex flex-column w-100 ">
                        <h3 class="mx-auto text-white w-100   p-2  rounded-left   text-center bg-gradient-dark my-0">{{$shop->name}}</h3>
                        <h5 class="text-light  text-left mr-auto mt-0 bg-dark p-1 rounded-bottom  ml-4">{{\App\Models\Product::where('shop_id',$shop->id)->count(). ' محصول '}}</h5>
                        {{--<h5 class="text-dark">{{$shop->location('render')}}</h5>--}}
                        <h6 class="text-light bg-dark rounded-left p-2 ml-auto">{{$shop->address}}
                            <br> {{' تلفن: '.str_replace('+98','',$shop->contact)}}
                        </h6>
                    </div>
                </div>
            </div>
            <section class=" position-relative  col-12 my-0 p-5  text-center rounded-lg overflow-hidden">
                <img src="{{asset('img/market.jpg')}}" alt=""
                     class="position-absolute left-0 right-0 w-100 top-0 bottom-0  filter-blur ">
                <div class="position-absolute w-100 z-inde-1 top-0   right-0 mt-n3">
                    <svg width="100%" viewBox="0 -2 1920 157" version="1.1" xmlns="http://www.w3.org/2000/svg"
                         xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>wave-down</title>
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g fill="#FFFFFF" fill-rule="nonzero">
                                <g id="wave-down">
                                    <path d="M0,60.8320331 C299.333333,115.127115 618.333333,111.165365 959,47.8320321 C1299.66667,-15.5013009 1620.66667,-15.2062179 1920,47.8320331 L1920,156.389409 L0,156.389409 L0,60.8320331 Z"
                                          id="Path-Copy-2"
                                          transform="translate(960.000000, 78.416017) rotate(180.000000) translate(-960.000000, -78.416017) "></path>
                                </g>
                            </g>
                        </g>
                    </svg>
                </div>

                <div class="container py-4 position-relative text-center">

                    <h5 class="mx-auto text-white pt-5  left-0 right-0 ">جست و جوی نام، برند، ویژگی
                        محصول</h5>

                    <form id="search-shop-form" action="{{route('products.view')}}" method="GET">
                        <div class="input-group   align-items-baseline py-5    mx-auto   max-width-500 "
                             dir="ltr">
                            <a type="submit" id="button-addon1" onclick="event.preventDefault();
 if(document.getElementById('search-input').value) document.getElementById('search-shop-form').submit();"
                               class="btn bg-gradient-primary   px-3 rounded-pill-left w-25 ">
                                <svg aria-hidden="true" class="svg-inline--fa fa-search fa-w-16" focusable="false"
                                     data-prefix="fa" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 512 512" data-fa-i2svg="">
                                    <path fill="currentColor"
                                          d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path>
                                </svg>
                                <!-- <i aria-hidden="true" class="fa fa-search"></i> Font Awesome fontawesome.com -->
                            </a>
                            <input id="search-input" type="text" placeholder="..." dir="rtl"
                                   aria-label="جست و جوی محصول"
                                   aria-describedby="button-addon1" name="search"
                                   class="form-control   px-5 py-2 rounded-pill-right" required>
                            {{--<input type="hidden" name="shop_id" value="{{$shop->id}}">--}}
                        </div>
                    </form>
                </div>
                <div class="position-absolute w-100 bottom-0 right-0 mn-n1">
                    <svg width="100%" viewBox="0 -1 1920 130" version="1.1" xmlns="http://www.w3.org/2000/svg"
                         xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>wave-up</title>
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g transform="translate(0.000000, 5.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                <g id="wave-up" transform="translate(0.000000, -5.000000)">
                                    <path d="M0,70 C298.666667,105.333333 618.666667,95 960,39 C1301.33333,-17 1621.33333,-11.3333333 1920,56 L1920,165 L0,165 L0,70 Z"
                                          fill="#ffffff"></path>
                                </g>
                            </g>
                        </g>
                    </svg>
                </div>
            </section>

            <section class=" position-relative  col-12 my-0 pt-2 pb-5 bg-gradient-faded-light rounded-lg">
                <div class="position-absolute w-100 z-inde-1 top-0   right-0 mt-n3">
                    <svg width="100%" viewBox="0 -2 1920 157" version="1.1" xmlns="http://www.w3.org/2000/svg"
                         xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>wave-down</title>
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g fill="#FFFFFF" fill-rule="nonzero">
                                <g id="wave-down">
                                    <path d="M0,60.8320331 C299.333333,115.127115 618.333333,111.165365 959,47.8320321 C1299.66667,-15.5013009 1620.66667,-15.2062179 1920,47.8320331 L1920,156.389409 L0,156.389409 L0,60.8320331 Z"
                                          id="Path-Copy-2"
                                          transform="translate(960.000000, 78.416017) rotate(180.000000) translate(-960.000000, -78.416017) "></path>
                                </g>
                            </g>
                        </g>
                    </svg>
                </div>
                <div class="container py-5">
                    <h3 class="mt-3">دسته بندی محصولات</h3>
                    <div id="groups-carousel" class="owl-carousel owl-theme text-center  ">
                        @foreach(\App\Models\Group::whereIn('id',\App\Models\Product::where('shop_id',$shop->id)->distinct('group_id')->pluck('group_id'))->select('id','name')->get() as $g)
                            <a href="{{ route('products.view',['group_ids'=>[$g->id],'shop_id'=>$shop->id]) }}"
                               class="">
                                <div class="position-relative rounded-3 move-on-hover  "
                                     style="width: 15rem;height: 15rem">
                                    @php($img=\App\Models\Image::whereIn('for_id',\App\Models\Product::where('shop_id',$shop->id)/*->where('group_id',$g->id)*/->pluck('id'))->inRandomOrder()->first())
                                    @php($img=$img?asset('storage/products/'.$img->id.'.jpg'):asset('img/noimage.png'))
                                    <img src="{{$img}}" alt=""
                                         class="position-absolute  w-100 h-100  top-0 bottom-0    bg-cover rounded-lg  ">
                                    <h4 class="position-absolute my-auto text-white  p-2  w-100 top-5 text-center bg-dark-transparent  ">{{$g->name}}</h4>
                                </div>
                            </a>
                        @endforeach

                    </div>
                </div>
                <div class="position-absolute w-100 bottom-0 right-0 mn-n1">
                    <svg width="100%" viewBox="0 -1 1920 140" version="1.1" xmlns="http://www.w3.org/2000/svg"
                         xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>wave-up</title>
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g transform="translate(0.000000, 5.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                <g id="wave-up" transform="translate(0.000000, -5.000000)">
                                    <path d="M0,70 C298.666667,105.333333 618.666667,95 960,39 C1301.33333,-17 1621.33333,-11.3333333 1920,56 L1920,165 L0,165 L0,70 Z"
                                          fill="#ffffff"></path>
                                </g>
                            </g>
                        </g>
                    </svg>
                </div>
            </section>

            <section class="pt-5 pb-2 position-relative bg-gradient-danger  ">
                <div class="position-absolute w-100 z-inde-1 top-0   right-0 mt-n3">
                    <svg width="100%" viewBox="0 -2 1920 157" version="1.1" xmlns="http://www.w3.org/2000/svg"
                         xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>wave-down</title>
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g fill="#FFFFFF" fill-rule="nonzero">
                                <g id="wave-down">
                                    <path d="M0,60.8320331 C299.333333,115.127115 618.333333,111.165365 959,47.8320321 C1299.66667,-15.5013009 1620.66667,-15.2062179 1920,47.8320331 L1920,156.389409 L0,156.389409 L0,60.8320331 Z"
                                          id="Path-Copy-2"
                                          transform="translate(960.000000, 78.416017) rotate(180.000000) translate(-960.000000, -78.416017) "></path>
                                </g>
                            </g>
                        </g>
                    </svg>
                </div>
                <div class="container-fluid shadow-card p-5">
                    <h3 class="  w-100  text-white  "><i
                                class="fas  fa-lg fa-fire  text-yellow  "></i> تخفیفات ویژه </h3>
                    <products-carousel id="carouselVitrin" data-link="{{route('product.search')}}"
                                       root-link="{{route('/')}}"
                                       img-link="{{asset("storage/products/")}}"
                                       cart-link="{{route('cart.edit')}}"
                                       asset-link="{{asset("img/")}}"
                                       :params="{{json_encode([ 'order_by_raw'=>'1-((price-discount_price)/(price)) desc' ,'shop_id'=>$shop->id])}}">

                    </products-carousel>

                </div>
                <div class="position-absolute w-100 bottom-0 right-0 mn-n1">
                    <svg width="100%" viewBox="0 0 1920 120" version="1.1" xmlns="http://www.w3.org/2000/svg"
                         xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>wave-up</title>
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g transform="translate(0.000000, 5.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                <g id="wave-up" transform="translate(0.000000, -5.000000)">
                                    <path d="M0,70 C298.666667,105.333333 618.666667,95 960,39 C1301.33333,-17 1621.33333,-11.3333333 1920,56 L1920,165 L0,165 L0,70 Z"
                                          fill="#ffffff"></path>
                                </g>
                            </g>
                        </g>
                    </svg>
                </div>
            </section>

            <section class="pt-5 pb-2  position-relative bg-gradient-info ">
                <div class="position-absolute w-100 z-inde-1 top-0   right-0 mt-n3">
                    <svg width="100%" viewBox="0 -2 1920 157" version="1.1" xmlns="http://www.w3.org/2000/svg"
                         xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>wave-down</title>
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g fill="#FFFFFF" fill-rule="nonzero">
                                <g id="wave-down">
                                    <path d="M0,60.8320331 C299.333333,115.127115 618.333333,111.165365 959,47.8320321 C1299.66667,-15.5013009 1620.66667,-15.2062179 1920,47.8320331 L1920,156.389409 L0,156.389409 L0,60.8320331 Z"
                                          id="Path-Copy-2"
                                          transform="translate(960.000000, 78.416017) rotate(180.000000) translate(-960.000000, -78.416017) "></path>
                                </g>
                            </g>
                        </g>
                    </svg>
                </div>
                <div class="container-fluid shadow-card p-5">
                    <h3 class=" w-100  text-white top-3 ">
                        <i class="fas  fa-lg fa-fire  text-yellow  "></i> جدیدترین محصولات </h3>
                    <products-carousel id="carouselVitrin2" data-link="{{route('product.search')}}"
                                       root-link="{{route('/')}}"
                                       img-link="{{asset("storage/products/")}}"
                                       cart-link="{{route('cart.edit')}}"
                                       asset-link="{{asset("img/")}}"
                                       :params="{{json_encode([ 'order_by'=>'created_at' ,'shop_id'=>$shop->id])}}">

                    </products-carousel>

                </div>
                <div class="position-absolute w-100 bottom-0 right-0 mn-n1">
                    <svg width="100%" viewBox="0 -1 1920 130" version="1.1" xmlns="http://www.w3.org/2000/svg"
                         xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>wave-up</title>
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g transform="translate(0.000000, 5.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                <g id="wave-up" transform="translate(0.000000, -5.000000)">
                                    <path d="M0,70 C298.666667,105.333333 618.666667,95 960,39 C1301.33333,-17 1621.33333,-11.3333333 1920,56 L1920,165 L0,165 L0,70 Z"
                                          fill="#ffffff"></path>
                                </g>
                            </g>
                        </g>
                    </svg>
                </div>
            </section>
        </div>
    </section>

@endsection


@section('script')


    <script type="text/javascript">


        $(document).ready(function () {

            $("#groups-carousel").owlCarousel({
                nav: true,
                lazyLoad: true,
                margin: 8,

                stagePadding: 24,
                infinite: true,
                pagination: true,
                startPosition: 0,
                dots: true,
                rewind: false,
                autoWidth: true,
                autoHeight: true,
//                        itemElement: 'div',
                navContainer: null,// '#' + this.id + '-nav',//String/Class/ID/Boolean

                navText: [

                    "<span class='   carousel-control-next-icon  rounded-right bg-dark p-2 my-1 hoverable  '></span>",
                    "<span class='   carousel-control-prev-icon rounded-left bg-dark p-2 my-1 hoverable'></span>",

                ],
//                        responsiveClass: true,
//                items: 4,
                rtl: true,
                onChanged: function (event) {

                },
                responsive: {
                    0: {
                        items: 2,
                        nav: true
                    },
                    600: {
                        items: 3,
                        nav: true
                    },
                    1000: {
                        items: 4,
                        nav: true,
                        loop: false
                    }
                }
            });

        });

    </script>

@endsection