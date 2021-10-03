<?php

namespace App\Http\Requests;

use App\Hooze;
use App\Rules\Recaptcha;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;


class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        if ($this->input('type') == 'edit')
            return Gate::allows('editAny', 'App\User');
        else if ($this->input('type') == 'create')
            return Gate::allows('createAny', 'App\User');

        else  return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

//        $user = User::findOrFail(1);
        $user = null;
        if ($this->input('type') == 'edit')
            $user = User::findOrFail($this->input('id'));
//        dd($exclude);
        return ['recaptcha' => ['required', new  Recaptcha()],
            'id' => 'numeric|exists:users,id|' . $this->input('type') == 'edit' ? 'required' : 'sometimes',
            'username' => ['required', 'string', 'min:6', 'max:20', $this->input('type') == 'edit' ? Rule::unique('users')->ignore($user) : Rule::unique('users')],//unique except this id (edit)
            'name' => 'required|string|min:3|max:50',
            'family' => 'required|string|min:3|max:50',
            'phone_number' => 'required|numeric|max:99999999999999999999',
            'email' => ['required', 'string', 'email', 'min:6', 'max:50', $this->input('type') == 'edit' ? Rule::unique('users')->ignore($user) : Rule::unique('users')],
            'password' => 'string|min:6|max:50|confirmed|' . $this->input('type') == 'edit' ? 'required' : 'nullable',
            'access_all' => 'required|boolean',
            'access_view_schools' => 'required|boolean',
            'access_create_schools' => 'required|boolean',
            'access_edit_schools' => 'required|boolean',
            'access_remove_schools' => 'required|boolean',
            'access_view_users' => 'required|boolean',
            'access_create_users' => 'required|boolean',
            'access_edit_users' => 'required|boolean',
            'access_remove_users' => 'required|boolean',
            'access_create_hoozes' => 'required|boolean',
            'access_edit_hoozes' => 'required|boolean',
            'access_remove_hoozes' => 'required|boolean',
            'access_view_reports' => 'required|boolean',
            'hoozes_all' => 'required|boolean',
            'deactivate_user' => 'required|boolean',
            "hoozes" => "nullable|array",
            "hoozes.*" => "numeric|exists:hoozes,id",];
    }

    public function messages()
    {
//        $isAgent = $this->input('is_agent');
        return [
            'recaptcha.required' => 'لطفا گزینه من ربات نیستم را تایید نمایید',
            'username.required' => 'نام کاربری ضروری است',
            'username.string' => 'نام کاربری نمی تواند عدد باشد',
            'username.min' => 'نام کاربری حداقل 6 حرف باشد',
            'username.max' => 'نام کاربری حداکثر 20 حرف باشد',
            'username.unique' => 'نام کاربری تکراری است',
            'name.required' => 'نام  ضروری است',
            'name.string' => 'نام  نمی تواند عدد باشد',
            'name.min' => 'نام  حداقل 3 حرف باشد',
            'name.max' => 'نام  حداکثر 50 حرف باشد',
            'family.required' => 'نام خانوادگی ضروری است',
            'family.string' => 'نام خانوادگی نمی تواند عدد باشد',
            'family.min' => 'نام خانوادگی حداقل 3 حرف باشد',
            'family.max' => 'نام خانوادگی  حداکثر 50 حرف باشد',
            'phone_number.required' => 'شماره تماس ضروری است',
            'phone_number.numeric' => 'شماره تماس باید عدد باشد',
            'phone_number.max' => 'شماره تماس حداکثر 20 عدد باشد',
            'email.required' => 'ایمیل ضروری است',
            'email.string' => 'ایمیل نامعتبر است',
            'email.email' => 'ایمیل نامعتبر است',
            'email.min' => 'ایمیل حداقل 6 حرف باشد',
            'email.max' => 'ایمیل حداکثر 50 حرف باشد',
            'email.unique' => 'ایمیل تکراری است',
            'password.required' => 'گذرواژه  ضروری است',
            'password.string' => 'گذرواژه  نمی تواند فقط عدد باشد',
            'password.min' => 'گذرواژه  حداقل 6 حرف باشد',
            'password.max' => 'گذرواژه  حداکثر 50 حرف باشد',
            'password.confirmed' => 'گذرواژه با تکرار آن مطابقت ندارد',
            'access_all.required' => 'پارامتر دسترسی نامعتبر است',
            'access_all.boolean' => 'پارامتر دسترسی نامعتبر است',
            'access_view_schools.required' => 'پارامتر دسترسی نامعتبر است',
            'access_view_schools.boolean' => 'پارامتر دسترسی نامعتبر است',
            'access_create_schools.required' => 'پارامتر دسترسی نامعتبر است',
            'access_create_schools.boolean' => 'پارامتر دسترسی نامعتبر است',
            'access_edit_schools.required' => 'پارامتر دسترسی نامعتبر است',
            'access_edit_schools.boolean' => 'پارامتر دسترسی نامعتبر است',
            'access_remove_schools.required' => 'پارامتر دسترسی نامعتبر است',
            'access_remove_schools.boolean' => 'پارامتر دسترسی نامعتبر است',
            'access_view_users.required' => 'پارامتر دسترسی نامعتبر است',
            'access_view_users.boolean' => 'پارامتر دسترسی نامعتبر است',
            'access_create_users.required' => 'پارامتر دسترسی نامعتبر است',
            'access_create_users.boolean' => 'پارامتر دسترسی نامعتبر است',
            'access_edit_users.required' => 'پارامتر دسترسی نامعتبر است',
            'access_edit_users.boolean' => 'پارامتر دسترسی نامعتبر است',
            'access_remove_users.required' => 'پارامتر دسترسی نامعتبر است',
            'access_remove_users.boolean' => 'پارامتر دسترسی نامعتبر است',
            'access_create_hoozes.required' => 'پارامتر دسترسی نامعتبر است',
            'access_create_hoozes.boolean' => 'پارامتر دسترسی نامعتبر است',
            'access_remove_hoozes.required' => 'پارامتر دسترسی نامعتبر است',
            'access_remove_hoozes.boolean' => 'پارامتر دسترسی نامعتبر است',
            'access_view_reports.required' => 'پارامتر دسترسی نامعتبر است',
            'access_view_reports.boolean' => 'پارامتر دسترسی نامعتبر است',
            'access_edit_hoozes.required' => 'پارامتر دسترسی نامعتبر است',
            'access_edit_hoozes.boolean' => 'پارامتر دسترسی نامعتبر است',
            'hoozes_all.required' => 'پارامتر دسترسی نامعتبر است',
            'hoozes_all.boolean' => 'پارامتر دسترسی نامعتبر است',
            'deactivate_user.required' => 'پارامتر دسترسی نامعتبر است',
            'deactivate_user.boolean' => 'پارامتر دسترسی نامعتبر است',
            'hoozes.array' => 'نوع حوزه ها نامعتبر است',
            "hoozes.*.numeric" => "نوع حوزه ها نامعتبر است",
            "hoozes.*.exists" => "حوزه ها موجود نیستند!",
        ];

    }
}
