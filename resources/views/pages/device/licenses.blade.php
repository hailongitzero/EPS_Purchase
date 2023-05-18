@extends('../layout/side-menu')

@section('subhead')
<title>EPS - Genco3 Quản lý thiết bị</title>
@endsection

@section('subcontent')
<div class="flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Licenses
    </h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0 ml-auto">
        <a href="{{ url('licenses/add')}}" class="btn btn-primary shadow-md mr-2 tooltip" title="Thêm mới license">
            <i data-feather="plus" class="w-4 h-4"></i>
        </a>
    </div>
</div>
<!-- BEGIN: HTML Table Data -->
<div class="box p-5 mt-5">
    <div class="flex flex-col sm:flex-row sm:items-end xl:items-start">
        <div class="xl:flex sm:mr-auto items-center mt-2">
            <span>Tổng số license: </span><span id="total_licenses" class="font-medium ml-2"></span>
        </div>
        <form id="licenses-table-filter-form" class="xl:flex sm:ml-auto">
            <div class="flex-1 relative sm:flex items-center sm:mr-4 mt-2 xl:mt-0">
                <input id="licenses-table-filter-value" type="text" class="form-control form-control-rounded w-64" placeholder="Tên...">
                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i> 
            </div>
        </form>
    </div>
    <div class="overflow-x-auto scrollbar-hidden">
        <div id="licenses-table" class="mt-5 table-report table-report--tabulator"></div>
        <div class="w-full mt-5">
            <a href="javascript:;" class="license-table-collapse text-center flex flex-col">
                <i class="fa fa-caret-up" aria-hidden="true"></i>
                Thu gọn
            </a>
        </div>
    </div>
</div>
<!-- END: HTML Table Data -->
@endsection