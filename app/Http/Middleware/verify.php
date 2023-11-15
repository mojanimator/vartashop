<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class verify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if (!auth()->user())
            return redirect('login');
        elseif (!auth()->user()->email_verified && $request->url() != url('panel/user-settings'))
            return redirect()->to('panel/user-settings')->with('error-alert', 'ایمیل شما هنوز تایید نشده است. لطفا روی لینک تایید که به ایمیل شما ارسال شده است کلیک کنید و یا آن را تغییر دهید');
        elseif (!auth()->user()->phone_verified && $request->url() != url('panel/user-settings'))
            return redirect()->to('panel/user-settings')->with('error-alert', 'شماره همراه شما هنوز تایید نشده است. لطفا روی ویرایش شماره همراه کلیک کرده و شماره خود را تایید کنید');
//        elseif (\App\Models\Shop::orWhere('user_id', auth()->user()->id)->orWhereIn('id', \App\Models\Rule::where('user_id', auth()->user()->id)->pluck('shop_id'))->count() > 0)
//            return redirect()->to('panel')->with('error-alert', 'با اکانت ادمین قادر به ثبت سفارش نیستید');
        return $next($request);
    }
}
