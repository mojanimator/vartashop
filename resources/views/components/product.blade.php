<div class="m-card  d-flex align-items-start flex-column  " data-toggle="modal"
>
    <div class="m-card-header bg-transparent   ">

        <div class="icon-container d-inline-block" data-toggle="tooltip"
             data-placement="top"
             title="جنسیت">
            <div class=""><i class="fas  fa-lg fa-male   "></i></div>


        </div>
        <div class="header-left  d-inline-block float-left  ">
                    <span class="right-border px-2 float-left  badge-pill bg-primary   small-1" data-toggle="tooltip"
                          data-placement="top"
                          title="تعداد دانش آموز">

                    </span>

            <span class="  float-left px-1 text-white   small-1"
            >

                    </span>
            <span class="left-border px-2 float-left  badge-pill text-white   small-1 bg-light-red"
                  data-toggle="tooltip" data-placement="top" title="نوع فضا">

                    </span>
        </div>
        <img class="back-header-img" src="{{asset("img/card-header.png")}}" alt="">
        <img class="card-img" src="{{$img}}" alt="">


    </div>

    <!--<img v-else src="img/school-no.png" alt=""></div>-->
    <div class="m-card-body  px-2   flex-column align-self-stretch">

        <p class="text-purple mb-0 text-center"> {{$product->name}}</p>

        <div class="codes d-flex justify-content-center pt-1">
            <small class="  left-border badge-pill bg-gray text-white small d-inline-block "
            > کد مدرسه:
                <span>  </span>

            </small>
            <small class="  right-border badge-pill bg-dark-green text-white small d-inline-block "
            >کد فضا:
                <span>  </span>

            </small>
        </div>

        <div class="card-divider"></div>

        <p class="card-text text-dark-blue">
            <i class="fas  fa-arrow-circle-left"></i> تاسیس:
            <span>  </span>

        </p>
        <p class="card-text text-dark-blue">
            <i class="fas  fa-arrow-circle-left"></i>حوزه نمایندگی:
            <span> </span>

        </p>

        <p class="card-text text-dark-blue p-type">
            <i class="fas  fa-arrow-circle-left"></i>

        </p>


        <div class="card-divider"></div>

        <div class="doore">
                        <span
                                class="card-text badge-pill bg-purple text-white px-2  mx-1 d-inline-block"> </span>
        </div>

        <p
                class="vaziat card-text badge-pill text-dark-blue text-center  mt-2 "
                @click.stop="$root.$emit('dropdownResponse',{'ids':getType(s, 'zamime_ids')})">
            <i class="fas  fa-eye "></i>


        </p>


    </div>
    <div class="m-card-footer  bg-transparent      ">
        <img class="mb-auto  back-footer-img" src="{{asset("img/card-footer.png")}}" alt="">
    </div>

</div>
