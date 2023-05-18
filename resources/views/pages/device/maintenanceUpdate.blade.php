@extends('../layout/side-menu')

@section('subhead')
<title>EPS - Genco3 Quản lý yêu cầu</title>
@endsection

@section('subcontent')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Thông tin
        </h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0 ml-auto">
            <a href="{{ url('asset/maintenance/logs/'.$asset->id) }}" class="btn btn-warning shadow-md ml-2 tooltip" title="Lịch sử bảo trì"><i data-feather="tool" class="w-4 h-4"></i></a>
            <a href="{{ url('maintenances') }}" class="btn btn-primary shadow-md ml-2 tooltip" title="Danh sách"><i data-feather="list" class="w-4 h-4"></i></a>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-6">
        <!-- BEGIN: Profile Menu -->
        <div class="col-span-12 lg:col-span-4 xxl:col-span-3 flex lg:block flex-col-reverse">
            <div class="box mt-5">
                <div class="items-center w-full p-5">
                    <img alt="{{ $asset->name }}" class="mx-auto" src="{{ url($asset->image ? $asset->image  : 'storage/placeholders/200x200.jpg') }}">
                </div>
                <div class="px-5 py-2 border-t border-gray-200 dark:border-dark-5">
                    <div class="font-medium text-base">{{ $asset->name }}
                        <a href="{{url('asset/update/'.$asset->id)}}" class="text-theme-17"><i data-feather="edit" class="w-4 h-4"></i></a>
                    </div>
                    <div class="text-gray-600">{{ $asset->model->name }}</div>
                </div>
                <div class="px-5 py-2 border-t border-gray-200 dark:border-dark-5">
                    <span class="font-medium">Thương hiệu: </span>{{ $asset->model->manufacturer->name }}
                </div>
                <div class="px-5 py-2 border-t border-gray-200 dark:border-dark-5">
                    <span class="font-medium">Thẻ tài sản: </span>{{ $asset->asset_tag }}
                </div>
                <div class="px-5 py-2 border-t border-gray-200 dark:border-dark-5">
                    <span class="font-medium">Số lượng: </span>{{ $asset->quantity }}
                </div>
                <div class="px-5 py-2 border-t border-gray-200 dark:border-dark-5">
                    <span class="font-medium">Trạng thái: </span><span style="color: {{ $asset->status->color }}">{{ $asset->status->name }}</span>
                </div>
                <div class="px-5 py-2 border-t border-gray-200 dark:border-dark-5">
                    <span class="font-medium">Serial: </span>{{ $asset->serial }}
                </div>
                <div class="px-5 py-2 border-t border-gray-200 dark:border-dark-5">
                    <span class="font-medium">Ngày mua: </span>{{ date("d-m-Y", strtotime($asset->purchase_date)) }}
                </div>
                <div class="px-5 py-2 border-t border-gray-200 dark:border-dark-5">
                    <span class="font-medium">Bảo hành: </span>
                    <span class="{{ strtotime(date('d-m-Y', strtotime("+$asset->warranty_months months", strtotime($asset->purchase_date)))) < strtotime(date('d-m-Y')) ? 'text-theme-24' : '' }}">
                        {{ $asset->warranty_months }} tháng ({{ date('d-m-Y', strtotime("+$asset->warranty_months months", strtotime($asset->purchase_date))) }})
                    </span>
                </div>
                <div class="px-5 py-2 border-t border-gray-200 dark:border-dark-5">
                    <span class="font-medium">Khấu hao: </span>{{ $asset->model->depreciation->name }}
                </div>
                <div class="px-5 py-2 border-t border-gray-200 dark:border-dark-5">
                    <span class="font-medium">Đính kèm: </span>
                    @foreach($asset->uploads as $upload)
                    <span class="text-xs px-1 rounded-full border mx-2 my-2"><a href="{{ url('asset-download/'. $upload->id) }}" class="text-theme-17">{{ $upload->filename }}</a></span>
                    @endforeach
                </div>
                <div id="accordion-notes" class="accordion accordion-boxed">
                    <div class="accordion-item p-2">
                        <div id="accordion-notes-content" class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-notes-detail" aria-expanded="false" aria-controls="accordion-notes-detail">Thông số kỹ thuật</button>
                        </div>
                        <div id="accordion-notes-detail" class="accordion-collapse collapse" aria-labelledby="accordion-notes-content" data-bs-parent="#accordion-notes">
                            <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                {{ $asset->notes }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Profile Menu -->
        <div class="col-span-12 lg:col-span-8 xxl:col-span-9">
            <!-- BEGIN: Display Information -->
            <div class="box lg:mt-5">
                <div class="flex items-center p-5 border-b border-gray-200 dark:border-dark-5">
                    <h2 class="font-medium text-base mr-auto">
                        Nội dung bảo trì
                    </h2>
                </div>
                <form id="frm_maintenance_setup" action="{{ url('maintenance-setup') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                    <div class="grid grid-cols-12 gap-2 p-5 mt-5 box">
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
                            <div class="grid grid-cols-12 gap-2">
                                <div class="col-span-12">
                                    {{ csrf_field() }}
                                    <label for="title" class="form-label">Tiêu đề</label>
                                    <input id="title" name="title" type="text" class="form-control w-full" placeholder="Tiêu đề" value="{{ $maintenance ? $maintenance->title : ''}}" required>
                                    <input id="id" name="id" type="hidden" value="{{ $maintenance ? $maintenance->id : '' }}">
                                    <input id="asset_id" name="asset_id" type="hidden" value="{{ $asset->id }}">
                                </div>
                                <div class="col-span-12">
                                    <label for="crud-form-2" class="form-label">Đơn vị bảo hành</label>
                                    <div class="input-group">
                                        <select data-placeholder="Đơn vị bảo hành" class="tail-select" id="supplier" name="supplier" required>
                                            <option value="">Chọn Đơn vị bảo hành</option>
                                            @foreach ( $suppliers as $sup)
                                                <option value="{{ $sup->id }}"{{
                                                    $maintenance && $maintenance->supplier_id == $sup->id ? 'selected' : ($asset->supplier_id == $sup->id ? 'selected' : '') }}>{{ $sup->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-span-12">
                                    <label class="form-label">Nội dung</label>
                                    <textarea id="notes" class="form-control" name="notes" placeholder="Nội dung">{{ $maintenance ? $maintenance->notes : '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-6">
                            <div class="grid grid-cols-12 gap-2">
                                <div class="col-span-6">
                                    <label class="form-label">Ngày bắt đầu: </label>
                                    <div class="relative w-56">
                                        <div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-gray-100 border text-gray-600 dark:bg-dark-1 dark:border-dark-4"> <i data-feather="calendar" class="w-4 h-4"></i> </div>
                                        <input id="start_date" name="start_date" type="text" class="datepicker_empty form-control pl-12"
                                            data-single-mode="true" value="{{ $maintenance && $maintenance->start_date ?  date('Y-m-d', strtotime($maintenance->start_date)) : '' }}">
                                    </div>
                                </div>
                                <div class="col-span-6">
                                    <label class="form-label">Ngày hoàn thành: </label>
                                    <div class="relative w-56">
                                        <div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-gray-100 border text-gray-600 dark:bg-dark-1 dark:border-dark-4"> <i data-feather="calendar" class="w-4 h-4"></i> </div>
                                        <input id="completion_date" name="completion_date" type="text" class="datepicker_empty form-control pl-12"
                                            data-single-mode="true" value="{{ $maintenance && $maintenance->completion_date ?  date('Y-m-d', strtotime($maintenance->completion_date)) : '' }}">
                                    </div>
                                </div>
                                <div class="col-span-6">
                                    <label class="form-label">Bảo hành </label>
                                    <div class="input-group">
                                        <div class="form-check">
                                            <input id="is_warranty" name="is_warranty" class="form-check-switch" type="checkbox" {{
                                                $maintenance && $maintenance->is_warranty == 1 ? 'checked' : 
                                                    (date("d-m-Y", strtotime("+".$asset->warranty_months." months", strtotime($asset->purchase_date))) >= date('d-m-Y') ? 'checked' : '')
                                            }}>
                                            <label class="form-check-label" for="is_warranty">Bảo hành</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-6">
                                    <label class="form-label">Chi phí </label>
                                    <div class="input-group mt-2 sm:mt-0">
                                        <input id="cost" name="cost" type="number" class="form-control" placeholder="Giá" aria-describedby="input-group-4" value="{{ $maintenance ? $maintenance->cost : ''}}">
                                        <div class="input-group-text">vnd</div>
                                    </div>
                                    <div>
                                        <span id="cost_format"></span>
                                    </div>
                                </div>
                                <div class="col-span-6">
                                    <label class="form-label">Trạng thái </label>
                                    <div class="input-group mt-2 sm:mt-0">
                                        <select data-placeholder="Trạng thái" class="tail-select" id="status" name="status" required>
                                            <option value="1" {{ !$maintenance || $maintenance->status == 1 ? 'selected' : '' }}>Đăng ký</option>
                                            <option value="2" {{ $maintenance && $maintenance->status == 2 ? 'selected' : '' }}>Đang bảo trì</option>
                                            <option value="3" {{ $maintenance && $maintenance->status == 3 ? 'selected' : '' }}>Hoàn thành bảo trì</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12">
                            <label>Đính kèm</label>
                            <div class="attached">
                                @if ($maintenance)
                                    @foreach($maintenance->uploads as $upload)
                                    <span class="text-xs px-1 rounded-full border mx-2 my-2"><a href="{{ url('maintenance-download/'. $upload->id) }}" class="text-theme-17">{{ $upload->filename }}</a></span>
                                    @endforeach
                                @endif
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
                        @if(!$maintenance || $maintenance->status != 3 )
                        <div class="col-span-12 lg:col-span-12 text-right mt-5">
                            <button id="btn_updateMaintenance" type="submit" class="btn btn-primary w-24">Cập nhật</button>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
            <!-- END: Display Information -->
        </div>
    </div>
@endsection