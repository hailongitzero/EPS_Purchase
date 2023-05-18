@extends('../layout/side-menu')

@section('subhead')
<title>EPS - Genco3 Quản lý thiết bị</title>
@endsection

@section('subcontent')
<div class="flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Cập nhật thông tin thiết bị</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0 ml-auto">
        <a href="{{ url('asset/maintenance/logs/'.$asset->id) }}" class="btn btn-warning shadow-md tooltip" title="Lịch sử bảo trì"><i data-feather="tool" class="w-4 h-4"></i></a>
        <a href="{{ url('asset/logs/'.$asset->id) }}" class="btn btn-primary shadow-md ml-2 tooltip" title="Lịch sử cấp phát"><i data-feather="clipboard" class="w-4 h-4"></i></a>
        <a href="{{ url('assets') }}" class="btn btn-primary shadow-md ml-2 tooltip" title="Danh sách thiết bị"><i data-feather="list" class="w-4 h-4"></i></a>
    </div>
</div>
<!-- Slider -->
<div class="box p-5 mt-5">
    <form id="frm_updateAssets" action="{{ url('asset-update') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
        <div class="flex flex-col-reverse xl:flex-row flex-col ">
            <div class="flex-1 mt-6 xl:mt-0">
                <div class="grid grid-cols-12 gap-2 mt-5 p-5">
                    <div class="col-span-12 failure hidden">
                        <div class="alert alert-danger show mb-2">
                            <ul>
                            </ul>
                        </div>
                    </div>
                    <div class="col-span-12 success hidden">
                        <div class="alert alert-success show mb-2"></div>
                    </div>
                    <div class="col-span-12 lg:col-span-6">
                        {{ csrf_field() }}
                        <label for="name" class="form-label">Tên tài sản <span class="text-theme-24">(*)</span></label>
                        <input id="name" name="name" type="text" class="form-control w-full" placeholder="Tên tài sản" value="{{$asset->name}}" required>
                        <input id="id" name="id" type="hidden" value="{{ $asset->id }}">
                    </div>
                    <div class="col-span-12 lg:col-span-6">
                        <label for="asset-tag" class="form-label">Thẻ tài sản</label>
                        <input id="asset-tag" name="asset-tag" type="text" class="form-control w-full" placeholder="Thẻ tài sản" value="{{$asset->asset_tag}}">
                    </div>
                    <div class="col-span-12 lg:col-span-6">
                        <label for="serial" class="form-label">Serial</label>
                        <input id="serial" name="serial" type="text" class="form-control w-full" placeholder="Serial" value="{{$asset->serial}}">
                    </div>
                    <div class="col-span-12 lg:col-span-6">
                        <label for="model" class="form-label">Model</label>
                        <div class="input-group">
                            <select data-placeholder="Model" data-search="true" class="tail-select" id="model" name="model" required>
                            @foreach ($categories as $cate)
                                <optgroup label="{{ $cate->name}}">
                                @foreach ($cate->models as $model)
                                <option value="{{ $model->id }}"{{ $asset->model->id == $model->id ? 'selected' : '' }}>{{ $model->name }}</option>
                                @endforeach
                                </optgroup>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-6">
                        <div class="sm:grid grid-cols-3 gap-2">
                            <div class="input-group">
                                <label class="">Số lượng <span class="text-theme-24">(*)</span></label>
                            </div>
                            <div class="input-group">
                                <label class="">Đơn giá <span class="text-theme-24">(*)</span></label>
                            </div>
                            <div class="input-group">
                                <label class="">Mã đơn hàng <span class="text-theme-24">(*)</span></label>
                            </div>
                            <div class="input-group">
                                <input id="quantity" type="number" name="quantity" class="form-control" placeholder="Số lượng" value="{{$asset->quantity}}" required>
                                <div class="">
                                    <select id="unit" name="unit" data-placeholder="" class="tail-select w-full" required>
                                        <option value="1" {{$asset->unit == 1 ? 'selected' : ''}}>Cái</option>
                                        <option value="2" {{$asset->unit == 2 ? 'selected' : ''}}>Bộ</option>
                                        <option value="3" {{$asset->unit == 3 ? 'selected' : ''}}>Sợi</option>
                                        <option value="4" {{$asset->unit == 4 ? 'selected' : ''}}>Mét</option>
                                    </select>
                                </div>
                            </div>
                            <div class="input-group mt-2 sm:mt-0">
                                <input id="cost" name="cost" type="number" class="form-control" placeholder="Đơn giá" value="{{$asset->purchase_cost}}" aria-describedby="input-group-4" required>
                                <div class="input-group-text">Vnd</div>
                            </div>
                            <div class="input-group mt-2 sm:mt-0">
                                <input id="order_no" name="order_no" type="text" class="form-control w-full" placeholder="Mã đơn hàng" value="{{$asset->order_number}}" required>
                            </div>
                            <div class="input-group"></div>
                            <div class="input-group mt-2 sm:mt-0">
                                <div><span id="cost_format"></span></div>
                            </div>
                            <div class="input-group"></div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-6">
                        <div class="sm:grid grid-cols-2 gap-2">
                            <div class="input-group">
                                <label class="">Nhà cung cấp</label>
                            </div>
                            <div class="input-group">
                                <label class="">Trạng thái</label>
                            </div>
                            <div class="input-group">
                                <select data-placeholder="Chọn nhà cung cấp" class="tail-select" id="supplier" name="supplier" required>
                                @foreach ( $suppliers as $sup)
                                    <option value="{{ $sup->id }}" {{ $asset->supplier_id == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="input-group">
                                <select id="status" name="status" data-placeholder="Chọn trạng thái" class="tail-select w-full" required>
                                @foreach ($status as $sts)
                                <option value="{{ $sts->id }}" {{ $asset->status_id == $sts->id ? 'selected' : '' }}>{{ $sts->name }}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-6">
                        <div class="sm:grid grid-cols-3 gap-2">
                            <div class="input-group">
                                <label class="">Ngày mua</label>
                            </div>
                            <div class="input-group">
                                <label class="">Bảo hành <span class="text-theme-24">(*)</span></label>
                            </div>
                            <div class="input-group">
                            </div>
                            <div class="input-group">
                                <div class="relative w-56">
                                    <div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-gray-100 border text-gray-600 dark:bg-dark-1 dark:border-dark-4"> <i data-feather="calendar" class="w-4 h-4"></i> </div>
                                    <input id="purchase_dt" name="purchase_dt" type="text" class="datepicker form-control pl-12" value="{{date('d-m-Y', strtotime($asset->purchase_date))}}" placeholder="Ngày mua" data-single-mode="true" required>
                                </div>
                            </div>
                            <div class="input-group">
                                <input id="warranty" name="warranty" type="number" class="form-control" value="{{$asset->warranty_months}}" placeholder="Bảo hành" aria-describedby="input-group-4">
                                <div class="input-group-text">Tháng</div>
                            </div>
                            <div class="input-group">
                                <div class="form-check">
                                    <input id="requestable" name="requestable" class="form-check-switch" type="checkbox" {{$asset->requestable == 1 ? 'checked' : ''}}>
                                    <label class="form-check-label" for="checkbox-switch-7">Có thể yêu cầu</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-6">
                        <label for="crud-form-2" class="form-label">Nơi lưu kho</label>
                        <div class="input-group">
                            <select data-placeholder="Select your favorite actors" class="tail-select" id="location" name="location" required>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept->department_id}}" {{ $dept->department_id == $asset->department_id ? 'selected' : '' }}>{{ $dept->department_name}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-span-12">
                        <label>Ghi chú <span class="text-theme-24">(*)</span></label>
                        <textarea id="notes" class="form-control" name="notes" placeholder="Nội dung" required>{{ $asset->notes }}</textarea>
                    </div>
                    <div class="col-span-12">
                        <label>Đính kèm</label>
                        <div class="attached">
                            @foreach($asset->uploads as $upload)
                            <span class="text-xs px-1 rounded-full border mx-2 my-2"><a href="{{ url('asset-download/'. $upload->id) }}" class="text-theme-17">{{ $upload->filename }}</a></span>
                            @endforeach
                        </div>
                        <div class="dropzone" data-id="attachment">
                            <div class="fallback">
                                <input id="attachment" name="attachment" type="file" multiple/>
                            </div>
                            <div class="dz-message" data-dz-message>
                                <div class="text-lg font-medium">Kéo thả file vào đây để tải lên</div>
                            </div>
                        </div>
                    </div>
                    @if ($asset->status_id == 1 || $asset->status_id == 2 || $asset->status_id == 4)
                    <div class="col-span-12 lg:col-span-12 text-right mt-5">
                        <button id="btn_updateAsset" type="submit" class="btn btn-primary w-24">Cập nhật</button>
                    </div>
                    @endif
                </div>
            </div>
            <div class="w-52 mx-auto xl:mr-0 xl:ml-6">
                <div class="border-2 border-dashed shadow-sm border-gray-200 dark:border-dark-5 rounded-md p-5">
                    <div class="h-40 relative image-fit cursor-pointer zoom-in mx-auto">
                        <img class="asset-img rounded-md" alt="" src="{{$asset->image ? url($asset->image) : url('storage/placeholders/200x200.jpg')}}">
                    </div>
                    <div class="mx-auto cursor-pointer relative mt-5">
                        <button type="button" class="btn btn-primary w-full">Change Photo</button>
                        <input id="asset_img" name="asset_img" type="file" class="w-full h-full top-0 left-0 absolute opacity-0">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- BEGIN: Large Modal Content -->
