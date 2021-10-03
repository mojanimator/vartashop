<?php

namespace App\Http\Controllers;


use App\Http\Requests\UserRequest;
use App\Mail\RegisterEditUserMail;
use App\Models\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Morilog\Jalali\CalendarUtils;
use PHPUnit\TextUI\Help;

class UserController extends Controller
{
    public function __construct()
    {
//        parent::__construct();
        $this->middleware('auth')->except(['show']);
    }

    public function show()
    {

        return view('user.show');
    }

    public function showPanel()
    {

        return view('user.panel');
    }

    public function view()
    {

        return view('user.users');
    }

    protected function create(UserRequest $data)
    {
//        $date = Carbon::now();
        $user = null;

        DB::transaction(function () use ($data, & $user) {


            $user = User::create([
                'username' => $data['username'],
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
                'score' => \Helper::$initScore,
                'token' => bin2hex(openssl_random_pseudo_bytes(30)),

//                'expires_at' => $data['ex_date'] ? CalendarUtils::createCarbonFromFormat('Y/m/d', $data['ex_date'])->addDays(1)->timezone('Asia/Tehran') : null,
            ]);

//            dispatch(new SendEmailJob($user))->onQueue('default');
            Mail::to($user->email)->send(new RegisterEditUserMail($user->token, 'register'));
//            Mail::to($user->email)->queue(new OrderShipped($order));
            return redirect(route('user.view', [auth()->user()->username]))->with('success-alert', 'برای تکمیل ثبت نام، روی لینکی که به ایمیل شما ارسال شده است کلیک کنید.');

            return 200;
        });
        return 404;
    }

    protected function edit(Request $request)
    {


        $request->validate([

            'name' => 'sometimes|string|min:5|max:30',
            'username' => 'sometimes|string|min:5|max:30|regex:/^[A-Za-z]+[A-Za-z0-9@_][A-Za-z0-9@]{1,28}$/|unique:users,username',
            'email' => ['sometimes', 'email', 'min:6', 'max:50', Rule::unique('users')->ignore(auth()->user())],
            'phone' => 'sometimes|numeric|digits:11|regex:/^09[0-9]+$/',

        ],
            ['name.string' => 'نام  نمی تواند عدد باشد',
                'name.min' => 'نام  حداقل 5 حرف باشد',
                'name.max' => 'نام  حداکثر 30 حرف باشد',


                'username.min' => 'طول نام کاربری حداقل 5 باشد',
                'username.max' => 'طول نام کاربری حداکثر 30 باشد',
                'username.unique' => 'نام کاربری تکراری است',
//            'username.alpha_dash' => 'نام کاربری فقط شامل حروف، عدد و - و _ باشد',
                'username.regex' => 'نام کاربری با حروف شروع شود و می تواند شامل عدد و _ و @ باشد',
                'email.email' => 'ایمیل نامعتبر است',
                'email.min' => 'ایمیل حداقل 6 حرف باشد',
                'email.max' => 'ایمیل حداکثر 50 حرف باشد',
                'email.unique' => 'ایمیل تکراری است',

                'phone.numeric' => 'شماره تماس باید عدد باشد',
                'phone.digits' => 'شماره تماس  11 رقم و با 09 شروع شود',
                'phone.regex' => 'شماره تماس  11 رقم و با 09 شروع شود',

            ]);

//

        return DB::transaction(function () use ($request) {

            $user = auth()->user();

            $name = $request->name;
            $username = $request->username;
            $email = $request->email;
            $phone = $request->phone;

            if ($name) {
                $user->name = $name;
                $user->save();
                return redirect()->back()->with('success-alert', 'نام با موفقیت ویرایش شد!');
            } elseif ($username) {
                $user->username = $username;
                $user->save();
                return redirect()->back()->with('success-alert', 'نام کاربری با موفقیت ویرایش شد!');
            } elseif ($email) {
                $emailChanged = $user->email != $request->email ? true : false;
                if ($emailChanged || !$user->email_verified) {
                    $user->email = $email;
                    $user->email_verified = false;
                    $user->token = bin2hex(openssl_random_pseudo_bytes(30));
                    $user->save();
                    Mail::to($email)->/*queue*/
                    queue
                    (new RegisterEditUserMail($user->token, 'edit'));

                    return redirect()->back()->with('success-alert', 'لینک تایید ایمیل به شما ارسال شد. وارد ایمیل خود شده و روی لینک کلیک کنید.');
                }
//                return redirect()->to('panel/user-settings')->with('success-alert', 'لینک تایید ایمیل به شما ارسال شد. وارد ایمیل خود شده و روی لینک کلیک کنید.');
            } elseif ($phone) {
                $phoneChanged = $user->phone != $request->phone ? true : false;
                if ($phoneChanged || !$user->phone_verified) {
                    $user->phone = $phone;
                    $user->phone_verified = false;
                    $user->save();
                    $adminPhone = str_replace('09', '9', \Helper::$admin_phone);
                    $link = "<a href='https://api.whatsapp.com/send/?phone=$adminPhone&text=تایید' class='text-white hoverable-text-dark'>لینک</a>";
                    $txt = "عبارت 'تایید' را از شماره وارد شده به پیامک یا واتساپ $adminPhone ارسال کنید." . "<br>" . "برای دسترسی سریعتر به واتساپ میتوانید از این $link استفاده کنید.";
                    return redirect()->back()->with('success-alert', $txt);
                }
//                return redirect()->to('panel/user-settings')->with('success-alert', 'لینک تایید ایمیل به شما ارسال شد. وارد ایمیل خود شده و روی لینک کلیک کنید.');
            }

            return;
            $editorRole = Role::where('user_id', auth()->user()->id)->first();

            $user = User::findOrFail($request->id);
            $role = Role::where('user_id', $user->id)->first();
            if (!auth()->user()->inline_role == 1 && $user->inline_role == 1)
                return abort(403, 'مجاز به ویرایش کاربر سوپرادمین نیستید!');

            $user->username = $request->username;
            $user->name = $request->name;
            $user->family = $request->family;
            $user->email = $request->email;
            $user->phone_number = $request->phone_number;
            $user->inline_role = auth()->user()->inline_role == 1 && $request->access_all == true && $request->hoozes_all == true ? 1 : null; //superuser
            $user->password = $request->password ? Hash::make($request->password) : $user->password;
            $user->token = $token;
            $user->deleted_at = $request->deactivate_user ? Carbon::now() : null;
            $user->expires_at = $request->ex_date ? CalendarUtils::createCarbonFromFormat('Y/m/d', $request->ex_date)/*->addDays(1)*/
            ->timezone('Asia/Tehran') : null;

            if ($emailChanged) $user->verified = 0;
            $user->save();

            if ($emailChanged) $this->resendEmail($request->email, $token, 'edit');

        });

    }

