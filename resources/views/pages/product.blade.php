@extends('layouts.app')

@section('content')
    <section class="  mx-md-5 mx-sm-3 text-right">
        <div class="      shadow-lg bg-white p-md-5 p-sm-3 rounded-3">

            <div class="row">
                <div id="gallery" style="display: none"
                     class="gallery-width   bg-gradient-faded-light rounded-lg p-3  col-md-6 mb-2">
                    @foreach(\App\Models\Image::where('for_id',$product->id)->get() as $img)

                        <img onError="this.onerror=null;this.src='/img/vartashop_logo.png';"
                             src="{{asset("storage/products/$img->id.jpg")}}" class="   "
                             alt="{{$img->name}}"
                             style="max-height: 25rem;object-fit:cover ;object-position: 0 0;">

                    @endforeach
                </div>
                <div class="col-md-6 px-2 py-1 flex-column d-flex ">
                    <h5 class="text-gradient-purple  text-center">{{$product->name}}</h5>
                    <h6 class="text-center   "><span class="text-info">کد محصول: </span>
                        <span
                            class="text-gradient-dark">{{$product->id}}
                            </span>
                    </h6>

                    <hr class="horizontal dark my-1 ">
                    <div class="  row">

                        {{--<span>{{$product->count}}</span>--}}
                        <h6 class="text-center   "><span class="text-info">موجودی: </span>
                            <span
                                class="text-gradient-dark">تماس بگیرید
                            </span>
                        </h6>

                        <hr class="horizontal dark my-1  ">
                    </div>
                    <h6 class="d-flex  flex-row  mx-auto align-items-center">
                        {{--<i class="fa fa-money-bill-alt text-primary" aria-hidden="true"></i>--}}
                        <div
                            class="mx-2  text-gradient-purple   {{ $product->discount_price!=null && $product->discount_price>0  ?'text-decoration-line-through':''}}">{{number_format($product->price). ' ت '}} </div>
                        <i class="fa fa-arrow-alt-circle-left text-primary {{ $product->discount_price==null || $product->discount_price==0  ?'d-none':''}}"
                           aria-hidden="true"></i>
                        <div
                            class="mx-2  text-gradient-indigo  {{ $product->discount_price==null || $product->discount_price==0  ?'d-none':''}}">{{$product->discount_price.' ت '}}</div>
                    </h6>
                    <hr class="horizontal dark mt-5">
                    <div class="  p-3 rounded-lg bg-gradient-faded-white">{!!  nl2br( $product->description) !!}
                    </div>

                </div>

            </div>
            <div class="row  ">
                <div class="col-md-6   ">
                    <div class="row align-items-baseline">
                        <div class="col-3">
                            <a href="{{route('shop',['id'=>$product->shop->id,'name'=>$product->shop->name])}}">
                                <img
                                    src="{{$product->shop->image}}"
                                    alt=""
                                    class="rounded-circle " style="max-width: 4rem">
                            </a>

                        </div>
                        <h5 class="col-9   align-items-center justify-content-between">
                            <a href="{{route('shop',['id'=>$product->shop->id,'name'=>$product->shop->name])}}">
                                <div class="hoverable-text-blue my-1">{{$product->shop->name}}</div>
                            </a>

                        </h5>
                        <div class="col-12 btn-group text-white  my-1" dir="ltr">

                            <a href="{{'https://wa.me/'.str_replace('+','',$product->shop->contact).'?text='.request()->url()}}"
                               target="_blank"
                               class="btn btn-dark hoverable-light    ">
                                <i class="fab fa-2x  fa-whatsapp"
                                   aria-hidden="true">

                                </i>
                            </a>
                            @php($owner=\App\Models\User::where('id',$product->shop->user_id)->first())
                            @if($owner)
                                <a href="{{'https://t.me/'.str_replace('@','',$owner->telegram_username) /*.'&msg='.request()->url()*/}}"
                                   class="btn btn-dark hoverable-light" target="_blank">
                                    <i class="fab fa-2x  fa-telegram"
                                       aria-hidden="true">

                                    </i>
                                </a>
                            @endif

                            <div class=" btn btn-outline-dark text-lg copy "
                            >
                                {{str_replace('+98','0',$product->shop->contact)}}
                            </div>
                        </div>
                    </div>
                </div>
                <form id="cart-form" name="cart-add" action="{{route('cart.edit' )}}" method="post"
                      class="col-md-6 mx-auto mt-auto mb-3 justify-content-center px-1 ">

                    <input type="hidden" name="cmnd" value="plus"/>
                    <input type="hidden" name="id" value="{{$product->id}}"/>
                    <div class="    text-center">
                        <small onclick="  document.getElementById('cart-form').submit();"
                               class="rounded py-3 btn-block btn-lg  bg-gradient-success text-white     px-1   move-on-hover hoverable"
                        >
                            <i class="fas  fa-cart-plus"></i>
                            اضافه به سبد خرید

                        </small>

                    </div>

                    @csrf
                </form>
            </div>
            <div class="  col-12   mt-2   ">


                @foreach(explode("#",$product->tags) as $tag)
                    @continue($tag==null)

                    <a href="{{ route('products.view',['search'=> str_replace('_',' ',$tag)]) }}"
                       class=" mb-1 ms-1 rounded text-white bg-gradient-faded-dark hoverable-dark px-2 d-inline-block">
                        <small class="  ">
                            {{$tag}}
                        </small>
                    </a>

                @endforeach

            </div>
        </div>
    </section>

    <section class="my-3 mx-5 bg-gradient-primary p-3 rounded-3 position-relative  ">
        <div class="d-md-inline-block d-sm-none d-none    align-self-center position-absolute right-0 top-50   ">
            <p class="vertical  text-white text-lg    ">محصولات مشابه</p>
        </div>
        <div class="d-md-none    text-right  ">
            <p class="  text-white text-lg   ">محصولات مشابه</p>
        </div>
        {{--<div id="carouselVitrin" class="carousel slide col-md-9" data-bs-ride="carousel" data-interval="false">--}}
        <products-carousel id="carouselVitrin" data-link="{{route('product.search')}}"
                           root-link="{{route('/')}}" class="mr-5 pr-2"
                           img-link="{{asset("storage/products/")}}"
                           asset-link="{{asset("img/")}}"
                           cart-link="{{route('cart.edit')}}"
                           :params="{{json_encode(['name'=>array_filter(explode(' ',$product->name),function($el){
                           return $el!='' && $el!=' ' && $el !=null; }),'tags'=>array_filter(explode("\n", str_replace('#','',$product->tags)),function($el){
                           return $el!='' && $el!=' ' && $el !=null; }),'order_by'=>'created_at','id_not'=>$product->id])}}">

        </products-carousel>


    </section>

@endsection


@section('script')

    <script src="{{asset('js/plugins/unitegallery.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/plugins/ug-theme-tiles.js')}}" type="text/javascript"></script>
    <script type="text/javascript">


        $(document).ready(function () {

            let g = $("#gallery").unitegallery({
                gallery_theme: "tiles",
                tiles_type: "nested",
                tiles_enable_transition: false,
//                gallery_min_width: '500',
            });
            $(document).on('click', '.copy', (e) => {
                let str = e.target.innerText;
                let $temp = $("<input>");
                $("body").append($temp);
                $temp.val(str).select();
                document.execCommand("copy");
                $temp.remove();

                toastr.options = {
                    closeButton: false,
                    closeMethod: 'fadeOut',
                    timeOut: 3000,
                    extendedTimeOut: 4000,
                    progressBar: true,
                    closeEasing: 'swing',
                    positionClass: "toast-bottom-right",
                };

                toastr.success('در حافظه موقت کپی شد!', '');
            });

        });


    </script>

@endsection
