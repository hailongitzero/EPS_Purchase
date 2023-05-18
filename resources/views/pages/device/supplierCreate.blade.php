@extends('../layout/side-menu')

@section('subhead')
<title>EPS - Genco3 Quản lý thiết bị</title>
@endsection

@section('subcontent')
<div class="flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Tạo mới Nhà cung cấp</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0 ml-auto">
        <a href="{{url('suppliers')}}" class="btn btn-primary shadow-md mr-2 tooltip" title="Danh sách"><i data-feather="list" class="w-4 h-4"></i></a>
    </div>
</div>
<div class="grid grid-cols-12 gap-2 mt-5 box p-5">
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
        <div class="box">
            <form action="{{url('supplier-create')}}" method="POST" autocomplete="off" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="flex flex-col-reverse xl:flex-row flex-col">
                    <div class="flex-1 mt-6 xl:mt-0">
                        <div class="grid grid-cols-12 gap-x-5">
                        <div class="col-span-12">
                                <div>
                                    <label for="name"class="form-label">Tên<span class="text-theme-24">(*)</span></label>
                                    <input id="name" name="name" type="text" class="form-control disabled" placeholder="tên" value="" required>
                                </div>
                                <div class="mt-3">
                                    <label for="address"class="form-label">Địa chỉ<span class="text-theme-24">(*)</span></label>
                                    <input id="address" name="address" type="text" class="form-control disabled" placeholder="Địa chỉ" value="" required>
                                </div>
                                <div class="mt-3">
                                    <label for="province"class="form-label">Tỉnh - Thành phố<span class="text-theme-24">(*)</span></label>
                                    <input id="province" name="province" type="text" class="form-control disabled" placeholder="Tỉnh - Thành phố" value="" required>
                                </div>
                                <div class="mt-3">
                                    <div class="sm:grid grid-cols-2 gap-2">
                                        <div class="input-group">
                                            <input id="phone" type="text" name="phone" class="form-control" placeholder="">
                                            <div class="input-group-text">Phone</div>
                                        </div>
                                        <div class="input-group mt-2 sm:mt-0">
                                            <input id="fax" name="fax" type="text" class="form-control" placeholder="" aria-describedby="input-group-4">
                                            <div class="input-group-text">Fax</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="input-group mt-2 sm:mt-0">
                                        <input id="email" name="email" type="mail" class="form-control" placeholder="" aria-describedby="input-group-4">
                                        <div class="input-group-text">Email</div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="sm:grid grid-cols-2 gap-2">
                                        <div class="input-group">
                                            <input id="zip" type="text" name="zip" class="form-control" placeholder="">
                                            <div class="input-group-text">Zip</div>
                                        </div>
                                        <div class="input-group mt-2 sm:mt-0">
                                            <input id="url" name="url" type="text" class="form-control" placeholder="" aria-describedby="input-group-4">
                                            <div class="input-group-text">Website</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">.
                                    <label for="notes"class="form-label">Lưu ý</label>
                                    <textarea id="notes" class="form-control" name="notes" placeholder="Nội dung" ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-52 mx-auto xl:mr-0 xl:ml-6">
                        <div class="border-2 border-dashed shadow-sm border-gray-200 dark:border-dark-5 rounded-md p-5">
                            <div class="h-40 relative image-fit cursor-pointer zoom-in mx-auto">
                                <img class="supplier-img rounded-md" alt="" src="{{url('storage/placeholders/200x200.jpg')}}">
                            </div>
                            <div class="mx-auto cursor-pointer relative mt-5">
                                <button type="button" class="btn btn-primary w-full">Change Photo</button>
                                <input id="supplier_img" name="supplier_img" type="file" class="w-full h-full top-0 left-0 absolute opacity-0">
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn-update-profile btn btn-primary w-20 mt-3">Lưu</button>
            </form>
        </div>
    </div>
</div>

@endsection