<div id="modal-add-model" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <a data-dismiss="modal" href="javascript:;"> <i data-feather="x" class="w-8 h-8 text-gray-500"></i> </a>
            <div class="modal-body p-3">
                <h1 class="text-center font-medium">Tạo mới Model </h1>
                <div class="grid grid-cols-12 gap-2 p2">
                    <div class="col-span-12">
                        <label for="model_name" class="form-label">Thẻ tài sản</label>
                        <input id="model_name" name="model_name" type="text" class="form-control w-full" placeholder="Model">
                    </div>
                    <div class="col-span-12 text-center">
                        <button id="btn_add_model" type="button" data-dismiss="modal" class="btn w-24 btn-primary">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Large Modal Content -->
<!-- BEGIN: Large Modal Content -->
<div id="modal-add-supplier" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <a data-dismiss="modal" href="javascript:;"> <i data-feather="x" class="w-8 h-8 text-gray-500"></i> </a>
            <div class="modal-body p-3">
                <h1 class="text-center font-medium">Thêm nhà cung cấp</h1>
                <div class="grid grid-cols-12 gap-2 p2">
                    <div class="col-span-12">
                        <label for="supplier_name" class="form-label">tên</label>
                        <input id="supplier_name" name="supplier_name" type="text" class="form-control w-full" placeholder="Nhà cung cấp">
                    </div>
                    <div class="col-span-12 text-center">
                        <button id="btn_add_supplier" type="button" data-dismiss="modal" class="btn w-24 btn-primary">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Large Modal Content -->

@endsection