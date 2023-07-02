@extends('../layout/base')

@section('body')
    <body class="main">
        @yield('content')
        @include('../layout/components/dark-mode-switcher')

        <!-- BEGIN: JS Assets-->
        <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=["your-google-map-api"]&libraries=places"></script>
        <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g="
        crossorigin="anonymous"></script>
        <script src=https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js></script>
        <script src="{{ mix('dist/js/app.js') }}"></script>
        <!-- END: JS Assets-->

        @yield('script')
    </body>
@endsection