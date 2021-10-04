<section class="   mx-md-5 mx-sm-3  " onload="loaded()">
    <div class="shadow-card  row col-md-10  mx-auto   blur rounded">
        <h4 class="text-center text-gradient-info">سبد خرید </h4>
        @if (count(\App\Models\Cart::get())==0)
            <hr class="horizontal dark my-2">
            <h4 class="text-center text-dark mt-3">سبد خرید شما خالی است </h4>
            <a href="{{route('/')}}"
               class="  ">
                <h6
                        class="hoverable-text-info text-primary text-center"> بازگشت به بازارچه
                </h6>
            </a>
        @else

            @php($totalPrice=0)
            @foreach(\App\Models\Cart::get() as $idx=>$shop)
                @php($s=\App\Models\Shop::on(env('DB_CONNECTION'))->find( $idx) )
                <div></div>
                <h5 class="col-12 mt-4  text-right  ">
                    <a href="{{route('shop',['id'=>$s->id,'name'=>$s->name])}}">
                        <div class="hoverable-text-blue my-1">{{$s->name}}</div>
                    </a>

                </h5>
                <div class="border-blue border border-1 border-primary rounded-lg">
                    @foreach($shop['prods'] as $id=>$cart)
                        <hr class="horizontal dark my-2">
                        @php($product=\App\Models\Product::on(env('DB_CONNECTION'))->where('id',$id)->with('shop')->first())

                        @php($totalPrice+=($product->discount_price>0  ?$product->discount_price:$product->price)*$cart['qty'])
                        <div class=" p-2  ">
                            <div class="d-flex flex-row bg-gradient-white rounded justify-content-start align-items-center">

                                <img src="{{$product->image()}}" alt=""
                                     class="max-width-100 max-height-100 rounded-lg  ">
                                <div class="px-4 d-flex flex-column align-items-start">
                                    <a href="{{route('product.view',[$product->name,$product->id])}}" target="_blank"
                                       class="  ">
                                        <h6
                                                class="hoverable-text-info text-primary">{{$product->name}}
                                        </h6>
                                    </a>
                                    {{--@if($product->shop)--}}
                                    {{--<a href="{{route('shop',['id'=>$product->shop->id,'name'=>$product->shop->name])}}"--}}
                                    {{--target="_blank"--}}
                                    {{--class="  ">--}}
                                    {{--<h6--}}
                                    {{--class="hoverable-text-info  ">--}}
                                    {{--{{  $product->shop->name }}--}}
                                    {{--</h6>--}}
                                    {{--</a>--}}
                                    {{--@endif--}}

                                    <div class="d-flex  flex-row  font-weight-bold  align-items-center mb-2 ">
                                        {{--<i class="fa fa-money-bill-alt text-primary" aria-hidden="true"></i>--}}
                                        <span>  قیمت واحد:</span>

                                        <div class="mx-2  text-primary     {{ $product->discount_price!=null && $product->discount_price>0  ?'text-decoration-line-through':''}}">{{ number_format($product->price/**$cart['qty']*/).' ت '}} </div>

                                        <i class="fa fa-arrow-alt-circle-left text-dark {{ $product->discount_price==null || $product->discount_price==0  ?'d-none':''}}"
                                           aria-hidden="true"></i>
                                        <div class="mx-2  text-primary   {{ $product->discount_price==null || $product->discount_price==0  ?'d-none':''}}">{{ number_format($product->discount_price/**$cart['qty']*/).' ت '}}</div>

                                    </div>

                                    <div class=" d-flex flex-row  justify-content-end align-items-center mx-2">
                                        <div class="input-group "
                                             dir="ltr">
                                            <div>
                                                <form id="{{'cart-'.$product->id.'-minus'}}"
                                                      class="btn btn-outline-secondary px-2 my-0 rounded-right  {{\App\Models\Cart::count($product->id)<=1? 'disabled':''}}"
                                                      type="button"
                                                      aria-expanded="false" action="{{route('cart.edit' )}}"
                                                      method="post"
                                                      onclick="  document.getElementById('{{ 'cart-'.$product->id.'-minus' }}').submit();"
                                                >

                                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                                    <input type="hidden" name="cmnd" value="minus"/>
                                                    <input type="hidden" name="id" value="{{$product->id}}"/>
                                                    <input type="hidden" name="shop_id" value="{{$product->shop_id}}"/>
                                                    @csrf
                                                </form>
                                            </div>
                                            <div class="  text-center px-4 font-weight-bold    align-self-center "

                                                 aria-label="{{$product->name}}">
                                                {{$cart['qty']}}

                                            </div>
                                            <div>
                                                <form id="{{'cart-'.$product->id.'-plus'}}"
                                                      class="btn btn-outline-secondary px-2 my-0 rounded-left"
                                                      type="button"
                                                      aria-expanded="false" action="{{route('cart.edit' )}}"
                                                      method="post"
                                                      onclick="  document.getElementById( '{{ 'cart-'.$product->id.'-plus' }}' ).submit();">

                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                    <input type="hidden" name="cmnd" value="plus"/>
                                                    <input type="hidden" name="id" value="{{$product->id}}"/>
                                                    <input type="hidden" name="shop_id" value="{{$product->shop_id}}"/>
                                                    @csrf
                                                </form>
                                            </div>
                                        </div>

                                        <form id="{{'cart-'.$product->id.'-remove'}}"
                                              class="mx-2 text-danger  cursor-pointer "
                                              action="{{route('cart.edit' )}}" method="post"
                                              onclick="  document.getElementById('{{ 'cart-'.$product->id.'-remove' }}').submit();">

                                            <i class="fas fa-2x fa-window-close  move-on-hover  "
                                               aria-hidden="true"></i>

                                            <input type="hidden" name="cmnd" value="remove"/>
                                            <input type="hidden" name="id" value="{{$product->id}}"/>
                                            @csrf
                                        </form>

                                    </div>

                                </div>

                            </div>


                        </div>
                    @endforeach
                    <div class="form-group row  ">

                        <div class="col-12">
                            <textarea id="desc-{{$idx}}" type="text" rows="3" maxlength="1000"
                                      placeholder=" توضیحات برای این فروشنده (اختیاری)..."
                                      class="px-4 form-control "
                                      name="{{$idx}}"
                                      autocomplete="description-{{$idx}}"
                                      autofocus>{{ $shop['desc'] ?? ''}}
                            </textarea>


                        </div>
                    </div>
                </div>
            @endforeach
            <hr class="horizontal dark my-2">
            <div class="row text-dark font-bold text-right">
                <div class="col-6">
                    <span> تعداد کالاها</span>
                    <br>
                    <span class="text-primary font-weight-bold">{{ \App\Models\Cart::count() }}</span>
                </div>
                <div class="col-6">
                    <span> قیمت مجموع (بدون هزینه پست)</span>
                    <br>
                    <span class="text-primary font-weight-bold">{{number_format($totalPrice).' ت '}}</span>
                </div>

            </div>
            <ul class="text-end text-dark">
                <li>{{'در صورت خرید از چند فروشنده، سفارشات جداگانه ثبت می شود'}}</li>
                <li>{{'هزینه پست از هر فروشنده، جداگانه محاسبه می شود'}}</li>
                <li>{{'ثبت سفارش شما به معنای موجود بودن آن کالا نیست و به شما اطلاع داده می شود'}}</li>
            </ul>
            <div class="row col-12">
                <a href="{{ url('panel/my-orders/checkout')}}"
                   class="mx-2 text-white  cursor-pointer btn btn-block bg-gradient-success  text-lg"
                >
                    <i class="fas fa-2x fa-cart-plus   "
                       aria-hidden="true"></i>
                    {{(!auth()->user()? 'ورود و ':'') . 'ثبت آدرس'}}


                </a>
            </div>
        @endif
    </div>
</section>

<script type="text/javascript">


    window.onbeforeunload = () => {


        let descs = {};

        $('[id^=desc-]').each((idx, el) => {

            descs[el.name] = el.value;
        });

        axios.post("{!! route('cart.edit') !!}", {
            'cmnd': 'setdescs',
            'descs': descs
        });
//


    }


</script>