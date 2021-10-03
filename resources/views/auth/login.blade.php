@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-right text-lg text-primary font-weight-bold">ورود</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group row  ">
                                <label for="login"
                                       class="col-md-4 col-form-label text-md-right">{{__('نام کاربری یا ایمیل')}}</label>

                                <div class="col-md-6">
                                    <input id="login" type="text"
                                           class="px-4 form-control{{ $errors->has('username') || $errors->has('email') ? ' is-invalid' : '' }}"
                                           name="login"
                                           {{--class="form-control @error('email') is-invalid @enderror" name="email"--}}
                                           value="{{ old('username') ?: old('email') }}"
                                           {{--autocomplete="email"--}}
                                           autofocus>

                                    {{--@error('email')--}}
                                    @if ($errors->has('username') || $errors->has('email'))
                                        <span class="invalid-feedback text-right" role="alert">
                                        {{--<strong>{{ $message }}</strong>--}}
                                            <strong>{{ $errors->first('username') ?: $errors->first('email') }}</strong>
                                    </span>
                                        {{--@enderror--}}
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password"
                                       class="col-md-4 col-form-label text-md-right"> رمز عبور </label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                           class="px-4 form-control @error('password') is-invalid @enderror"
                                           name="password"
                                           autocomplete="current-password">

                                    @error('password')
                                    <span class="invalid-feedback text-right" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember"
                                               id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            به خاطر بسپار
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8  mx-auto">
                                    <button type="submit" class="btn btn-primary btn-block  ">
                                        ورود
                                    </button>

                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link mx-auto" href="{{ route('password.request') }}">
                                            رمز عبور خود را فراموش کرده ام
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
