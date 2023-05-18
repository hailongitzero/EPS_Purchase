@extends('../layout/side-menu')

@section('subhead')
<title>EPS - Genco3 Quản lý thiết bị</title>
@endsection

@section('subcontent')
<div class="flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Danh Mục
    </h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0 ml-auto">
        <a href="{{ url('assets/add')}}" class="btn btn-primary shadow-md mr-2 tooltip" title="Thêm mới thiết bị"><i data-feather="plus" class="w-4 h-4"></i></a>
    </div>
</div>
<!-- BEGIN: HTML Table Data -->
<div class="box p-5 mt-5">
    <div class="flex flex-col sm:flex-row sm:items-end xl:items-start">
        <div class="xl:flex sm:mr-auto items-center mt-2">
            <span>Tổng số thiết bị: </span><span id="total_assets" class="font-medium ml-2"></span>
        </div>
        <form id="assets-table-filter-form" class="xl:flex sm:ml-auto">
            <div class="sm:flex items-center sm:mr-4">
                <label class="w-32 flex-none xl:flex-initial mr-2">Đơn vị</label>
                <select id="assets-table-filter-department" class="tail-select sm:w-56 mt-2 sm:mt-0" data-search="true">
                    <option value="">Tất cả</option>
                    @foreach ($department as $dept)
                        <option value="{{ $dept->department_id}}">{{ $dept->department_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:flex items-center sm:mr-4">
                <label class="w-32 flex-none xl:flex-initial mr-2">Trạng thái</label>
                <select id="assets-table-filter-status" class="tail-select sm:w-56 mt-2 sm:mt-0" data-search="true">
                    <option value="">Tất cả</option>
                    @foreach ($status as $sts)
                        <option value="{{ $sts->id}}">{{ $sts->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 relative sm:flex items-center sm:mr-4 mt-2 xl:mt-0">
                <input id="assets-table-filter-value" type="text" class="form-control form-control-rounded w-64" placeholder="Tên...">
                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i>
            </div>
        </form>
    </div>
    <div class="overflow-x-auto scrollbar-hidden">
        <div id="assets-table" class="mt-5 table-report table-report--tabulator"></div>
        <div class="w-full mt-5">
            <a href="javascript:;" class="asset-table-collapse text-center flex flex-col">
                <i class="fa fa-caret-up" aria-hidden="true"></i>
                Thu gọn
            </a>
        </div>
    </div>
</div>
<!-- END: HTML Table Data -->
@endsection
