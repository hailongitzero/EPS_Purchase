@extends('../layout/side-menu')

@section('subhead')
<title>EPS - Genco3 Quản lý yêu cầu</title>
<script>
    function goBack() {
        window.history.back();
    }
</script>
@endsection

@section('subcontent')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Thông tin
    </h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0 ml-auto">
        <a href="{{ url('asset/maintenance/logs/'.$asset->id) }}" class="btn btn-warning shadow-md tooltip" title="Lịch sử bảo trì"><i data-feather="tool" class="w-4 h-4"></i></a>
        <a href="{{ url('asset/logs/'.$asset->id) }}" class="btn btn-primary shadow-md ml-2 tooltip" title="Lịch sử cấp phát"><i data-feather="clipboard" class="w-4 h-4"></i></a>
        <a href="{{ url('assets') }}" class="btn btn-primary shadow-md ml-2 tooltip" title="Danh sách thiết bị"><i data-feather="list" class="w-4 h-4"></i></a>
        <!-- <button onclick="goBack()" class="btn btn-danger shadow-md ml-2 tooltip" title="Quay lại"><i data-feather="corner-down-left" class="w-4 h-4"></i></button> -->
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
                    Danh sách cấp phát
                </h2>
            </div>
            <div class="grid grid-cols-12 gap-2 mt-5 box">
                <div class="col-span-12 failure hidden">
                    <div class="alert alert-danger show mb-2">
                        <ul>
                        </ul>
                    </div>
                </div>
                <div class="col-span-12 success hidden">
                    <div class="alert alert-success show mb-2"></div>
                </div>
                @for ($i = 0; $i < $asset->quantity; $i++)
                    <div class="col-span-12 assets">
                        <div class="assets-item" data-status="">
                            <div class="flex flex-col lg:flex-row items-center px-5 py-1">
                                <div class="lg:mr-1 font-medium text-base">
                                    {{ $i + 1 }}
                                </div>
                                <div class="lg:ml-2 text-center lg:text-left mt-3 lg:mt-0">
                                    <select data-search="true" data-placeholder="Chọn phòng ban" class="assigned {{$asset->status_id == 2 ? 'tail-select' : 'form-select'}} w-full" {{$asset->status_id == 2 ? '' : 'disabled'}}>
                                        <option value="">---Chọn người cấp---</option>
                                        @foreach ($departments as $dept)
                                        <optgroup label="{{ $dept->department_name}}">
                                            @foreach ($dept->users as $user)
                                            <option value="{{ $user->id }}" {{ $asset->assigned_to == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                            @endforeach
                                        </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="lg:ml-2 text-center lg:text-left mt-3 lg:mt-0">
                                    <input type="text" class="serial form-control w-full" placeholder="Serial" value="{{ $asset->serial }}" {{ $asset->status_id == 2 ? '' : 'disabled' }}>
                                    <input type="hidden" class="asset-id" value="{{ $asset->id}}">
                                </div>
                                <div class="lg:ml-2 text-center lg:text-left mt-3 lg:mt-0">
                                    <input type="text" class="asset_tag form-control w-full" placeholder="Thẻ tài sản" value="{{ $asset->asset_tag }}" {{ $asset->status_id == 2 ? '' : 'disabled' }}>
                                </div>
                                <div class="flex lg:mt-0 ml-2">
                                    @if ($asset->status_id == 3 || $asset->status_id == 7)
                                    <button class="btn btn-primary btn_recall w-32"><i data-feather="rotate-ccw" class="w-4 h-4 mr-2"></i>Thu hồi</button>
                                    @elseif ($asset->status_id == 4)
                                    <button class="btn btn-primary btn_recall_cancel w-32"><i data-feather="x-octagon" class="w-4 h-4 mr-2"></i>Hủy thu hồi</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endfor
                    @if ($asset->status_id == 2)
                    <div class="col-span-12 lg:col-span-12 text-right p-5">
                        <button id="btn_deploy" type="submit" class="btn btn-primary btn_deploy w-24"><i data-feather="save" class="w-4 h-4 mr-2"></i>Cấp</button>
                    </div>
                    @endif
            </div>
        </div>
        <!-- END: Display Information -->
    </div>
</div>
@endsection