{{--@section('content')--}}
@php
    if (! auth()->user()){

            header("Location: " . route('/'), true, 302);
               exit();
               }

@endphp
{{--{{Route::currentRouteName()=='panel.order'?'hi':'no'}}--}}
<div class="  bg-gray-100 mt-n7">
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl   fixed-right   bg-gradient-light shadow-lg"
           id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute left-0 top-0 d-none d-xl-none"
               aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0 text-right    bg-gradient-info text-white  "
               href="{{route('panel.view')}}">
                <span class="mx-1 font-weight-bold mb-1">پنل کاربری</span>
                <div class="d-flex flex-row">
                    <i class="fa fa-user-circle  " aria-hidden="true"></i>
                    <span class="align-self-center mx-2">{{auth()->user()->username}}</span>
                </div>
                <div class="d-flex flex-row my-1">
                    <i class="fa fa-money-bill-wave  " aria-hidden="true"></i>
                    <span class="align-self-center mx-2">{{auth()->user()->score}}</span>
                </div>
            </a>
        </div>
        {{--<hr class="horizontal bg-primary    mt-0">--}}
        <div class="collapse d-flex p-0 mt-5 w-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav p-0 pt-4 ">


                @if(str_contains( url()->current(),'panel'))

                    <x-panel :section="'header'"></x-panel>


                @endif


                <li class="nav-item  ">
                    <a class="nav-link hoverable-dark  mx-1 {{str_contains( url()->current(),'/panel/user-settings')?' active  text-primary':''}}"
                       href="{{url('/panel/user-settings')}}">
                        <div class="icon icon-shape   shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">
                            <svg width="20px" height="20px" viewBox="-2.5 -2.5 30 30" version="1.1"
                                 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>حساب کاربری</title>
                                <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none"
                                   fill-rule="evenodd">
                                    <g id="Rounded-Icons"
                                       fill="#FFFFFF" fill-rule="nonzero">
                                        <g id="Icons-with-opacity">
                                            <g id="settings">
                                                <path id="Path" class="color-background"
                                                      xmlns="http://www.w3.org/2000/svg"
                                                      d="M24 13.616v-3.232c-1.651-.587-2.694-.752-3.219-2.019v-.001c-.527-1.271.1-2.134.847-3.707l-2.285-2.285c-1.561.742-2.433 1.375-3.707.847h-.001c-1.269-.526-1.435-1.576-2.019-3.219h-3.232c-.582 1.635-.749 2.692-2.019 3.219h-.001c-1.271.528-2.132-.098-3.707-.847l-2.285 2.285c.745 1.568 1.375 2.434.847 3.707-.527 1.271-1.584 1.438-3.219 2.02v3.232c1.632.58 2.692.749 3.219 2.019.53 1.282-.114 2.166-.847 3.707l2.285 2.286c1.562-.743 2.434-1.375 3.707-.847h.001c1.27.526 1.436 1.579 2.019 3.219h3.232c.582-1.636.75-2.69 2.027-3.222h.001c1.262-.524 2.12.101 3.698.851l2.285-2.286c-.744-1.563-1.375-2.433-.848-3.706.527-1.271 1.588-1.44 3.221-2.021zm-12 2.384c-2.209 0-4-1.791-4-4s1.791-4 4-4 4 1.791 4 4-1.791 4-4 4z"></path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="nav-link-text me-1">حساب کاربری</span>

                    </a>
                </li>
                @if(str_contains( url()->current(),'/panel/settings'))

                @endif

                <li class="nav-item  ">
                    <a class="nav-link hoverable-dark mx-1  "
                       href="{{ route('logout') }}"
                       onclick="event.preventDefault();
    document.getElementById('logout-form').submit();">
                        <div class="icon icon-shape   shadow border-radius-md bg-white text-center ms-2 d-flex align-items-center justify-content-center">

                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                 version="1.1" id="Capa_1" x="0px" y="0px" width="20px" height="20px"
                                 viewBox="0 0 471.851 471.851"
                                 xml:space="preserve">

    <g>
        <path class="color-background"
              d="M340.109,355.875c-5.707,0-11.023,2.153-14.578,5.926c-3.438,3.646-5.094,8.516-4.764,14.062    c1.087,18.758,1.366,37.374,1.438,54.903c-24.923,0.365-53.802,0.558-83.906,0.558c-56.414,0.006-107.818-0.665-145.585-1.878    c-0.317-75.389-2.133-151.893-3.89-225.927c-1.246-52.654-2.541-107.049-3.285-160.649c29.66-1.623,68.789-2.381,122.435-2.381    c26.509,0,52.722,0.183,76.279,0.348c9.282,0.068,18.159,0.124,26.481,0.178c0.544,11.656,1.468,23.237,2.519,35.878    c0.036,0.421,0.102,0.815,0.193,1.3c-0.137,0.937-0.208,1.871-0.208,2.798v12.022c0,11.154,9.074,20.225,20.23,20.225    s20.23-9.071,20.23-20.225V80.989c0-0.317-0.021-0.63-0.061-0.932c0.137-1.34,0.152-2.656,0.04-4.009    c-1.411-16.955-2.874-34.489-2.985-52.206c-0.03-4.522-1.407-8.653-3.977-11.989c-3.184-7.021-9.76-11.192-17.742-11.212    c-15.335-0.031-32.275-0.15-50.16-0.287C255.363,0.183,230.286,0,205.056,0C143.074,0,98.469,1.166,64.68,3.662    c-6.807,0.505-12.454,3.89-15.942,9.551c-2.61,3.385-3.963,7.607-3.905,12.226c0.686,59.694,2.143,120.355,3.552,179.026    c1.902,79.232,3.867,161.16,3.966,241.737c0.013,8.196,4.296,14.817,11.535,17.936c3.468,3.271,7.939,5.093,13.004,5.281    c41.172,1.569,97.814,2.432,159.484,2.432c37.234,0,74.959-0.319,106.219-0.919c8.709-0.162,15.757-5.312,18.474-13.456    c1.102-2.514,1.655-5.302,1.655-8.277c-0.005-26.329-0.116-50.069-1.508-73.945C360.462,362.527,350.032,355.875,340.109,355.875z    "></path>
        <path class="color-background"
              d="M406.383,142.679h-117.84c-0.152-16.618-0.645-33.215-2.356-49.777c-0.091-0.942-0.33-1.78-0.533-2.643    c-0.797-14.117-18.54-26.015-30.554-12.659c-41.36,45.956-82.726,91.911-124.083,137.867c-7,3.146-12.299,10.836-11.832,18.943    c-0.467,8.104,4.832,15.797,11.832,18.94c41.357,45.956,82.723,91.911,124.083,137.872c12.014,13.351,29.757,1.447,30.554-12.659    c0.203-0.863,0.442-1.706,0.533-2.646c1.712-16.56,2.204-33.159,2.356-49.779h117.84c8.805,0,14.31-5.113,16.508-11.518    c2.504-2.858,4.129-6.672,4.129-11.552v-62.048v-13.23v-62.045c0-4.888-1.625-8.694-4.124-11.547    C420.692,147.797,415.188,142.679,406.383,142.679z M390.6,227.796v13.226v48.697H275.264c-1.721,0-3.265,0.244-4.737,0.6    c-9.146-0.051-18.332,5.814-18.337,17.61c0,8.49-0.056,16.98-0.198,25.477c-29.693-33.002-59.389-65.999-89.09-98.995    c29.696-33,59.392-65.996,89.09-98.995c0.138,8.487,0.198,16.978,0.198,25.479c0,11.793,9.191,17.661,18.337,17.608    c1.468,0.358,3.017,0.602,4.737,0.602H390.6V227.796z"></path>
    </g>


