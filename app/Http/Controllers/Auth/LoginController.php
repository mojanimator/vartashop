<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Morilog\Jalali\CalendarUtils;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::ROOT;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->redirectTo = /*Cart::count() > 0 ? route('panel/order') :*/
            RouteServiceProvider::ROOT;
        $this->middleware('guest')->except('logout');
        $this->username = $this->findUsername();
    }

    public function authenticated(Request $request, $user)
    {
        if (!$user->active) {
            auth()->logout();
            return back()->with('error-alert', ' حساب کاربری شما غیر فعال است. از طریق لینک های پایین صفحه با ما تماس بگیرید ')
                ->with('token', $user->token);
        }
        if (!$user->email_verified) {
//            auth()->logout();
            return redirect('panel/user-settings')->with('error-alert', 'ایمیل شما هنوز تایید نشده است. لطفا روی لینک تایید که به ایمیل شما ارسال شده است کلیک کنید و یا آن را تغییر دهید')
                ->with('token', $user->token);
        }
        if (!$user->phone_verified) {
//            auth()->logout();
            return redirect('panel/user-settings')->with('error-alert', 'تلفن شما هنوز تایید نشده است. لطفا دکمه ویرایش شماره همراه را بزنید')
                ->with('token', $user->token);
        }
//        dd(CalendarUtils::createCarbonFromFormat('Y/m/d', $user->expires_at));
        if ($user->expires_at && CalendarUtils::createCarbonFromFormat('Y/m/d', $user->expires_at) < Carbon::now()) {
            auth()->logout();
            return back()->with('error-alert', ' اعتبار شما منقضی شده است');
        }


        return redirect()->intended($this->redirectPath());
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function findUsername()
    {
        $login = request()->input('login');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

//        if ($fieldType == 'username') {
//            str_replace('@', '', $login);
//            $login = '@' . $login;
//        }

        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    /**
     * Get username property.
     *
     * @return string
     */
    public function username()
    {
        return $this->username;
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'نام کاربری ضروری است',
            'password.required' => 'گذرواژه ضروری است',
        ]);
    }

//    protected function credentials()
//    {
//        $username = $this->username();
//        $credentials = request()->only($username, 'password');
//        if (isset($credentials[$username])) {
//            $credentials[$username] = strtolower($credentials[$username]);
//        }
//        return $credentials;
//    }
}
