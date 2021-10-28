@component('mail::message')
@lang($title) <br><br>

@lang($message) <br>

@component('mail::button', ['url' =>  url("panel/my-orders/order-details?order_id=$id") ,'color' => 'gradient'])
        ورود به سایت
@endcomponent


@lang('اگر دکمه را نمی بینید لینک زیر را در نوار آدرس مرورگر کپی کنید')
<br>
@lang(url("panel/my-orders/order-details?order_id=$id"))
<br>
<br>
<br>
@lang(    'با تشکر')
{{--سامانه مدارس عشایر کرمان--}}
{{ config('app.name') }}

@endcomponent