</svg>
                        </div>
                        <span class="nav-link-text me-1">خروج</span>

                    </a>
                    <form id="logout-form" action="{{ route('logout') }}"
                          method="POST"
                          class="d-none">
                        @csrf
                    </form>
                </li>
                @if(str_contains( url()->current(),'/panel/user-settings'))

                @endif





                {{--************************************************************                --}}

            </ul>
        </div>
        {{--<div class="sidenav-footer mx-3 mt-3 pt-3">--}}
        {{--<div class="card card-background shadow-none card-background-mask-secondary" id="sidenavCard">--}}
        {{--<div class="full-background"--}}
        {{--style="background-image: url({{asset('img/curved-images/curved5-small.jpg')}} )"></div>--}}
        {{--<div class="card-body text-left p-3 w-100">--}}
        {{--<div class="icon icon-shape   bg-white shadow text-center mb-1 d-flex align-items-center justify-content-center border-radius-md">--}}
        {{--<i class="ni ni-diamond text-dark text-gradient text-lg top-0" aria-hidden="true"--}}
        {{--id="sidenavCardIcon"></i>--}}

        {{--</div>--}}
        {{--<h5 class="text-white up mb-1 text-right">پشتیبانی</h5>--}}
        {{--<p class="text-xs font-weight-bold text-right">سوالی دارید؟</p>--}}
        {{--<a href="https://zil.ink/varta"--}}
        {{--target="_blank" class="btn btn-white btn-sm w-100 mb-0">ارتباط با ما</a>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
    </aside>
    <main class="main-content mt-1   border-radius-lg">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0    shadow-none border-radius-xl" id="navbarBlur"
             navbar-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 ">
                        @php($linkMap=explode("/",url()->current()))
                        @foreach($linkMap as $idx=>$item)
                            @continue($idx<3)
                            <li class="breadcrumb-item text-sm ps-2">
                                <a class="opacity-5 text-dark"
                                   href="{{ explode($item,url()->current())[0].$item}}">
                                    {{__($item)}}
                                </a>
                            </li>
                        @endforeach
                    </ol>
                    <h6 class="font-weight-bolder mb-0">   {{__($item)}}</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 px-0" id="navbar">
                    {{--<div class="ms-md-auto pe-md-3 d-flex align-items-center">--}}
                    {{--<div class="input-group">--}}
                    {{--<span class="input-group-text text-body"><i class="fas fa-search"--}}
                    {{--aria-hidden="true"></i></span>--}}
                    {{--<input type="text" class="form-control" placeholder="أكتب هنا...">--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    <ul class="navbar-nav me-auto ms-0 justify-content-end">
                        <li class="nav-item d-xl-none px-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item px-3  align-items-center">
                            <a href="{{url('panel/settings')}}" class="nav-link text-body font-weight-bold px-0">
                                <span class="d-sm-inline d-none"> {{auth()->user()->username??auth()->user()->name}}</span>
                                <i class="fa fa-user me-sm-1"></i>
                            </a>
                        </li>
                        <li class="nav-item px-3 align-items-center">
                            <a href="{{route('/')}}" class="nav-link text-body font-weight-bold px-0">

                                <span class="d-sm-inline "> صفحه اصلی</span>
                            </a>
                        </li>

                        {{--<li class="nav-item dropdown ps-2 d-flex align-items-center">--}}
                        {{--<a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton"--}}
                        {{--data-bs-toggle="dropdown" aria-expanded="false">--}}
                        {{--<i class="fa fa-bell cursor-pointer"></i>--}}
                        {{--</a>--}}
                        {{--<ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4"--}}
                        {{--aria-labelledby="dropdownMenuButton">--}}
                        {{--<li class="mb-2">--}}
                        {{--<a class="dropdown-item border-radius-md" href="javascript:;">--}}
                        {{--<div class="d-flex py-1">--}}
                        {{--<div class="my-auto">--}}
                        {{--<img src="../assets/img/team-2.jpg" class="avatar avatar-sm  ms-3 ">--}}
                        {{--</div>--}}
                        {{--<div class="d-flex flex-column justify-content-center">--}}
                        {{--<h6 class="text-sm font-weight-normal mb-1">--}}
                        {{--<span class="font-weight-bold">New message</span> from Laur--}}
                        {{--</h6>--}}
                        {{--<p class="text-xs text-secondary mb-0">--}}
                        {{--<i class="fa fa-clock me-1"></i>--}}
                        {{--13 minutes ago--}}
                        {{--</p>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</a>--}}
                        {{--</li>--}}
                        {{--<li class="mb-2">--}}
                        {{--<a class="dropdown-item border-radius-md" href="javascript:;">--}}
                        {{--<div class="d-flex py-1">--}}
                        {{--<div class="my-auto">--}}
                        {{--<img src="../assets/img/small-logos/logo-spotify.svg"--}}
                        {{--class="avatar avatar-sm bg-gradient-dark  ms-3 ">--}}
                        {{--</div>--}}
                        {{--<div class="d-flex flex-column justify-content-center">--}}
                        {{--<h6 class="text-sm font-weight-normal mb-1">--}}
                        {{--<span class="font-weight-bold">New album</span> by Travis Scott--}}
                        {{--</h6>--}}
                        {{--<p class="text-xs text-secondary mb-0">--}}
                        {{--<i class="fa fa-clock me-1"></i>--}}
                        {{--1 day--}}
                        {{--</p>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                        {{--<a class="dropdown-item border-radius-md" href="javascript:;">--}}
                        {{--<div class="d-flex py-1">--}}
                        {{--<div class="avatar avatar-sm bg-gradient-secondary  ms-3  my-auto">--}}
                        {{--<svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1"--}}
                        {{--xmlns="http://www.w3.org/2000/svg"--}}
                        {{--xmlns:xlink="http://www.w3.org/1999/xlink">--}}
                        {{--<title>credit-card</title>--}}
                        {{--<g id="Basic-Elements" stroke="none" stroke-width="1"--}}
                        {{--fill="none" fill-rule="evenodd">--}}
                        {{--<g id="Rounded-Icons"--}}
                        {{--transform="translate(-2169.000000, -745.000000)"--}}
                        {{--fill="#FFFFFF" fill-rule="nonzero">--}}
                        {{--<g id="Icons-with-opacity"--}}
                        {{--transform="translate(1716.000000, 291.000000)">--}}
                        {{--<g id="credit-card"--}}
                        {{--transform="translate(453.000000, 454.000000)">--}}
                        {{--<path class="color-background"--}}
                        {{--d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z"--}}
                        {{--id="Path" opacity="0.593633743"></path>--}}
                        {{--<path class="color-background"--}}
                        {{--d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"--}}
                        {{--id="Shape"></path>--}}
                        {{--</g>--}}
                        {{--</g>--}}
                        {{--</g>--}}
                        {{--</g>--}}
                        {{--</svg>--}}
                        {{--</div>--}}
                        {{--<div class="d-flex flex-column justify-content-center">--}}
                        {{--<h6 class="text-sm font-weight-normal mb-1">--}}
                        {{--Payment successfully completed--}}
                        {{--</h6>--}}
                        {{--<p class="text-xs text-secondary mb-0">--}}
                        {{--<i class="fa fa-clock me-1"></i>--}}
                        {{--2 days--}}
                        {{--</p>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</a>--}}
                        {{--</li>--}}
                        {{--</ul>--}}
                        {{--</li>--}}
                    </ul>
                </div>
            </div>
        </nav>
        <!-- End Navbar -->
        <div class="container-fluid py-4 min-vh-100">

            <x-panel :section="'content'"></x-panel>

        </div>
    </main>
</div>

{{--@endsection--}}

@section('script')


    <script type="text/javascript">


        $(document).ready(function () {

        });

    </script>

@endsection