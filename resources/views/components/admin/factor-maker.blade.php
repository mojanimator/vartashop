<img id="loading" src="{{asset('img/loading.gif')}}"
     class="d-none position-fixed z-index-3  left-0 right-0  mx-auto d-hi "
     width="200"
     alt="">


<factor-maker

        shops="{{\App\Models\Shop::whereIn('id',json_decode($shopIds))->with('province')->with('county')->get()}}"
        product-link="{{url('product')}}"
        provinces="{{\App\Models\Province::all()}}"
        counties="{{\App\Models\County::all()}}"
        search-link="{{route('product.search')}}"
        factor-link="{{route('factor.create')}}"

>

</factor-maker>

