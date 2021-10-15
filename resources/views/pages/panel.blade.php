@extends('layouts.app')

{{--@if (session()->has('success-alert'))--}}

{{--<div class="alert alert-success alert-dismissible fade show top-5 left-2 z-index-3 text-right position-absolute"--}}
{{--role="alert">--}}
{{--<strong>  {{session()->get('success-alert')}}</strong>--}}
{{--<button type="button" class="close" data-dismiss="alert" aria-label="Close">--}}
{{--<span aria-hidden="true">&times;</span>--}}
{{--</button>--}}
{{--</div>--}}
{{--@php(session()->forget('order-completed'))--}}
{{--@endif--}}



@section('content')



    {{--{{Route::currentRouteName()=='panel.order'?'hi':'no'}}--}}
    @includeWhen(auth()->user()->role=='go','pages.panel.god-panel')
    {{--@includeWhen(auth()->user()->role=='ad','pages.panel.admin-panel')--}}
    @includeWhen(auth()->user()->role=='us','pages.panel.user-panel')




@endsection


@section('script')


    {{--<script type="text/javascript">--}}


    {{--$(document).ready(function () {--}}
    {{--console.log('hi');--}}
    {{--});--}}

    {{--</script>--}}

@endsection