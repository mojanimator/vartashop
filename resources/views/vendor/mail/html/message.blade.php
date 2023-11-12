@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} @lang('تمامی حقوق برای') {{ config('app.name') }}. @lang('محفوظ است')
            <br>@lang(':طراحی توسط')
            <br><a href="https://zil.ink/varta">@lang('ورتا استودیو')</a>

        @endcomponent
    @endslot
@endcomponent
