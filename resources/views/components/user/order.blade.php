@php
    use Illuminate\Support\Facades\URL;$params=json_decode($params);
        $order=\App\Models\Order::where('id',$params->order_id)->where('user_id',auth()->user()->id)->with('shop:id,name,channel_address')->with('province:id,name')->with('county:id,name')->with('products')->first();

if (!$order){

 header("Location: " . URL::to('/panel/my-orders'), true, 302);
    exit();
    }
$order->createdat=\Morilog\Jalali\Jalalian::fromDateTime($order->created_at)->format('%A, %d %B %Y ⏰ H:i');
$order->statustext=orderNameFromId($order->status,false);
@endphp

<img id="loading" src="{{asset('img/loading.gif')}}"
     class="d-none position-fixed z-index-3  left-0 right-0  mx-auto d-hi "
     width="200"
     alt="">

<order-user order="{{    $order    }}" delete-order-link="{{route('order.delete')}}"
            shop-link="{{route('shop',['id'=>$order->shop->id??'','name'=>$order->shop->name??'']) }}"
            product-link="{{url('product')}}"
            provinces="{{\App\Models\Province::all()}}"
            counties="{{\App\Models\County::all()}}"
            search-link="{{route('product.search')}}"
            edit-link="{{route('order.edit')}}"
            editable="{{$order->status<3  }}"
></order-user>


@section('script')

@endsection