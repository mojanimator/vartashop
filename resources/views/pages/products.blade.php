@extends('layouts.app')

@section('content')
    @php($params=request()->all())
    <section>
        <search-page
                :params="{{json_encode( request()->all()) }}"
                data-link="{{route('product.search')}}"
                group-link="{{route('group.search')}}"
                root-link="{{route('/')}}"
                img-link="{{asset("storage/products/")}}"
                cart-link="{{route('cart.edit')}}"
                asset-link="{{asset("img/")}}"
                {{--:params="{{json_encode([ 'order_by_raw'=>'1-((price-discount_price)/(price)) desc'  ])}}"--}}
        ></search-page>
    </section>
@endsection