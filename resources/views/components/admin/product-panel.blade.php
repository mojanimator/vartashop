@if($section=='header')
    <li class="nav-item  ">
        <a class="nav-link hoverable-dark  mx-1 {{str_contains( url()->current(),'/my-products')?' active ':''}}"
           href="{{url('panel/my-products')}}">
            <div class="icon icon-shape   shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">

                <i class="fa fa-2x fa-gift {{str_contains( url()->current(),'/my-products')?' text-white ':' text-dark '}}    "
                   aria-hidden="true"></i>
            </div>
            <span class="nav-link-text me-1">محصولات من</span>

        </a>
    </li>

    @if( str_contains( url()->current(),'/my-products'))

        <li class="nav-item  my-0 ">
            <div class="navbar-nav p-0  ">

                <a class=" text-sm nav-link  hoverable-info py-2 {{str_contains( url()->full(),'my-products/create')?' active text-primary ':''}} "
                   href="{{url('panel/my-products/create')}}">
                    <i class="fa   fa-plus-circle text-success m-1" aria-hidden="true"></i>

                    محصول جدید

                </a>


            </div>
        </li>



    @endif
@endif


@if($section=='content')
    @php($shops=\App\Models\Shop::whereIn('id',json_decode($shopIds))->select('id','name')->get())

    @if(count($shops)>0)
        <div class="text-center font-weight-bold text-lg text-primary  col-12">
            محصولات من
        </div>
        <div class="  row  my-2  ">
            <div class="col-8"></div>
            <div class="col-sm-4        ">
                <a href="{{url('panel/my-products/create')}}" class="   ">
                    <div class="card move-on-hover bg-success">
                        <div class="card-body p-1 ">
                            <div class="row align-items-center ">
                                <div class="col-8">

                                    <h5 class="  mb-0 text-white font-weight-bold">
                                        محصول جدید
                                    </h5>

                                </div>
                                <div class="col-4 text-end">
                                    <div class="  ">
                                        <i class="fa fa-2x fa-plus-circle text-white m-1 "
                                           aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>



        <products-panel storage="{{asset('storage')}}"
                        images-link="{{route('product.images')}}"
                        groups-link="{{route('group.search')}}"
                        search-link="{{route('product.search')}}"
                        product-link="{{url('panel/my-products')}}"
                        create-link="{{route('product.create')}}"
                        edit-link="{{route('product.edit')}}"
                        shop-ids="{{$shops->toJson() }}"
        ></products-panel>
    @else
        <div class="text-center text-danger font-weight-bold"> شما هنوز فروشگاهی ندارید</div>
        <div class="col-md-6      ">
            <a href="{{url('panel/my-shops/create')}}" class="   ">
                <div class="card move-on-hover">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <h5 class="  mb-0 text-success font-weight-bold">
                                        فروشگاه جدید
                                    </h5>
                                    <h5 class=" text-sm text-black-50 font-weight-bolder mb-0">
                                        &nbsp

                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="  ">
                                    <i class="fa fa-3x fa-plus-circle text-success m-1"
                                       aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

    @endif





@endif




