@extends('layouts.app')
@section('title')
    خطای دسترسی
@stop

@section('content')


    <div class="alert alert-danger mt-5 m-4 text-right  text-light" dir="rtl">
        <strong class="text-white">خطای دسترسی!</strong> <br>
        {{ $exception->getMessage() }}
        <br>
        <strong>
            <a
                    href="@if(auth()->user()){{url()->previous()}}
                    @else{{url()->previous()}}@endif"
                    class="text-dark-red text-left">
                بازگشت <i class="fa fa-backward text-dark-red"></i></a></strong>
    </div>
@stop