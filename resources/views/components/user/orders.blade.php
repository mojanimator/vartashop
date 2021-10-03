<div class="  " data-toggle="modal">

    @php
        $params=json_decode($params);
            $query=\App\Models\Order::query();
     $query->where('status',$params->status);
     $query=$query->paginate(12);
    $query=$query->appends(['status'=>$params->status])
    @endphp

    <div class="col-10 mx-auto  text-primary text-center  my-1  font-weight-bold ">
        سفارشات&nbsp <span class="text-dark font-weight-bolder">{{orderNameFromId($params->status)}}</span>
    </div>

    @if (  $query->total()  ==0 )
        <hr class="horizontal dark my-2">
        <h4 class="text-center text-dark mt-3">سفارشی پیدا نشد </h4>
        <a href="{{url('panel/my-orders')}}"
           class="  ">
            <h6
                    class="hoverable-text-info text-primary text-center"> بازگشت
            </h6>
        </a>
    @endif



    @foreach($query as $order)
        <div class="col-10 card text-right border-info border-2 my-3 py-2    ">
            <div class="card-header py-2 ">

                <div class="input-group   position-absolute " style="top:-.7rem;">
                            <span class="rounded-right    bg-primary text-white small d-inline-block px-1 "
                            > شماره سفارش

                            </span>
                    <span class="rounded-left    bg-dark text-white small d-inline-block px-1 ">
                        {{$order->id}}

                            </span>

                </div>

                <div class="input-group position-absolute justify-content-end left-1" style="top:-.7rem;">
                    <a href="{{url('panel/my-orders/details?order_id='.$order->id)}}"
                       class="   rounded-pill-right   bg-info text-white small   p-2 move-on-hover cursor-pointer">
                        مشاهده جزییات

                    </a>
                    <div class="    rounded-pill-left  bg-danger text-white small   p-2 move-on-hover cursor-pointer"
                         onclick=" showDialog('confirm','سفارش شما لغو و حذف خواهد شد',()=>{
                             document.getElementById('delete-order').submit();
                         })">
                        لغو سفارش

                    </div>

                </div>
                <form id="delete-order" method="POST"
                      action="{{route('order.delete',['order_id'=>$order->id,'order_user_id'=>$order->user_id,'shop_id'=>$order->shop_id])}}">
                    @csrf
                    {{--<input type="hidden" name="order_id" value="{{$order->id}}">--}}
                    {{--<input type="hidden" name="shop_id" value="{{$order->shop_id}}">--}}
                </form>

                <div class="input-group">
                    <span class="rounded-right    bg-info text-white small d-inline-block px-1">تاریخ ثبت  </span>
                    <span class="rounded-left    bg-dark text-white small d-inline-block px-1">{{\Morilog\Jalali\Jalalian::fromDateTime($order->created_at)->format('%A, %d %B %Y ⏰ H:i')}}</span>
                </div>
                <div>
                    <span class="text-primary">آدرس: </span>
                    <span class="text-dark">{{$order->address}}</span>
                </div>
                <div class="border-bottom  border-info border-2  py-1 "></div>
                <div class="d-flex flex-row">
                    @foreach ($order->products as $product)
                        <div class="d-flex flex-column   align-items-center">
                            <a href="{{route('product.view',[$product->slug,$product->id])}}"
                               title="{{$product->name}}" data-toggle="tooltip"
                               class=" m-1 ">
                                <img src="{{$product->image()}}" alt=""
                                     class="max-width-100 max-height-100 rounded-lg  ">
                            </a>
                            <div class="text-primary">
                                <span class="text-dark">تعداد:&nbsp</span>

                                {{ $product->pivot->qty }}</div>
                            <div class="text-primary">
                                <span class="text-dark">قیمت واحد:&nbsp</span>
                                {{number_format( $product->pivot->unit_price).' ت ' }}</div>
                        </div>
                    @endforeach
                </div>
                <div class="border-bottom  border-info border-2  py-1 "></div>

                <div class="col-12 row justify-content-between">
                    <div class="col-sm-6">
                        <span class="text-primary">قیمت کالاها: </span>
                        <span class="text-dark">{{number_format($order->total_price).' ت ' }}</span>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-primary">هزینه پست: </span>
                        <span class="text-dark">{{$order->post_price===null?'مشخص نشده':($order->post_price==0?'رایگان':number_format($order->post_price). ' ت ')}}</span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach


    <div class="text-center mx-auto w-50">
        {{ $query->links()   }}
    </div>
</div>

@section('script')

@endsection