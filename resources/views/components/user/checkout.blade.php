<section class=" row justify-content-center     ">

{{dd(\App\Models\Cart::get())}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-header text-center text-lg text-primary font-weight-bold">ثبت اطلاعات پستی</div>

            <div class="card-body text-right">
                <form method="POST" action="{{ route('order.create') }}">
                    @csrf

                    <div class="form-group row  ">
                        <label for="name-input"
                               class="col-12 col-form-label text-right"> نام دریافت کننده</label>

                        <div class="col-12">
                            <input id="name-input" type="text"
                                   class="px-4 form-control{{ $errors->has('name')  ? ' is-invalid' : '' }}"
                                   name="name"
                                   {{-- ?:  returning the first non-false value within a group of expressions--}}
                                   value="{{  old('name')   }}"
                                   autocomplete="name"
                                   autofocus>

                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                {{--<strong>{{ $errors->first('telegram_username') ?: $errors->first('email') }}</strong>--}}
                                    </span>
                            @enderror

                        </div>
                    </div>
                    <div class="form-group row  ">
                        <div class="col-sm-6">
                            <label for="name-input"
                                   class="col-12 col-form-label text-right"> تلفن دریافت کننده</label>
                            <input id="phone-input" type="text"
                                   class="px-4 form-control{{ $errors->has('phone')  ? ' is-invalid' : '' }}"
                                   name="phone"
                                   value="{{   old('phone')   }}"
                                   autocomplete="phone"
                                   autofocus>

                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror

                        </div>
                        <div class="col-sm-6">
                            <label for="code-input"
                                   class="col-12 col-form-label text-right"> کد پستی</label>
                            <input id="code-input" type="text"
                                   class="px-4 form-control{{ $errors->has('postal_code')  ? ' is-invalid' : '' }}"
                                   name="postal_code"
                                   value="{{  old('postal_code')   }}"
                                   autocomplete="postal_code"
                                   autofocus>

                            @error('postal_code')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror

                        </div>
                    </div>
                    <div class="form-group row  ">


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
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror

                        </div>
                        <div class="col-sm-6 my-1">
                            <label for="county-input"
                                   class="col-12 col-form-label text-right">شهر </label>
                            <select id="county-input" name="county_id"
                                    class="px-4 form-control{{ $errors->has('county_id')  ? ' is-invalid' : '' }}">
                                @if(old('county_id') && $cId=\App\Models\County::find(old('county_id') ))

                                    <option value="{{$cId->id}}" selected>{{$cId->name}}</option>
                                @else
                                    <option value="0">انتخاب شهر</option>
                                @endif
                            </select>

                            @error('county_id')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror

                        </div>

                    </div>
                    <div class="form-group row  ">
                        <label for="address-input"
                               class="col-12 col-form-label text-right">آدرس</label>

                        <div class="col-12">
                            <textarea id="address-input" type="text" rows="5"
                                      class="px-4 form-control{{ $errors->has('address')  ? ' is-invalid' : '' }}"
                                      name="address"
                                      autocomplete="address"
                                      autofocus>{{ old('address') }}
                            </textarea>
                            @error('address')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror

                        </div>
                    </div>
                    {{--<div class="form-group row  ">--}}
                    {{--<label for="desc-input"--}}
                    {{--class="col-12 col-form-label text-right">توضیحات</label>--}}

                    {{--<div class="col-12">--}}
                    {{--<textarea id="desc-input" type="text" rows="3"--}}
                    {{--placeholder="در صورت نیاز به توضیحات برای فروشنده..."--}}
                    {{--class="px-4 form-control{{ $errors->has('description')  ? ' is-invalid' : '' }}"--}}
                    {{--name="description"--}}
                    {{--autocomplete="address"--}}
                    {{--autofocus>{{ old('description') }}--}}
                    {{--</textarea>--}}
                    {{--@error('description')--}}
                    {{--<span class="invalid-feedback" role="alert">--}}
                    {{--<strong>{{ $message }}</strong>--}}
                    {{--</span>--}}
                    {{--@enderror--}}

                    {{--</div>--}}
                    {{--</div>--}}
                    <div class="form-group row mb-0">
                        <div class="col-md-8  mx-auto">
                            <button type="submit" class="btn bg-gradient-primary btn-block  ">
                                ثبت سفارش
                            </button>
                            @error('cart')
                            <div class="  alert   text-center ">
                                <strong class="text-danger">{{ $message }}</strong>
                                {{--<strong>{{ $errors->first('telegram_username') ?: $errors->first('email') }}</strong>--}}
                            </div>
                            @enderror
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>


</section>

@section('script')

    <script type="text/javascript">


        @php
            $counties=\Illuminate\Support\Facades\DB::table('county')->get();
        @endphp
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

    </script>

@endsection
