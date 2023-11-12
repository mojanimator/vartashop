@if($section=='header')
    @if($userHasShop)
        <x-admin.order-panel :section="'header'" shopIds="{!! $shopIds !!}"></x-admin.order-panel>
    @else
        <x-user.order-panel :section="'header'"></x-user.order-panel>
    @endif
@endif
@if($section=='content')

    {{--@php($params=json_decode($params))--}}

    @if(str_contains( url()->full(),'my-orders/order-details?'))
        @if($userHasShop)
            <x-admin.order :section="'content'" params="{!! $params  !!}"></x-admin.order>
        @else
            <x-user.order :section="'content'" params="{!!  $params  !!}"></x-user.order>
        @endif
    @elseif(str_contains( url()->full(),'my-orders/search-orders?'))
        @if( $userHasShop)
            <x-admin.orders params="{!! $params  !!}" shopIds="{!! $shopIds !!}"></x-admin.orders>
        @else
            <x-user.orders params="{!!  $params  !!}"></x-user.orders>

        @endif

    @else
        @if($userHasShop)
            <x-admin.order-panel :section="'content'" shopIds="{!! $shopIds !!}"></x-admin.order-panel>
        @else
            <x-user.order-panel :section="'content'"></x-user.order-panel>
        @endif
    @endif

@endif