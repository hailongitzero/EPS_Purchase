@extends('../layout/side-menu')

@section('subhead')
<title>EPS - Genco3 Quản lý yêu cầu</title>
<script type="text/javascript" src="{{ url('/js/ckfinder/ckfinder.js) }}"></script>
<script>CKFinder.config( { connectorPath: "{{ url('/ckfinder/connector') }}" } );</script>
@endsection

@section('subcontent')
    <div class="flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Hướng dẫn sử dụng
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-6">
        <!-- BEGIN: Profile Menu -->
        <div class="col-span-12 flex lg:block flex-col-reverse">
            <div class="box mt-5">
                @include('ckfinder::setup')
                <div id="ckfinder-widget-manual">
                </div>
            </div>
        </div>
    </div>
@endsection
