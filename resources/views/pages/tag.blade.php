@extends('layouts.app')

@section('content')
    <section class="mt-7    mx-md-5 mx-sm-3 text-right">
        <div class="      shadow-card bg-white p-md-5 p-sm-3 rounded-3">

            <div class="row">
                <div id="gallery" style="display: none"
                     class="gallery-width   bg-gradient-faded-light rounded-3 p-3  col-md-6 ">
                    @foreach(\App\Models\Image::where('for_id',$product->id)->get() as $img)

                        <img src="{{asset("storage/products/$img->id.jpg")}}" class="   "
                             alt="{{$img->name}}"
                             style="max-height: 25rem;object-fit:cover ;object-position: 0 0;">

                    @endforeach
                </div>
                <div class="col-md-6 p-3 flex-column d-flex  align-items-start">
                    <h5 class="text-gradient-purple ">{{$product->name}}</h5>
                    <div class="d-flex  flex-row-reverse   align-items-center">
                        <div class="mx-2  text-gradient-purple   {{ $product->discount_price!=null && $product->discount_price>0  ?'text-decoration-line-through':''}}">{{number_format($product->price). ' ت '}} </div>
                        <i class="fa fa-arrow-alt-circle-right text-primary {{ $product->discount_price==null || $product->discount_price==0  ?'d-none':''}}"
                           aria-hidden="true"></i>
                        <div class="mx-2  text-gradient-indigo  {{ $product->discount_price==null || $product->discount_price==0  ?'d-none':''}}">{{$product->discount_price.' ت '}}</div>
                    </div>
                    <div class="mx-1">{{$product->description}}</div>
                    <div class="col-12 mx-auto  mt-auto">
                        <div class="  justify-content-center px-1    text-center">
                            <small class="rounded py-2 btn-block   bg-gradient-success text-white     px-1   move-on-hover hoverable"
                            >
                                <i class="fas  fa-cart-plus"></i>
                                اضافه به سبد خرید

                            </small>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row col-12">

                <div class="col-md-6">
                    @foreach(explode("\n",$product->tags) as $tag)

                    @endforeach
                </div>


            </div>
        </div>
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


        });

    </script>

@endsection