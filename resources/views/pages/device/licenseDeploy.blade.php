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
            <a href="{{ url('licenses/update/'.$license->id) }}" class="btn btn-primary shadow-md mr-2 tooltip" title="Cập nhật license">
                <i data-feather="edit" class="w-4 h-4"></i>
            </a>
            <a href="{{ url('licenses') }}" class="btn btn-primary shadow-md mr-2 tooltip" title="Danh sách License">
                <i data-feather="list" class="w-4 h-4"></i>
            </a>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-6">
        <!-- BEGIN: Profile Menu -->
        <div class="col-span-12 lg:col-span-4 xxl:col-span-3 flex lg:block flex-col-reverse">
            <div class="box mt-5">
                <div class="p-2 border-t border-gray-200 dark:border-dark-5">
                    <span class="font-medium text-base">Tên: </span>{{ $license->name }}
                </div>
                <div class="p-2 border-t border-gray-200 dark:border-dark-5">
                    <span class="font-medium text-base">Tên đăng ký: </span>{{ $license->license_name }}
                </div>
                <div class="p-2 border-t border-gray-200 dark:border-dark-5">
                    <span class="font-medium text-base">Email: </span>{{ $license->license_email }}
                </div>
                <div class="p-2 border-t border-gray-200 dark:border-dark-5">
                    <span class="font-medium text-base">Serial: </span>{{ $license->serial }}
                </div>
                <div class="p-2 border-t border-gray-200 dark:border-dark-5">
                    <span class="font-medium">Danh mục: </span>{{ $license->category->name }}
                </div>
                <div class="p-2 border-t border-gray-200 dark:border-dark-5">
                    <span class="font-medium">Nhà sản xuất: </span>{{ $license->manufacturer->name }}
                </div>
                <div class="p-2 border-t border-gray-200 dark:border-dark-5">
                    <span class="font-medium">Số lượng: </span><span id="license_total">{{ $license->limit_seats == 0 ? "Không giới hạn" : $license->seats }}</span>
                </div>
                <div class="p-2 border-t border-gray-200 dark:border-dark-5">
                    <span class="font-medium">Còn lại: </span><span id="license_remain">{{ $license->limit_seats == 0 ? "Không giới hạn" : $license->remain }}</span>
                </div>
                <div class="p-2 border-t border-gray-200 dark:border-dark-5">
                    <span class="font-medium">Ngày hết hạn: </span>{{ $license->limit_date == 0 ? "Không" : date('d-m-Y', strtotime($license->expiration_date)) }}
                </div>
                <div class="p-2 border-t border-gray-200 dark:border-dark-5">
                    <span class="font-medium">Ngày mua: </span>{{ date('d-m-Y', strtotime($license->purchase_date)) }}
                </div>
                <div class="p-2 border-t border-gray-200 dark:border-dark-5">
                    <span class="font-medium">Đính kèm: </span>
                    @foreach($license->uploads as $upload)
                        <span class="text-xs px-1 rounded-full border mx-2 my-2"><a href="{{ url('license-download/'. $upload->id) }}" class="text-theme-17">{{ $upload->filename }}</a></span>
                    @endforeach
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
                <div class="flex flex-row p-5 lisence-control">
                    <div class="input-group w-40">
                        <select data-placeholder="" class="tail-select" id="license_type" name="license_type" required>
                            <option value="user">Người dùng</option>
                            <option value="asset">Thiết bị</option>
                        </select>
                        <input id="license_id" type="hidden" value="{{ $license->id }}">
                    </div>
                    <div class="input-group ml-2 w-full user_type">
                        <select data-placeholder="" class="tail-select" data-search="true" id="user_type" name="user_type" required>
                            @foreach ($departments as $dept)
                                <optgroup label="{{ $dept->department_name}}">
                                    @foreach ($dept->users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group ml-2 w-full asset_type hidden">
                        <select data-placeholder="" class="tail-select" data-search="true" id="asset_type" name="asset_type" required>
                            @foreach ($models as $md)
                                <optgroup label="{{ $md->name}}">
                                    @foreach ($md->assets as $asset)
                                    <option value="{{$asset->id}}">{{$asset->name}} -- {{$asset->asset_tag}}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group">
                        <button id="btn_addDeployLicense" class="btn btn-primary ml-2" data-controls="next">Thêm</button>
                    </div>
                </div>
                <div class="p-5 overflow-x-auto">
                    <table id="tb_deployLicense" class="table">
                        <thead>
                            <tr class="bg-gray-700 dark:bg-dark-1 text-white">
                                <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Stt</th>
                                <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Thiết bị</th>
                                <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Người dùng</th>
                                <th class="border-b-2 dark:border-dark-5 whitespace-nowrap">Phòng ban</th>
                                <th class="border-b-2 dark:border-dark-5 whitespace-nowrap w-24"></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div class="input-group ml-auto">
                        <button id="btn_saveDeployLicense" class="btn btn-primary ml-auto mt-2">Lưu</button>
                    </div>
                </div>
                <div class="p-5 border-t">
                    <div class="flex flex-col sm:flex-row sm:items-end xl:items-start">
                        <div class="sm:flex items-center sm:mr-4">
                            <label class="text-lg font-medium mr-auto">Đã cấp</label>
                        </div>
                        <form id="licenses-deploy-table-filter-form" class="xl:flex sm:ml-auto">
                            <div class="flex-1 relative sm:flex items-center sm:mr-4 mt-2 xl:mt-0">
                                <input id="licenses-deploy-table-filter-user" type="text" class="form-control form-control-rounded w-64" placeholder="Tên người dùng">
                                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i>
                            </div>
                            <div class="flex-1 relative sm:flex items-center sm:mr-4 mt-2 xl:mt-0">
                                <input id="licenses-deploy-table-filter-asset" type="text" class="form-control form-control-rounded w-64" placeholder="Tên thiết bị">
                                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i>
                            </div>
                        </form>
                    </div>
                    <div class="overflow-x-auto scrollbar-hidden">
                        <div id="licenses-deploy-table" class="mt-5 table-report table-report--tabulator"></div>
                        <a href="javascript:;" class="licenses-deploy-table-collapse text-center flex flex-col">
                            <i class="fa fa-caret-up" aria-hidden="true"></i>
                            Thu gọn
                        </a>
                    </div>
                </div>
            </div>
            <!-- END: Display Information -->
        </div>
    </div>
@endsection
