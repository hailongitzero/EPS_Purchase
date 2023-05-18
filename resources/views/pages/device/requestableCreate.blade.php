@extends('../layout/side-menu')

@section('subhead')
<title>EPS - Genco3 Quản lý yêu cầu</title>
@endsection

@section('subcontent')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Mượn thiết bị
        </h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0 ml-auto">
            <a href="{{ url('requestable') }}" class="btn btn-primary shadow-md mr-2 tooltip" title="Danh sách"><i data-feather="list" class="w-4 h-4"></i></a>
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
                        Nội dung yêu cầu mượn
                    </h2>
                </div>
                <div class="grid grid-cols-12 gap-2 mt-5 p-5 box">
                    <div class="col-span-12 lg:col-span-6">
                        <form action="{{url('requestable/add')}}" method="POST" autocomplete="off" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" id="asset_id" name="asset_id" value="{{ $asset->id }}">
                            @if ($errors->any())
                                <div class="col-span-12">
                                    <div class="alert alert-danger show mb-2">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                            @if (session('success'))
                                <div class="col-span-12">
                                    <div class="alert alert-success show mb-2">{{ session('success') }}</div>
                                </div>
                            @endif
                            <div class="col-span-12 lg:col-span-6">
                                <div class="sm:grid grid-cols-2 gap-2">
                                    <div class="input-group">
                                        <label class="">Từ ngày</label>
                                    </div>
                                    <div class="input-group">
                                        <label class="">Tới ngày</label>
                                    </div>
                                    <div class="input-group">
                                        <div class="relative w-full">
                                            <div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-gray-100 border text-gray-600 dark:bg-dark-1 dark:border-dark-4"> <i data-feather="calendar" class="w-4 h-4"></i> </div>
                                            <input id="from_date" name="from_date" type="text" class="datepicker_empty form-control pl-12" placeholder="Từ ngày" data-single-mode="true" required>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <div class="relative w-full">
                                            <div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-gray-100 border text-gray-600 dark:bg-dark-1 dark:border-dark-4"> <i data-feather="calendar" class="w-4 h-4"></i> </div>
                                            <input id="to_date" name="to_date" type="text" class="datepicker_empty form-control pl-12" placeholder="Đến ngày" data-single-mode="true">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 lg:col-span-6">
                                <label for="content" class="form-label">Nội dung</label>
                                <textarea id="content" name="content" type="text" class="form-control w-full" placeholder="Nội dung"></textarea>
                            </div>
                            @if ($asset->status_id == 2)
                            <div class="col-span-12 lg:col-span-12 text-right p-5">
                                <button type="submit" class="btn btn-primary w-32"><i data-feather="save" class="w-4 h-4 mr-2"></i>Yêu cầu</button>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <!-- END: Display Information -->
        </div>
    </div>
@endsection