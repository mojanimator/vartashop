@php( $params=json_decode($params))


@php( $shop=\App\Models\Shop::where('id',$params->id)->whereIn('id',json_decode($shopIds))->first())
@if($shop)


    <img id="loading" src="{{asset('img/loading.gif')}}"
         class="d-none position-fixed z-index-3  left-0 right-0  mx-auto d-hi "
         width="200"
         alt="">
    <section class=" row justify-content-center     ">

        <div class="col-md-12">
            <div class="card  ">
                <div class="card-header text-center text-lg text-primary font-weight-bold">{{' اطلاعات '.$shop->name}}</div>

                <image-uploader class="col-sm-6 mx-auto    " id="form-img" label="تصویر فروشگاه"
                                for-id="{{$shop->id}}"
                                link="{{ route('shop.edit')}}"
                                preload="{{asset('storage/shops/'.$shop->id.'.jpg')}}"
                                height="10rem" mode="edit">

                </image-uploader>
                <div class="card-body text-right row col-12">


                    <form id="form-name" class="form-group  col-sm-6  " method="POST" action="{{ route('shop.edit') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{$shop->id}}">
                        <label for="name-input"
                               class="  col-form-label text-right"> نام </label>

                        <div class="   ">
                            <div class="align-items-stretch flex-row d-flex input-group">
                                <input id="name-input" type="text"
                                       class="border  px-4 form-control{{ $errors->has('name')  ? ' is-invalid' : '' }}"
                                       name="name"
                                       {{-- ?:  returning the first non-false value within a group of expressions--}}
                                       value="{{ $shop->name  }}"
                                       autocomplete="name"
                                       autofocus>
                                <span class="  bg-primary  border   text-white px-2 hoverable-primary"
                                      onclick=" document.getElementById('form-name').submit() ">
                                  ویرایش
                                </span>
                                @error('name')
                                <span class="col-12 small  text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    {{--<strong>{{ $errors->first('telegram_username') ?: $errors->first('email') }}</strong>--}}
                                    </span>
                                @enderror
                            </div>


                        </div>
                    </form>
                    <form id="form-group_id" class="form-group    col-sm-6   " method="POST"
                          action="{{ route('shop.edit') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{$shop->id}}">

                        <label for="group_id-input"
                               class="col-12 col-form-label text-right">دسته بندی</label>
                        <div class="   input-group">
                            <select id="group_id-input " name="group_id"
                                    class="px-4 py-1 form-control{{ $errors->has('group_id')  ? ' is-invalid' : '' }}">
                                <option value="0">انتخاب دسته</option>
                                @foreach(\App\Models\Group::where('level',0)->get() as $p)
                                    <option value="{{$p->id}}"
                                            {{ $shop->group_id==$p->id? ' selected ':''}} >{{$p->name}}</option>

                                @endforeach
                            </select>

                            <div class="   bg-primary    text-center  text-white px-2    hoverable-primary  "
                                 onclick=" document.getElementById('form-group_id').submit() ">
                                ویرایش
                            </div>
                        </div>


                        @error('group_id')
                        <span class="small  text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </form>
                    <form id="form-description" class="form-group  col-12  " method="POST"
                          action="{{ route('shop.edit') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{$shop->id}}">

                        <label for="description-input"
                               class="  col-form-label text-right"> توضیحات </label>

                        <div class="   ">
                            <div class="align-items-stretch flex-row d-flex input-group">
                                <textarea id="description-input" type="text" rows="3"
                                          class="border  px-4 form-control{{ $errors->has('description')  ? ' is-invalid' : '' }}"
                                          name="description"
                                          {{-- ?:  returning the first non-false value within a group of expressions--}}

                                          autocomplete="description"
                                          autofocus>{{ $shop->description  }}</textarea>
                                <span class="  bg-primary  border   text-white px-2 hoverable-primary"
                                      onclick=" document.getElementById('form-description').submit() ">
                                  ویرایش
                                </span>
                                @error('description')
                                <span class="col-12 small  text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    {{--<strong>{{ $errors->first('telegram_username') ?: $errors->first('email') }}</strong>--}}
                                    </span>
                                @enderror
                            </div>


                        </div>
                    </form>

                    <form id="form-pc" class="form-group  row col-12 align-items-center  " method="POST"
                          action="{{ route('shop.edit') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{$shop->id}}">

                        <div class="col-sm-5 my-1">
                            <label for="province-input"
                                   class="col-12 col-form-label text-right">استان</label>
                            <select id="province-input" name="province_id" onchange="setCountyOptions(this.value)"
                                    class="px-4 form-control{{ $errors->has('province_id')  ? ' is-invalid' : '' }}">
                                <option value="0">انتخاب استان</option>
                                @foreach(\Illuminate\Support\Facades\DB::table('province')->get() as $p)
                                    <option value="{{$p->id}}"
                                            {{ $shop->province_id==$p->id? ' selected ':''}} >{{$p->name}}</option>

                                @endforeach
                            </select>

                            @error('province_id')
                            <span class="small  text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror

                        </div>
                        <div class="col-sm-5 my-1">
                            <label for="county-input"
                                   class="col-12 col-form-label text-right">شهر </label>
                            <select id="county-input" name="county_id"
                                    class="px-4 form-control{{ $errors->has('county_id')  ? ' is-invalid' : '' }}">
                                @if(  $cId=\App\Models\County::find($shop->county_id ))

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
                        <div class="col-sm-2  bg-primary rounded-lg   text-center  text-white p-2 mt-5  hoverable-primary align-self-baseline"
                             onclick=" document.getElementById('form-pc').submit() ">
                            ویرایش
                        </div>
                    </form>

                    <form id="form-postal_code" class="form-group  col-sm-6  " method="POST"
                          action="{{ route('shop.edit') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{$shop->id}}">

                        <label for="postal_code-input"
                               class="  col-form-label text-right"> کد پستی </label>

                        <div class="   ">
                            <div class="align-items-stretch flex-row d-flex input-group">
                                <input id="postal_code-input" type="text"
                                       class="border  px-4 form-control{{ $errors->has('postal_code')  ? ' is-invalid' : '' }}"
                                       name="postal_code"
                                       {{-- ?:  returning the first non-false value within a group of expressions--}}
                                       value="{{ $shop->postal_code  }}"
                                       autocomplete="postal_code"
                                       autofocus>
                                <span class="  bg-primary  border   text-white px-2 hoverable-primary"
                                      onclick=" document.getElementById('form-postal_code').submit() ">
                                  ویرایش
                                </span>
                                @error('postal_code')
                                <span class="col-12 small  text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    {{--<strong>{{ $errors->first('telegram_username') ?: $errors->first('email') }}</strong>--}}
                                    </span>
                                @enderror
                            </div>


                        </div>
                    </form>
                    <form id="form-channel_username" class="form-group  col-sm-6  " method="POST"
                          action="{{ route('shop.edit') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{$shop->id}}">
                        <input type="hidden" name="cmnd" value="channel">

                        <label for="channel_username-input"
                               class="  col-form-label text-right"> کانال تلگرام </label>

                        <div class="   ">
                            <div class="align-items-stretch flex-row d-flex input-group">
                                <input id="channel_username-input" type="text"
                                       class="border  px-4 form-control{{ $errors->has('channel_username')  ? ' is-invalid' : '' }}"
                                       name="channel_username" placeholder="نام کانال (مثال: @vartashop )"
                                       {{-- ?:  returning the first non-false value within a group of expressions--}}
                                       value="{{ \App\Models\Channel::where('shop_id',$shop->id)->first()->chat_username ??'' }}"
                                       autocomplete="channel_username"
                                       autofocus>
                                <span class="  bg-primary  border   text-white px-2 hoverable-primary"
                                      onclick=" document.getElementById('form-channel_username').submit() ">
                                  ویرایش
                                </span>
                                @error('channel_username')
                                <span class="small  text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    {{--<strong>{{ $errors->first('telegram_username') ?: $errors->first('email') }}</strong>--}}
                                    </span>
                                @enderror
                            </div>


                        </div>
                    </form>

                    <form id="form-address" class="form-group  col-12  " method="POST"
                          action="{{ route('shop.edit') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{$shop->id}}">

                        <label for="address-input"
                               class="  col-form-label text-right"> آدرس </label>

                        <div class="   ">
                            <div class="align-items-stretch flex-row d-flex input-group">
                                <textarea id="address-input" type="text" rows="3"
                                          class="border  px-4 form-control{{ $errors->has('address')  ? ' is-invalid' : '' }}"
                                          name="address"
                                          {{-- ?:  returning the first non-false value within a group of expressions--}}

                                          autocomplete="address"
                                          autofocus>{{ $shop->address  }}</textarea>
                                <span class="  bg-primary  border   text-white px-2 hoverable-primary  "
                                      onclick=" document.getElementById('form-address').submit() ">
                                  ویرایش
                                </span>
                                @error('address')
                                <span class="col-12 small  text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    {{--<strong>{{ $errors->first('telegram_username') ?: $errors->first('email') }}</strong>--}}
                                    </span>
                                @enderror
                            </div>


                        </div>
                    </form>


                </div>
            </div>
        </div>


    </section>
@else
    <div class="card-header text-center text-lg text-primary font-weight-bold">فروشگاه یافت نشد!</div>
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




