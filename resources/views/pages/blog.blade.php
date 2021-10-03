@extends('layouts.app')

@section('content')
    <section class="mt-7    mx-md-5 mx-sm-3 text-right">
        <div class="      shadow-card bg-white p-md-5 p-sm-3 rounded-3">
            <h2 class="text-center">بزودی!</h2>
        </div>
    </section>

@endsection


@section('script')


    <script src="{{asset('js/plugins/unitegallery.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/plugins/ug-theme-tiles.js')}}" type="text/javascript"></script>
    <script type="text/javascript">


        $(document).ready(function () {

            let g = $("#gallery").unitegallery({
                gallery_theme: "tiles",
                tiles_type: "nested",
                tiles_enable_transition: false,
//                gallery_min_width: '500',
            });


        });

    </script>

@endsection