@if (auth()->user()->score < Helper::$create_shop_score)
    <div class="  text-center text-lg text-primary font-weight-bold"> {{ 'برای ساخت فروشگاه '.Helper::$create_shop_score.' سکه نیاز است' }}</div>
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
       href="{{url('panel/my-shops')}}"
    >
        بازگشت
    </a>

@else
    <img id="loading" src="{{asset('img/loading.gif')}}"
         class="d-none position-fixed z-index-3  left-0 right-0  mx-auto d-hi "
         width="200"
         alt="">
    <form class=" row justify-content-center  form-group   " id="form-create-shop" method="POST"
          action="{{ route('shop.create') }}">
        @csrf
        <div class="col-md-12">
            <div class="card  ">
                <div class="card-header text-center text-lg text-primary font-weight-bold">ساخت فروشگاه جدید</div>

                <image-uploader class="col-sm-6 mx-auto" id="img" label="تصویر فروشگاه"
                                for-id="img"
                                link="null"
                                preload="null"
                                height="10rem" mode="create">

                </image-uploader>
                @error('img')
                <span class="col-12 small  text-danger text-center" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
                <div class="card-body text-right row col-12">


                    <div id="form-name" class="form-group  col-sm-6  ">


                        <label for="name-input"
                               class="  col-form-label text-right"> نام فروشگاه </label>


                        <div class="align-items-stretch flex-row d-flex  ">
                            <input id="name-input" type="text"
                                   class="border  px-4 form-control{{ $errors->has('name')  ? ' is-invalid' : '' }}"
                                   name="name"
                                   {{-- ?:  returning the first non-false value within a group of expressions--}}
                                   value="{{ old('name' ) }}"
                                   autocomplete="name"
                                   autofocus>

                        </div>
                        @error('name')
                        <span class="col-12 small  text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                            {{--<strong>{{ $errors->first('telegram_username') ?: $errors->first('email') }}</strong>--}}
                                    </span>
                        @enderror


                    </div>
                    <div id="form-group_id" class="form-group    col-sm-6   ">


                        <label for="group_id-input"
                               class="col-12 col-form-label text-right">دسته بندی</label>
                        <div class="   input-group">
                            <select id="group_id-input " name="group_id"
                                    class="px-4 py-1 form-control{{ $errors->has('group_id')  ? ' is-invalid' : '' }}">
                                {{--<option value="0">انتخاب دسته</option>--}}
                                @foreach(\App\Models\Group::where('level',0)->get() as $p)
                                    <option value="{{$p->id}}"
                                            {{ old('group_id')==$p->id? ' selected ':''}} >{{$p->name}}</option>

                                @endforeach
                            </select>


                        </div>

                        @error('group_id')
                        <span class="small  text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                    <div id="form-description" class="form-group  col-12 ">


                        <label for="description-input"
                               class="  col-form-label text-right"> توضیحات </label>


                        <div class="align-items-stretch flex-row d-flex input-group">
                                <textarea id="description-input" type="text" rows="3"
                                          class="border  px-4 form-control{{ $errors->has('description')  ? ' is-invalid' : '' }}"
                                          name="description"
                                          {{-- ?:  returning the first non-false value within a group of expressions--}}

                                          autocomplete="description"
                                          autofocus>{{ old('description')  }}</textarea>

                            @error('description')
                            <span class="col-12 small  text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                {{--<strong>{{ $errors->first('telegram_username') ?: $errors->first('email') }}</strong>--}}
                                    </span>
                            @enderror
                        </div>

                    </div>


                    <div class="col-sm-6 my-1">
                        <label for="province-input"
                               class="col-12 col-form-label text-right">استان</label>
                        <select id="province-input" name="province_id" onchange="setCountyOptions(this.value)"
                                class="px-4 form-control{{ $errors->has('province_id')  ? ' is-invalid' : '' }}">
                            <option value="0">انتخاب استان</option>
                            @foreach(\Illuminate\Support\Facades\DB::table('province')->get() as $p)
                                <option value="{{$p->id}}"
                                        {{ old('province_id')==$p->id? ' selected ':''}} >{{$p->name}}</option>

                            @endforeach
                        </select>

                        @error('province_id')
                        <span class="small  text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror

                    </div>
                    <div class="col-sm-6 my-1">
                        <label for="county-input"
                               class="col-12 col-form-label text-right">شهر </label>
                        <select id="county-input" name="county_id"
                                class="px-4 form-control{{ $errors->has('county_id')  ? ' is-invalid' : '' }}">
                            @if(  $cId=\App\Models\County::find(old('county_id') ))

                                <option value="{{$cId->id}}" selected>{{$cId->name}}</option>
                            @else
                                <option value="0">انتخاب شهر</option>
                            @endif
                        </select>

                        @error('county_id')
                        <span class="small  text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror

                    </div>


                    <div id="form-postal_code" class="form-group  col-sm-6  ">


                        <label for="postal_code-input"
                               class="  col-form-label text-right"> کد پستی </label>

                        <div class="   ">
                            <div class="align-items-stretch flex-row d-flex input-group">
                                <input id="postal_code-input" type="text"
                                       class="border  px-4 form-control{{ $errors->has('postal_code')  ? ' is-invalid' : '' }}"
                                       name="postal_code"
                                       {{-- ?:  returning the first non-false value within a group of expressions--}}
                                       value="{{ old('postal_code')  }}"
                                       autocomplete="postal_code"
                                       autofocus>

                                @error('postal_code')
                                <span class="col-12 small  text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    {{--<strong>{{ $errors->first('telegram_username') ?: $errors->first('email') }}</strong>--}}
                                    </span>
                                @enderror
                            </div>


                        </div>
                    </div>
                    <div id="form-channel_username" class="form-group  col-sm-6  ">


                        <label for="channel_username-input"
                               class="  col-form-label text-right"> کانال تلگرام </label>


                        <div class="align-items-stretch flex-row d-flex input-group">
                            <input id="channel_username-input" type="text"
                                   class="border  px-4 form-control{{ $errors->has('channel_username')  ? ' is-invalid' : '' }}"
                                   name="channel_username" placeholder="نام کانال (مثال: @vartashop )"
                                   {{-- ?:  returning the first non-false value within a group of expressions--}}
                                   value="{{ old('channel_username') }}"
                                   autocomplete="channel_username"
                                   autofocus>

                        </div>
                        @error('channel_username')
                        <span class="col-12 small  text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                            {{--<strong>{{ $errors->first('telegram_username') ?: $errors->first('email') }}</strong>--}}
                                    </span>
                        @enderror


                    </div>


                    <div id="form-address" class="form-group  col-12  ">

                        <label for="address-input"
                               class="  col-form-label text-right"> آدرس </label>


                        <div class="align-items-stretch flex-row d-flex input-group">
                                <textarea id="address-input" type="text" rows="3"
                                          class="border  px-4 form-control{{ $errors->has('address')  ? ' is-invalid' : '' }}"
                                          name="address"
                                          {{-- ?:  returning the first non-false value within a group of expressions--}}

                                          autocomplete="address"
                                          autofocus>{{ old('address')  }}</textarea>

                            @error('address')
                            <span class="col-12 small  text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                {{--<strong>{{ $errors->first('telegram_username') ?: $errors->first('email') }}</strong>--}}
                                    </span>
                            @enderror
                        </div>


                    </div>


                </div>
                <div class="btn btn-block bg-gradient-success col-8 mx-auto text-lg" onclick="event.preventDefault();
    document.getElementById('form-create-shop').submit();">ساخت فروشگاه
                </div>
            </div>
        </div>


    </form>


@endif





@section('script')


    <script type="text/javascript">


                @php( $counties=\Illuminate\Support\Facades\DB::table('county')->get())


        let counties = @json($counties);


        function setCountyOptions(selValue) {
            let sel2 = $('#county-input');
            sel2.empty();
            for (let i = 0; i < counties.length; i++) {
                if (counties[i].province_id == selValue) {

                    sel2.append(`<option value='${counties[i].id}' pvalue='${counties[i].province_id}'>${counties[i].name}</option>`);
                }
            }
//            console.log(sel2);

        }

        image = document.getElementById('img');


    </script>


@endsection




