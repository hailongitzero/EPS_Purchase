@extends('../layout/side-menu')

@section('subhead')
<title>EPS - Genco3 Quản lý thiết bị</title>
@endsection

@section('subcontent')
<div class="flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Tạo mới tài sản</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0 ml-auto">
        <a href="{{ url('licenses') }}" class="btn btn-primary shadow-md mr-2 tooltip" title="Danh sách License">
            <i data-feather="list" class="w-4 h-4"></i>
        </a>
    </div>
</div>
<!-- Slider -->
<div class="box p-5 mt-5">
    <form id="frm_insertLicenses" action="{{ url('licenses-insert') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
        <div class="grid grid-cols-12 gap-4 mt-5 p-5">
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
                <label for="name" class="form-label">Tên license <span class="text-theme-24">(*)</span></label>
                <input id="name" name="name" type="text" class="form-control w-full" placeholder="Tên tài sản" required>
            </div> 
            <div class="col-span-12 lg:col-span-6">
                <label for="license_name" class="form-label">Tên đăng ký <span class="text-theme-24">(*)</span></label>
                <input id="license_name" name="license_name" type="text" class="form-control w-full" placeholder="Tên tài sản" required>
            </div>
            <div class="col-span-12 lg:col-span-6">
                <label for="license_email" class="form-label">Email đăng ký <span class="text-theme-24">(*)</span></label>
                <input id="license_email" name="license_email" type="text" class="form-control w-full" placeholder="Tên tài sản" required>
            </div> 
            <div class="col-span-12 lg:col-span-6">
                <label for="serial" class="form-label">Serial <span class="text-theme-24">(*)</span></label>
                <textarea id="serial" name="serial" type="text" class="form-control w-full" placeholder="Serial" required></textarea>
            </div>
            <div class="col-span-12 lg:col-span-6">
                <div class="sm:grid grid-cols-2 gap-2">
                    <div class="input-group">
                        <label>Danh mục <span class="text-theme-24">(*)</span></label>
                    </div>
                    <div class="input-group">
                        <label>Mã đơn hàng <span class="text-theme-24">(*)</span></label>
                    </div>
                    <div class="input-group">
                        <select data-placeholder="Danh mục" data-search="true" class="tail-select" id="category" name="category" required>
                            @foreach ($category as $cate)
                            <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group">
                        <input id="order_no" name="order_no" type="text" class="form-control w-full" placeholder="Mã đơn hàng" required>
                    </div>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-6">
                <div class="sm:grid grid-cols-2 gap-2">
                    <div class="input-group">
                        <label>Nhà phát hành <span class="text-theme-24">(*)</span></label>
                    </div>
                    <div class="input-group">
                        <label>Nhà cung cấp <span class="text-theme-24">(*)</span></label>
                    </div>
                    <div class="input-group">
                        <select data-placeholder="Chọn nhà phát hành" class="tail-select" id="manufacturer" name="manufacturer" required>
                        @foreach ( $manufacturers as $fact)
                            <option value="{{ $fact->id }}">{{ $fact->name }}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="input-group">
                        <select data-placeholder="Chọn nhà cung cấp" class="tail-select" id="supplier" name="supplier" required>
                        @foreach ( $suppliers as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-6">
                <div class="sm:grid grid-cols-3 gap-2">
                    <div class="input-group">
                        <label>Loại</label>
                    </div>
                    <div class="input-group">
                        <label>Số lượng</label>
                    </div>
                    <div class="input-group">
                        <label>Đơn giá</label>
                    </div>
                    <div class="input-group">
                        <div class="form-check">
                            <input id="limit_seats" name="limit_seats" class="form-check-switch" type="checkbox">
                            <label class="form-check-label" for="limit_seats">Không giới hạn</label>
                        </div>
                    </div>
                    <div class="input-group mt-2 sm:mt-0">
                        <input id="seats" name="seats" type="number" class="form-control" placeholder="" aria-describedby="input-group-4" disabled>
                        <div class="input-group-text">Keys</div>
                    </div>
                    <div class="input-group mt-2 sm:mt-0">
                        <input id="cost" name="purchase_cost" type="number" class="form-control" placeholder="Tổng tiền" aria-describedby="input-group-4">
                        <div class="input-group-text">Vnd</div>
                    </div>
                    <div class="input-group"></div>
                    <div class="input-group"></div>
                    <div class="input-group mt-2 sm:mt-0">
                        <div><span id="cost_format"></span></div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-6">
                <div class="sm:grid grid-cols-3 gap-2">
                    <div class="input-group">
                        <label>Ngày mua</label>
                    </div>
                    <div class="input-group">
                        <label>Thời hạn</label>
                    </div>
                    <div class="input-group">
                        <label>Ngày hết hạn</label>
                    </div>
                    <div class="input-group">
                        <div class="relative w-56">
                            <div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-gray-100 border text-gray-600 dark:bg-dark-1 dark:border-dark-4"> <i data-feather="calendar" class="w-4 h-4"></i> </div>
                            <input id="purchase_date" name="purchase_date" type="text" class="datepicker form-control pl-12" placeholder="Ngày mua" data-single-mode="true" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="form-check">
                            <input id="limit_date" name="limit_date" class="form-check-switch" type="checkbox">
                            <label class="form-check-label" for="limit_date">Không</label>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="relative w-56">
                            <div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-gray-100 border text-gray-600 dark:bg-dark-1 dark:border-dark-4"> <i data-feather="calendar" class="w-4 h-4"></i> </div>
                            <input id="expiration_date" name="expiration_date" type="text" class="expired_datepicker form-control pl-12" placeholder="Ngày mua" data-single-mode="true" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12">
                <label>Ghi chú <span class="text-theme-24">(*)</span></label>
                <textarea id="notes" class="form-control" name="notes" placeholder="Nội dung" required></textarea>
            </div>
            <div class="col-span-12">
                <label>Đính kèm</label>
                <div class="dropzone" data-id="attachment">
                    <div class="fallback">
                        <input id="attachment" name="attachment" type="file" multiple/>
                    </div>
                    <div class="dz-message" data-dz-message>
                        <div class="text-lg font-medium">Kéo thả file vào đây để tải lên</div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-12 text-right mt-5">
                <button type="submit" class="btn btn-primary w-24">Thêm mới</button>
            </div>
        </div>
    </form>
</div>

@endsection