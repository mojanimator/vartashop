<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }} " dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="RLPFP2ey8-bMtn1JIqJHxMQCRyTNYmLEmhha4V5rBVU">


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
{{--<script src="{{ asset('js/app.js') }}" defer></script>--}}

<!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">


    <!-- Styles -->
    {{--<link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
    <script>
        const app = new Vue({
            el: '#app',
            mode: 'production',

            components: {

                tgju,
            }
        });
    </script>
</head>
<body>


<div id="app">

    <tgju></tgju>

</div>


</body>
</html>
