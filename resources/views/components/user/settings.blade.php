<section class=" row justify-content-center     ">


    <div class="col-md-12">
        <div class="card move-on-hover">
            <div class="card-header text-center text-lg text-primary font-weight-bold">حساب کاربری</div>

            <div class="card-body text-right">
                <div class="row">


                    <form id="form-name" class="form-group  col-sm-6  " method="POST" action="{{ route('user.edit') }}">
                        @csrf
                        <label for="name-input"
                               class="  col-form-label text-right"> نام </label>

                        <div class="   ">
                            <div class="align-items-stretch flex-row d-flex input-group">
                                <input id="name-input" type="text"
                                       class="border  px-4 form-control{{ $errors->has('name')  ? ' is-invalid' : '' }}"
                                       name="name"
                                       {{-- ?:  returning the first non-false value within a group of expressions--}}
                                       value="{{ auth()->user()->name  }}"
                                       autocomplete="name"
                                       autofocus>
                                <span class="  bg-primary  border   text-white px-2 hoverable-primary"
                                      onclick=" document.getElementById('form-name').submit() ">
                                  ویرایش
                                </span>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    {{--<strong>{{ $errors->first('telegram_username') ?: $errors->first('email') }}</strong>--}}
                                    </span>
                                @enderror
                            </div>


                        </div>
                    </form>
                    <form id="form-username" class="form-group col-sm-6  " method="POST"
                          action="{{ route('user.edit') }}">
                        @csrf
                        <label for="username-input"
                               class="  col-form-label text-right"> نام کاربری </label>

                        <div class="  ">
                            <div class="align-items-stretch flex-row d-flex input-group">
                                <input id="username-input" type="text"
                                       class="border  px-4 form-control{{ $errors->has('username')  ? ' is-invalid' : '' }}"
                                       name="username"
                                       {{-- ?:  returning the first non-false value within a group of expressions--}}
                                       value="{{ auth()->user()->username  }}"
                                       autocomplete="username"
                                       autofocus>
                                <span class="  bg-primary  border   text-white px-2 hoverable-primary"
                                      onclick=" document.getElementById('form-username').submit() ">
                                  ویرایش
                                </span>
                                @error('username')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    {{--<strong>{{ $errors->first('telegram_username') ?: $errors->first('email') }}</strong>--}}
                                    </span>
                                @enderror
                            </div>


                        </div>
                    </form>
                    <form id="form-email" class="form-group col-sm-6  " method="POST"
                          action="{{ route('user.edit') }}">
                        @csrf
                        <label for="email-input"
                               class="  col-form-label text-right"> ایمیل </label>
                        <span class="text-sm font-weight-bolder {{auth()->user()->email_verified?' text-success ':' text-danger ' }}">{{auth()->user()->email_verified?' (فعال) ':' (غیرفعال) ' }}</span>
                        <div class="  ">
                            <div class="align-items-stretch flex-row d-flex input-group">
                                <input id="email-input" type="text"
                                       class="border  px-4 form-control{{ $errors->has('email')  ? ' is-invalid' : '' }}"
                                       name="email"
                                       {{-- ?:  returning the first non-false value within a group of expressions--}}
                                       value="{{ auth()->user()->email  }}"
                                       autocomplete="email"
                                       autofocus>
                                <span class="  bg-primary  border   text-white px-2 hoverable-primary"
                                      onclick=" document.getElementById('form-email').submit() ">
                                  ویرایش
                                </span>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    {{--<strong>{{ $errors->first('telegram_username') ?: $errors->first('email') }}</strong>--}}
                                    </span>
                                @enderror
                            </div>


                        </div>
                    </form>
                    <form id="form-phone" class="form-group col-sm-6  " method="POST"
                          action="{{ route('user.edit') }}">
                        @csrf
                        <label for="phone-input"
                               class="  col-form-label text-right"> شماره همراه </label>
                        <span class="text-sm font-weight-bolder {{auth()->user()->phone_verified?' text-success ':' text-danger ' }}">{{auth()->user()->phone_verified?' (فعال) ':' (غیرفعال) ' }}</span>
                        <div class="  ">
                            <div class="align-items-stretch flex-row d-flex input-group">
                                <input id="phone-input" type="text"
                                       class="border  px-4 form-control{{ $errors->has('phone')  ? ' is-invalid' : '' }}"
                                       name="phone"
                                       {{-- ?:  returning the first non-false value within a group of expressions--}}
                                       value="{{ auth()->user()->phone  }}"
                                       autocomplete="phone"
                                       autofocus>
                                <span class="  bg-primary  border   text-white px-2 hoverable-primary"
                                      onclick=" document.getElementById('form-phone').submit() ">
                                  ویرایش
                                </span>
                                @error('phone')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    {{--<strong>{{ $errors->first('telegram_username') ?: $errors->first('email') }}</strong>--}}
                                    </span>
                                @enderror
                            </div>


                        </div>
                    </form>


                    {{--<div class="form-group row  ">--}}
                    {{--<label for="desc-input"--}}
                    {{--class="col-12 col-form-label text-right">توضیحات</label>--}}

                    {{--<div class="col-12">--}}
                    {{--<textarea id="desc-input" type="text" rows="3"--}}
                    {{--placeholder="در صورت نیاز به توضیحات برای فروشنده..."--}}
                    {{--class="px-4 form-control{{ $errors->has('description')  ? ' is-invalid' : '' }}"--}}
                    {{--name="description"--}}
                    {{--autocomplete="address"--}}
                    {{--autofocus>{{ old('description') }}--}}
                    {{--</textarea>--}}
                    {{--@error('description')--}}
                    {{--<span class="invalid-feedback" role="alert">--}}
                    {{--<strong>{{ $message }}</strong>--}}
                    {{--</span>--}}
                    {{--@enderror--}}

                    {{--</div>--}}
                    {{--</div>--}}
                    <div class="my-4 col-sm-6">
                        <div class=" ">
                            <a href="{{ route('password.request') }}" class="btn bg-gradient-primary btn-block  ">
                                تغییر رمز عبور
                            </a>
                            @error('cart')
                            <div class="  alert   text-center ">
                                <strong class="text-danger">{{ $message }}</strong>
                                {{--<strong>{{ $errors->first('telegram_username') ?: $errors->first('email') }}</strong>--}}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="my-4 col-sm-6">
                        @php
                            $link =base64_encode(utf8_encode("c\$".auth()->user()->id));
                       $link = "https://t.me/".str_replace('@','',Helper::$bot)."?start=$link";
                        @endphp

                        <div class="row ">
                            <a href="{{ $link }}" class="btn bg-gradient-success btn-block  ">
                                اتصال به تلگرام
                            </a>
                            @error('cart')
                            <div class="  alert   text-center ">
                                <strong class="text-danger">{{ $message }}</strong>
                                {{--<strong>{{ $errors->first('telegram_username') ?: $errors->first('email') }}</strong>--}}
                            </div>
                            @enderror

                            <div class="col-6 small">
                                <span class="text-dark">نام کاربری تلگرام:</span>
                                <span class="text-primary">{{auth()->user()->telegram_username??'نامشخص'}}</span>
                            </div>
                            <div class="col-6 small">
                                <span class="text-dark">آیدی تلگرام:</span>
                                <span class="text-primary">{{auth()->user()->telegram_id??'نامشخص'}}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


</section>

@section('script')


    <script type="text/javascript">


        function editUser(col, val) {

        }

    </script>

@endsection