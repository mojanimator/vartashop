@extends('layouts.app')

@section('content')
    <div class="container  ">
        <div class="row justify-content-center">
            <div class="col-md-8 mx-auto  ">
                <div class="card bg-light">
                    <h5 class="card-header text-center text-primary bg-light">ثبت نام</h5>

                    <div class="card-body  ">
                        <form method="POST" action="{{ route('register') }}" class="text-right">
                            @csrf

                            <div class="form-group  justify-content-center ">
                                <label for="name"
                                       class="col-md-12 col-form-label text-md-right">{{ __('name') }}</label>

                                <div class="col-md-12">
                                    <input id="name" type="text"
                                           class="px-4 form-control @error('name') is-invalid @enderror" name="name"
                                           value="{{ old('name') }}" autocomplete="name" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group  ">
                                <label for="username"
                                       class="col-md-12 col-form-label text-md-right">{{ __('username') }}</label>

                                <div class="col-md-12">
                                    <input id="username" type="text"
                                           class="px-4 form-control @error('username') is-invalid @enderror"
                                           value="{{ old('username') }}"
                                           name="username"
                                           autocomplete="username">

                                    @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group  ">
                                <label for="email"
                                       class="col-md-12 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-12">
                                    <input id="email" type="email"
                                           class="px-4 form-control @error('email') is-invalid @enderror" name="email"
                                           value="{{ old('email') }}" autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="form-group  ">
                                <label for="phone"
                                       class="col-md-12 col-form-label text-md-right">{{ __('phone') }}</label>

                                <div class="col-md-12">
                                    <input id="phone" type="tel"
                                           class="px-4 form-control @error('phone') is-invalid @enderror" name="phone"
                                           value="{{ old('phone') }}"
                                           autocomplete="phone">

                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group  ">
                                <label for="password"
                                       class="col-md-12 col-form-label text-md-right">{{ __('Password') }}</label>

                                <div class="col-md-12">
                                    <input id="password" type="password"
                                           class="px-4 form-control @error('password') is-invalid @enderror"
                                           name="password"
                                           value="{{ old('password') }}"
                                           autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group  ">
                                <label for="password-confirm"
                                       class="px-4 col-md-12 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                <div class="col-md-12">
                                    <input id="password-confirm" type="password" class="px-2 form-control"
                                           name="password_confirmation" autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-group   mb-0">
                                <div class="col-md-12  ">
                                    <button type="submit" class="btn btn-success btn-block">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-md-right"> </label>
                                <div class="col-md-6">
                                    {!! htmlFormSnippet
                                    ([
    "theme" => "light",
    "size" => "normal",
    "tabindex" => "3",
])
 !!}
                                </div>
                                @error('g-recaptcha-response')
                                <span class="  text-center text-danger @error('g-recaptcha-response') is-invalid @enderror "
                                      role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