    public function search(Request $request)
    {
        $user_access = auth()->user() ? auth()->user()->role()->first()->permissions : [];
        $is_superuser = count($user_access) > 0 && in_array('all', $user_access) ? true : false;
        if (count($user_access) == 0 || !$is_superuser && !in_array('vu', $user_access)) //user not hooze access
            return [];

        $request->validate([
            'name' => 'nullable|max:100',
            'paginate' => 'nullable|numeric|max:1000000000',
            'page' => 'nullable|numeric|max:1000000000',
//            'for' => 'sometimes|string|in:dropdown',
        ], [
            'name.max' => 'حداکثر طول نام 100 کاراکتر است',
            'paginate.numeric' => 'صفحه بندی نامعتبر است.',
            'paginate.max' => 'صفحه بندی نامعتبر است.',
            'page.numeric' => 'صفحه  نامعتبر است.',
            'page.max' => 'صفحه  نامعتبر است.',
//            'for.string' => 'نوع درخواست حوزه نامعتبر است',
//            'for.in' => 'نوع درخواست حوزه نامعتبر است',
        ]);
        $query = User::where('id', '<>', auth()->user()->id); //not return self user

        if (!$is_superuser)
            $query = $query->where('inline_role', '<>', 1); //only superuser can see superusers

        $name = $request->name;
        $paginate = $request->paginate;
        $page = $request->page;
        $sortBy = $request->sortBy;
        $direction = $request->direction;


        if (!$paginate) {
            $paginate = 24;
        }
        if (!$page) {
            $page = 1;
        }


        if ($name != '')
            $query = $query->where('name', 'like', '%' . $name . '%');


        if ($sortBy && $direction)
            $query = $query->orderBy($sortBy, $direction);

        return $query->with('role')->paginate($paginate, ['*'], 'page', $page);

    }

    protected function resendEmail($email, $token, $from)
    {

//            dispatch(new SendEmailJob($user))->onQueue('default');
        Mail::to($email)->send(new RegisterEditUserMail($token, $from));

        return redirect(route('user.view', [auth()->user()->username]))->with('flash-success-edit', ' پیام تایید ایمیل  برای کاربر ارسال شد');

    }

    protected function create_access(Request $data)
    {
        $roles = array();

        if ($data['access_all'] == true)
            array_push($roles, 'all');
        else {
            if ($data['access_view_schools'] == true)
                array_push($roles, 'vs');
            if ($data['access_create_schools'] == true)
                array_push($roles, 'cs');
            if ($data['access_edit_schools'] == true)
                array_push($roles, 'es');
            if ($data['access_remove_schools'] == true)
                array_push($roles, 'rs');
            if ($data['access_view_users'] == true)
                array_push($roles, 'vu');
            if ($data['access_create_users'] == true)
                array_push($roles, 'cu');
            if ($data['access_edit_users'] == true)
                array_push($roles, 'eu');
            if ($data['access_remove_users'] == true)
                array_push($roles, 'ru');
            if ($data['access_view_hoozes'] == true)
                array_push($roles, 'vh');
            if ($data['access_create_hoozes'] == true)
                array_push($roles, 'ch');
            if ($data['access_edit_hoozes'] == true)
                array_push($roles, 'eh');
            if ($data['access_remove_hoozes'] == true)
                array_push($roles, 'rh');
            if ($data['access_view_reports'] == true)
                array_push($roles, 'vr');


        }
        return $roles;
    }

    public function destroy(Request $request)
    {


        $request->validate([
            'id' => 'required|numeric|min:1|max:4294967295',

        ], [
            'id.required' => 'شناسه کاربر نامعتبر است',
            'id.numeric' => 'شناسه کاربر نامعتبر است',
            'id.min' => 'شناسه کاربر نامعتبر است',
            'id.max' => 'شناسه کاربر نامعتبر است',

        ]);

        DB::transaction(function () use ($request) {
            Schema::disableForeignKeyConstraints();

            User::destroy($request->id);
            Schema::enableForeignKeyConstraints();

            return "200";
        });
        return null;
    }

    public function storeImage(Request $request)
    {

        $validatedData = $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',

        ]);

        $name = $request->file('image')->getClientOriginalName();

        $path = $request->file('image')->store('public/users');


        $save = new Photo;

        $save->name = $name;
        $save->path = $path;

        return redirect('panel/settings')->with('status', 'Image Has been uploaded successfully in laravel 8');

    }
}


