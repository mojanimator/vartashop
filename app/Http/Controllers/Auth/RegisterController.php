<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RegisterEditUserMail;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Rules\Recaptcha;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use phpseclib3\Math\BigInteger\Engines\PHP;
use PHPUnit\TextUI\Help;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('guest')->except(['verifyEmail']);
//        $this->middleware('auth')->only(['verifyEmail']);
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $token = bin2hex(openssl_random_pseudo_bytes(30));
        $user = User::create([
            'username' => $data['username'],
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'score' => \Helper::$initScore,
            'token' => $token,

//                'expires_at' => $data['ex_date'] ? CalendarUtils::createCarbonFromFormat('Y/m/d', $data['ex_date'])->addDays(1)->timezone('Asia/Tehran') : null,
        ]);

        foreach (\Helper::$logs as $log) {
            sendTelegramMessage($log, '✨ کاربر در ورتاشاپ ثبت نام کرد' . PHP_EOL . PHP_EOL .
                "$user->name\n$user->username\n$user->email\n$user->phone"
            );
        }

        Mail::to($user->email)->/*queue*/
        queue
        (new RegisterEditUserMail($token, 'register'));

        return $user;
    }

    public function verifyEmail($token, $from)
    {

        if (!$token) {
            return redirect('login')->with('error-alert', 'لینک نامعتبر است!');
        }


        $user = User::where('token', $token)->first();


        if (!$user) {
            return redirect('login')->with('error-alert', 'لینک منقضی شده است!');
        }

        $user->email_verified = 1;

        if ($user->save()) {

            if ($from == 'register')
                return redirect('login')->with('success-alert', 'ثبت نام شما با موفقیت کامل شد!');
            else if ($from == 'edit')
                return redirect('login')->with('success-alert', 'تایید ایمیل با موفقیت کامل شد!');

        }

    }

    protected function registered(Request $request, $user)
    {
        $this->guard()->logout();
//        flash('flash-success', 'برای تکمیل ثبت نام لطفا ایمیل خود را تایید کنید پیام تایید ایمیل  برای شما ارسال شده است');
        return redirect('login')->with('success-alert', 'برای تکمیل ثبت نام لطفا ایمیل خود را تایید کنید پیام تایید ایمیل  برای شما ارسال شده است');
    }

    public function resendEmail(Request $request)
    {
//        $this->guard()->logout();
        $user = User::where('token', $request->token)->first();
//        dd($user);
//        return redirect('login')->with('flash-success', $user->token);
        if ($user) {
//            dispatch(new SendEmailJob($user))->onQueue('default');
            Mail::to($user->email)->send(new RegisterEditUserMail($user->token, 'register'));

            return redirect('login')->with('success-alert', 'برای تکمیل ثبت نام لطفا ایمیل خود را تایید کنید پیام تایید ایمیل  برای شما ارسال شد');
        } else {
            return redirect('login')->with('error-alert', 'کاربر وجود ندارد!');

        }
    }

    public function register(Request $request)
    {

        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

//        $this->guard()->login($user);
//        return redirect('/login')->with('flash-success', 'برای تکمیل ثبت نام لطفا ایمیل خود را تایید کنید پیام تایید ایمیل  برای شما ارسال شده است');

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
//        $this->guard()->logout();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

    protected function validator(array $data)
    {


        return Validator::make($data, [
//            'recaptcha' => ['required', new  Recaptcha()],
            'g-recaptcha-response' => 'recaptcha',
            'name' => 'required|string|min:5|max:30',
            'username' => 'required|string|min:5|max:30|regex:/^[A-Za-z]+[A-Za-z0-9@_][A-Za-z0-9@]{1,28}$/|unique:users,username',
            'email' => ['required', 'string', 'email', 'min:6', 'max:50', 'unique:users,email'],
            'phone' => 'required|numeric|digits:11|regex:/^09[0-9]+$/',

            'password' => 'string|min:6|max:50|confirmed|required',
        ],

            [
                'recaptcha.required' => 'لطفا گزینه من ربات نیستم را تایید نمایید',

                'name.required' => 'نام  نمی تواند خالی باشد',
                'name.string' => 'نام  نمی تواند عدد باشد',
                'name.min' => 'نام  حداقل 5 حرف باشد',
                'name.max' => 'نام  حداکثر 30 حرف باشد',

                'username.required' => 'نام کاربری نمی تواند خالی باشد',
                'username.min' => 'طول نام کاربری حداقل 5 باشد',
                'username.max' => 'طول نام کاربری حداکثر 30 باشد',
                'username.unique' => 'نام کاربری تکراری است',
//            'username.alpha_dash' => 'نام کاربری فقط شامل حروف، عدد و - و _ باشد',
                'username.regex' => 'نام کاربری با حروف شروع شود و می تواند شامل عدد و _ و @ باشد',
                'email.required' => 'ایمیل نمی تواند خالی باشد',
                'email.email' => 'ایمیل نامعتبر است',
                'email.min' => 'ایمیل حداقل 6 حرف باشد',
                'email.max' => 'ایمیل حداکثر 50 حرف باشد',
                'email.unique' => 'ایمیل تکراری است',
                'phone.required' => 'شماره تماس نمی تواند خالی باشد',
                'phone.numeric' => 'شماره تماس باید عدد باشد',
                'phone.digits' => 'شماره تماس  11 رقم و با 09 شروع شود',
                'phone.regex' => 'شماره تماس  11 رقم و با 09 شروع شود',
                'password.required' => 'گذرواژه  ضروری است',
                'password.string' => 'گذرواژه  نمی تواند فقط عدد باشد',
                'password.min' => 'گذرواژه  حداقل 6 حرف باشد',
                'password.max' => 'گذرواژه  حداکثر 50 حرف باشد',
                'password.confirmed' => 'گذرواژه با تکرار آن مطابقت ندارد',

            ]);
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\Models\User
     */
    protected function create2(array $data)
    {
//        $date = Carbon::now();
        $user = null;
        DB::transaction(function () use ($data, & $user) {
            $user = User::create([
                'username' => $data['username'],
                'name' => $data['name'],
                'family' => $data['family'],
                'email' => $data['email'],
                'phone_number' => $data['phone_number'],
                'inline_role' => $data['access_all'] == true && $data['hoozes_all'] == true ? 0 : null, //superuser
                'password' => Hash::make($data['password']),
                'token' => bin2hex(openssl_random_pseudo_bytes(30)),
                'deleted_at' => $data['deactivate_user'] ? Carbon::now() : null,
                'expires_at' => $data['ex_date'] ? CalendarUtils::createCarbonFromFormat('Y/m/d', $data['ex_date'])->addDays(1)->timezone('Asia/Tehran') : null,
            ]);

            $hooze_ids = array();


            if ($data['hoozes_all'] == true)
                array_push($hooze_ids, 'all');


            Role::create([
                'user_id' => $user->id,
                'permissions' => $this->create_access($data),
                'hooze_ids' => $data['hoozes_all'] == true ? ['all'] : $data['hoozes'],
            ]);
//            dispatch(new SendEmailJob($user))->onQueue('default');
            Mail::to($user->email)->send(new RegisterEditUserMail($user->token, 'register'));
//            Mail::to($user->email)->queue(new OrderShipped($order));
        });
        return $user;
    }
}
