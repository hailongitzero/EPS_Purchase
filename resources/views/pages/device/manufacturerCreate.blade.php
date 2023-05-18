@extends('../layout/side-menu')

@section('subhead')
<title>EPS - Genco3 Quản lý thiết bị</title>
@endsection

@section('subcontent')
<div class="flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Tạo mới Nhà sản xuất</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0 ml-auto">
        <a href="{{url('manufacturers')}}" class="btn btn-primary shadow-md mr-2 tooltip" title="Danh sách"><i data-feather="list" class="w-4 h-4"></i></a>
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
            <form action="{{url('manufacturer-create')}}" method="POST" autocomplete="off" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="flex flex-col-reverse xl:flex-row flex-col">
                    <div class="flex-1 mt-6 xl:mt-0">
                        <div class="grid grid-cols-12 gap-x-5">
                        <div class="col-span-12">
                                <div>
                                    <label for="name"class="form-label">Tên</label>
                                    <input id="name" name="name" type="text" class="form-control disabled" placeholder="tên" value="">
                                </div>
                                <div class="mt-3">
                                    <label for="url"class="form-label">Url</label>
                                    <input id="url" name="url" type="text" class="form-control disabled" placeholder="http://example.com" value="">
                                </div>
                                <div class="mt-3">
                                    <label for="support_url"class="form-label">Link Hỗ trợ</label>
                                    <input id="support_url" name="support_url" type="text" class="form-control disabled" placeholder="http://example.com" value="">
                                </div>
                                <div class="mt-3">
                                    <label for="support_phone"class="form-label">Số điện thoại</label>
                                    <input id="support_phone" name="support_phone" type="text" class="form-control disabled" placeholder="số điện thoại" value="">
                                </div>
                                <div class="mt-3">
                                    <label for="support_email"class="form-label">Email</label>
                                    <input id="support_email" name="support_email" type="email" class="form-control disabled" placeholder="email..." value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-52 mx-auto xl:mr-0 xl:ml-6">
                        <div class="border-2 border-dashed shadow-sm border-gray-200 dark:border-dark-5 rounded-md p-5">
                            <div class="h-40 relative image-fit cursor-pointer zoom-in mx-auto">
                                <img class="manufacturer-img rounded-md" alt="" src="{{url('storage/placeholders/200x200.jpg')}}">
                            </div>
                            <div class="mx-auto cursor-pointer relative mt-5">
                                <button type="button" class="btn btn-primary w-full">Change Photo</button>
                                <input id="manufacturer_img" name="manufacturer_img" type="file" class="w-full h-full top-0 left-0 absolute opacity-0">
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