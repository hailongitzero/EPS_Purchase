@extends('../layout/side-menu')

@section('subhead')
<title>EPS - Genco3 Quản lý thiết bị</title>
@endsection

@section('subcontent')
<div class="flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Danh Mục
    </h2>
</div>
<!-- BEGIN: HTML Table Data -->
<div class="box p-5 mt-5">
    <div class="flex flex-col sm:flex-row sm:items-end xl:items-start">
        <form id="maintenances-table-filter-form" class="xl:flex sm:ml-auto">
            <div class="flex-1 relative sm:flex items-center sm:mr-4 mt-2 xl:mt-0">
                <input id="maintenances-table-filter-value" type="text" class="form-control form-control-rounded w-64" placeholder="Tên...">
                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i> 
            </div>
        </form>
    </div>
    <div class="overflow-x-auto scrollbar-hidden">
        <div id="maintenances-table" class="mt-5 table-report table-report--tabulator"></div>
    </div>
</div>
<!-- END: HTML Table Data -->
@endsection