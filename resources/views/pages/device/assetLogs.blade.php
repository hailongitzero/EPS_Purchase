@extends('../layout/side-menu')

@section('subhead')
<title>EPS - Genco3 Quản lý yêu cầu</title>
@endsection

@section('subcontent')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Lịch sử sử dụng
    </h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0 ml-auto">
        <a href="{{ url('/asset/deploy/'.$asset->id) }}" class="btn btn-primary shadow-md tooltip" title="Cấp phát"><i data-feather="activity" class="w-4 h-4"></i></a>
        <a href="{{ url('asset/maintenance/logs/'.$asset->id) }}" class="btn btn-warning shadow-md ml-2 tooltip" title="Lịch sử bảo trì"><i data-feather="tool" class="w-4 h-4"></i></a>
        <a href="{{ url('assets') }}" class="btn btn-primary shadow-md ml-2 tooltip" title="Danh sách thiết bị"><i data-feather="list" class="w-4 h-4"></i></a>
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
                    Lịch sử
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
                <div class="col-span-12">
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr class="bg-gray-700 dark:bg-dark-1 text-white">
                                    <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Stt</th>
                                    <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Tác vụ</th>
                                    <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Người thực hiện</th>
                                    <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Đối tượng</th>
                                    <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Thời gian</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($logs as $key=>$val)
                                @if ($key % 2 == 0)
                                <tr class="bg-gray-200 dark:bg-dark-1">
                                    <td class="border-b dark:border-dark-5">{{ $key }}</td>
                                    <td class="border-b dark:border-dark-5">{{ $val->action_type }}</td>
                                    <td class="border-b dark:border-dark-5">{{ $val->user->name }}</td>
                                    <td class="border-b dark:border-dark-5">{{ $val->assigned ? $val->assigned->name : "" }}</td>
                                    <td class="border-b dark:border-dark-5">{{ date("d-m-Y H:i:s", strtotime($val->updated_at)) }}</td>
                                </tr>
                                @else
                                <tr>
                                    <td class="border-b dark:border-dark-5">{{ $key }}</td>
                                    <td class="border-b dark:border-dark-5">{{ $val->action_type }}</td>
                                    <td class="border-b dark:border-dark-5">{{ $val->user->name }}</td>
                                    <td class="border-b dark:border-dark-5">{{ $val->assigned->name }}</td>
                                    <td class="border-b dark:border-dark-5">{{ date("d-m-Y H:i:s", strtotime($val->updated_at)) }}</td>
                                </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Display Information -->
    </div>
</div>
@endsection