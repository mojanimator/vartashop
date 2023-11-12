@if (auth()->user()->score < Helper::$create_product_score)
    <div class="  text-center text-lg text-primary font-weight-bold"> {{ 'برای ساخت محصول '.Helper::$create_product_score.' سکه نیاز است' }}</div>
    <div class="  text-center text-lg text-dark font-weight-bold"> {{ 'سکه های شما: '. auth()->user()->score }}</div>
    <div class="text-center     ">

        <ul class=" justify-content-center  nav pr-0  ">
            <li class="nav-item ">
                <a class="nav-link pe-1" href="https://www.instagram.com/varta.shop/" target="_blank"
                   data-bs-toggle="tooltip" data-bs-placement="bottom" title="اینستاگرام">
                    <i class="fab fa-instagram text-lg opacity-8 "></i>
                </a>
            </li>
            @php( $adminPhone = str_replace('09', '9', \Helper::$admin_phone))
            <li class="nav-item">
                <a class="nav-link pe-1 " href="https://wa.me/98{{$adminPhone}}" target="_blank"
                   data-bs-toggle="tooltip" data-bs-placement="bottom" title="واتساپ">
                    <i class="fab fa-whatsapp text-lg opacity-8 text-success"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link pe-1" href="https://t.me/develowper" target="_blank"
                   data-bs-toggle="tooltip" data-bs-placement="bottom" title="تلگرام">
                    <i class="fab fa-telegram text-lg opacity-8 text-info"></i>
                </a>
            </li>

            <li class="nav-item ">
                <a class="nav-link pe-1" href="https://www.youtube.com/channel/UCzwQ6GnoNQG1PwpqZhkIogA"
                   target="_blank" data-bs-toggle="tooltip" data-bs-placement="bottom" title="یوتیوب">
                    <i class="fab fa-youtube text-lg opacity-8 text-danger"></i>
                </a>
            </li>
        </ul>

    </div>

    <a class="nav-link  text-center font-weight-bold  "
       href="{{url('panel/my-products')}}"
    >
        بازگشت
    </a>

@else
    @php($shops=\App\Models\Shop::whereIn('id',json_decode($shopIds))->select('id','name')->get())
    @if(count($shops)>0)

        <img id="loading" src="{{asset('img/loading.gif')}}"
             class="d-none position-fixed z-index-3  left-0 right-0  mx-auto d-hi "
             width="200"
             alt="">

        <product-create

                img-limit="{{Helper::$product_image_limit}}"
                groups-link="{{route('group.search')}}"
                product-link="{{url('panel/my-products')}}"
                create-link="{{route('product.create')}}"
                shop-ids="{{$shops->toJson() }}"
        ></product-create>
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