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
            <a href="{{ url('asset/maintenance/logs/'.$asset->id) }}" class="btn btn-warning shadow-md tooltip" title="Lịch sử bảo trì"><i data-feather="tool" class="w-4 h-4"></i></a>
            <a href="{{ url('asset/logs/'.$asset->id) }}" class="btn btn-primary shadow-md ml-2 tooltip" title="Lịch sử cấp phát"><i data-feather="clipboard" class="w-4 h-4"></i></a>
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
                        Nội dung yêu cầu
                    </h2>
                </div>
                <div class="grid grid-cols-12 gap-2 mt-5 box">
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
                    <div class="col-span-12">
                        <form id="frm_checkout" action="{{ url('checkout-asset') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                            <div class="grid grid-cols-12 gap-2 p-5">
                                <div class="col-span-12 lg:col-span-6">
                                    <div class="col-span-12">
                                        <p class="font-medium py-2">Người yêu cầu: <span class="text-gray-600 whitespace-nowrap">{{ $checkout->requester->name }}</span></p>
                                        <p class="font-medium py-2">Phòng ban: <span class="text-gray-600 whitespace-nowrap">{{ $checkout->requester->department->department_name }}</span></p>
                                        <p class="font-medium py-2">Từ ngày: <span class="text-gray-600 whitespace-nowrap">{{ date('d-m-y', strtotime($checkout->from_date)) }}</span></p>
                                        <p class="font-medium py-2">Tới ngày: <span class="text-gray-600 whitespace-nowrap">{{ date('d-m-y', strtotime($checkout->to_date)) }}</span></p>
                                        <p class="font-medium py-2">Nội dung: <span class="text-gray-600 whitespace-nowrap">{{ $checkout->content }}</span></p>
                                    </div>
                                    <div class="col-span-12">
                                        <label for="serial" class="form-label">Số serial</label>
                                        <input name="serial" type="text" class="serial form-control w-full mb-2" placeholder="Serial" value="{{ $asset->serial }}">
                                        <input type="hidden" name="id" value="{{ $checkout->id}}">
                                        {{ csrf_field() }}
                                    </div>
                                    <div class="col-span-12">
                                        <label for="asset_tag" class="form-label">Thẻ tài sản</label>
                                        <input name="asset_tag" type="text" class="asset_tag form-control w-full" placeholder="Thẻ tài sản" value="{{ $asset->asset_tag }}">
                                    </div>
                                    <div class="col-span-12">
                                        <label for="notes" class="form-label">Lưu ý</label>
                                        <textarea name="notes" class="serial form-control w-full mb-2" placeholder="Nội dung">{{ $checkout->notes }}</textarea>
                                    </div>
                                    <div class="col-span-12 mb-2">
                                        <label for="status" class="form-label">Quyết định</label>
                                        <select name="status" data-placeholder="Chọn phòng ban" class="tail-select w-full">
                                            <option value="A">Đồng ý</option>
                                            <option value="D">Từ chối</option>
                                        </select>
                                    </div>
                                    @if($checkout->accepted_at == null && $checkout->denied_at == null)
                                        <div class="col-span-12 text-right">
                                            <button type="submit" class="btn btn-primary btn_accept w-auto mr-2"><i data-feather="save" class="w-4 h-4 mr-2"></i>Cập nhật</button>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-span-12 lg:col-span-6"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- END: Display Information -->
        </div>
    </div>
@endsection