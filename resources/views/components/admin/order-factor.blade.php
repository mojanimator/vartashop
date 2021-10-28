<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }} " dir="rtl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->


    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
{{--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css">--}}
{{--integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N" crossorigin="anonymous">--}}
<!-- Fonts -->

{{--<style type="text/css">--}}
{{--@font-face {--}}
{{--font-family: Tanha;--}}
{{--src: url({{asset('fonts/Farsi-Digits/Tanha-FD.eot')}});--}}
{{--src: url({{asset('fonts/Farsi-Digits/Tanha-FD.eot?#iefix')}}) format('Tanha-FD-opentype'),--}}
{{--url({{asset('fonts/Farsi-Digits/Tanha-FD.woff')}}) format('woff'),--}}
{{--url({{asset('fonts/Farsi-Digits/Tanha-FD.ttf')}}) format('truetype');--}}
{{--font-weight: normal;--}}
{{--font-style: normal;--}}
{{--}--}}

{{--* {--}}
{{--font-family: Tanha, DejaVu Sans, sans-serif;--}}
{{--}--}}
{{--</style>--}}
<!-- Styles -->


</head>
<body>
@php
    if(isset($id))
        $order=\App\Models\Order::where('id',$id)->whereIn('shop_id',auth()->user()->shopIds())->with('products')->with('shop')->with('province')->with('county')->first();

@endphp


<div class="container-fluid border border-primary rounded-3 border-2 text-right p-4 w-80   position-relative"
     style="font-family: Tanha,serif">

    <div class=" small left-1 top-1 text-success   text-left  " style="">
        <div>
            <span class="small" style=""> تاریخ صدور:</span>
            <span class="small font-weight-bold"> {{\Morilog\Jalali\Jalalian::fromDateTime(\Carbon\Carbon::now('Asia/Tehran'))->format('%A, %d %B %Y ساعت H:i')}}</span>
        </div>
        <div>
            <span class="small"> بازارچه اینترنتی ورتا</span> <span class="small font-weight-bold"> vartashop.ir</span>
        </div>

    </div>
    <div>
        <div class="border-2 border-bottom border-info text-primary py-2 text-lg">فروشنده</div>
        <div class="py-1">نام: <span class="text-dark font-weight-bold"> {{$order->shop->name}}</span></div>
        <div class="pb-1">تلفن: <span
                    class="text-dark font-weight-bold"> {{str_replace('+98','0',$order->shop->contact)}}</span></div>
        <div class="pb-1">کد پستی: <span class="text-dark font-weight-bold"> {{ $order->shop->postal_code }}</span>
        </div>
        <div class="pb-1">
            <span>استان: <span class="text-dark font-weight-bold"> {{$order->shop->province->name??''}}</span></span>
            <span class="px-5">شهر: <span class="text-dark font-weight-bold"> {{$order->shop->county->name??''}}</span></span>
        </div>
        <div class="pb-1">آدرس: <span class="text-dark font-weight-bold"> {{$order->shop->address}}</span></div>

    </div>
    <div class="py-3">
        <div class="border-2 border-bottom border-info text-primary py-2 text-lg">گیرنده</div>
        <div class="py-1">نام: <span class="text-dark font-weight-bold"> {{$order->name}}</span></div>
        <div class="pb-1">تلفن: <span
                    class="text-dark font-weight-bold"> {{str_replace('+98','0',$order->phone)}}</span></div>
        <div class="pb-1">کد پستی: <span class="text-dark font-weight-bold"> {{ $order->postal_code }}</span>
        </div>
        <div class="pb-1">
            <span>استان: <span class="text-dark font-weight-bold"> {{$order->province->name??''}}</span></span>
            <span class="px-5">شهر: <span class="text-dark font-weight-bold"> {{$order->county->name??''}}</span></span>
        </div>
        <div class="pb-1">آدرس: <span class="text-dark font-weight-bold"> {{$order->address}}</span></div>

    </div>
    <div class="py-3">

        <div class="border-2 border-bottom border-info text-primary py-2 text-lg">جزییات سفارش</div>
        <div class="pb-1">
            <span class="pb-1">کد سفارش: <span
                        class="text-dark font-weight-bold"> {{$order->id!='new'?"#$order->id":''}}</span></span>
            <span class="px-5">تاریخ ارسال: <span
                        class="text-dark font-weight-bold"> {{\Morilog\Jalali\Jalalian::fromDateTime($order->send_at??\Carbon\Carbon::now('Asia/Tehran'))->format('%A, %d %B %Y   ')}}</span></span>
        </div>
        <div class="pb-1">کد رهگیری پست: <span class="text-dark font-weight-bold"> {{"$order->post_trace"}}</span></div>
        <div class="pb-1">شناسه پرداخت: <span class="text-dark font-weight-bold"> {{"$order->pay_id"}}</span></div>


        <div class="row mr-sm-5 mr-1">
            <div class="border-2 border-bottom border-dark text-primary py-2 text-lg">محصولات</div>

            <table class="table w-100      table-bordered   " dir="rtl"
                   style="font-family: Tanha, serif;  table-layout: fixed;

      ">
                <thead>
                <tr>
                    <th width="10%" scope="col" class="small">کد</th>
                    <th width="50%" scope="col" class="small">نام</th>
                    <th width="20%" scope="col" class="small">تعداد</th>
                    <th width="20%" scope="col" class="small">قیمت واحد</th>

                </tr>
                </thead>
                <tbody>

                @php($count=0)
                @foreach ($order->products as $idx=>$product)
                    @php($count+=$product->pivot->qty)
                    <tr class="    ">
                        <th scope="row">{{$product->id}}</th>
                        <td class="small align-middle font-weight-bold  "
                            style="word-wrap:break-word; white-space: normal; ">  {{$product->name}}</td>
                        <td class="font-weight-bold "
                            style="word-wrap: break-word;white-space: normal; ">  {{$product->pivot->qty}}</td>
                        <td class=" font-weight-bold"
                            style="word-wrap: break-word; white-space: normal;">  {{number_format( $product->pivot->unit_price).' ت ' }}</td>

                    </tr>
                @endforeach
                <tr class="    ">
                    <th scope="row"></th>
                    <td>هزینه پست</td>
                    <td></td>
                    <td class=" font-weight-bold" colspan="1"
                        style="word-wrap: break-word; white-space: normal;">  {{number_format( $order->post_price).' ت ' }}</td>

                </tr>
                <tr class="    ">
                    <th scope="row"></th>
                    <td class="  font-weight-bold">مجموع</td>
                    <td class="font-weight-bold">{{$count}}</td>
                    <td class=" font-weight-bold" colspan="1"
                        style="word-wrap: break-word; white-space: normal;">  {{number_format( $order->total_price).' ت ' }}</td>

                </tr>
                </tbody>
            </table>
        </div>

        <div class="border-2 border-bottom border-info  py-2  "></div>
        <div class="position-absolute left-4 text-left bottom-1" style="">

            <div>
                <span class="small text-success"> بازارچه اینترنتی ورتا</span> <span
                        class="small font-weight-bold"> vartashop.ir</span>
            </div>

        </div>
    </div>

</div>

</body>
</html>


