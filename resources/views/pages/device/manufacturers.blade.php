@extends('../layout/side-menu')

@section('subhead')
<title>EPS - Genco3 Quản lý thiết bị</title>
@endsection

@section('subcontent')
<div class="flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Nhà sản xuất
    </h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0 ml-auto">
        <a href="{{ url('manufacturer/add')}}" class="btn btn-primary shadow-md mr-2 tooltip" title="Thêm mới nhà sản xuất">
            <i data-feather="plus" class="w-4 h-4"></i>
        </a>
    </div>
</div>
<!-- BEGIN: HTML Table Data -->
<div class="intro-y box p-5 mt-5">
    <div class="flex flex-col sm:flex-row sm:items-end xl:items-start">
        <div class="xl:flex sm:mr-auto items-center mt-2">
            <span>Tổng số nhà sản xuất: </span><span id="total_manufacturers" class="font-medium ml-2"></span>
        </div>
        <form id="manufacturers-table-filter-form" class="xl:flex sm:ml-auto">
            <div class="sm:flex items-center sm:mr-4">
                <label class="flex-none xl:flex-initial mr-2">Trạng thái</label>
                <select id="manufacturers-table-filter-status" class="tail-select sm:w-32 mt-2 sm:mt-0">
                    <option value="">Tất cả</option>
                    <option value="1">Active</option>
                    <option value="2">Inactive</option>
                </select>
            </div>
            <div class="flex-1 relative sm:flex items-center sm:mr-4 mt-2 xl:mt-0">
                <input id="manufacturers-table-filter-value" type="text" class="form-control form-control-rounded w-64" placeholder="Tên...">
                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i>
            </div>
        </form>
    </div>
    <div class="overflow-x-auto scrollbar-hidden">
        <div id="manufacturers-table" class="mt-5 table-report table-report--tabulator"></div>
        <div class="w-full mt-5">
            <a href="javascript:;" class="manufacturers-table-collapse text-center flex flex-col">
                <i class="fa fa-caret-up" aria-hidden="true"></i>
                Thu gọn
            </a>
        </div>
    </div>
</div>
<!-- END: HTML Table Data -->
@endsection
