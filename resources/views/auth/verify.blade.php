@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="text-white card-header">{{ __('تایید آدرس ایمیل') }}</div>

                    <div class="card-body">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                {{ __('لینک تکمیل ثبت نام به ایمیل شما فرستاده شد') }}
                            </div>
                        @endif

                        {{--{{ __('Before proceeding, please check your email for a verification link.') }}--}}
                        {{ __('اگر ایمیلی دریافت نکردید روی لینک زیر کلیک کنید') }}, <a
                                href="{{ route('verification.resend') }}">{{ __('ارسال مجدد ایمیل') }}</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
