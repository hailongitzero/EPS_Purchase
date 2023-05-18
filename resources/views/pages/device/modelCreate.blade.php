@extends('../layout/side-menu')

@section('subhead')
<title>EPS - Genco3 Quản lý thiết bị</title>
@endsection

@section('subcontent')
<div class="flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Tạo mới Model</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0 ml-auto">
        <a href="{{url('models')}}" class="btn btn-primary shadow-md mr-2 tooltip" title="Danh sách model"><i data-feather="list" class="w-4 h-4"></i></a>
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
            <form action="{{url('model-create')}}" method="POST" autocomplete="off" enctype="multipart/form-data">
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
                                    <label for="model_number"class="form-label">Số hiệu</label>
                                    <input id="model_number" name="model_number" type="text" class="form-control disabled" placeholder="số hiệu" value="">
                                </div>
                                <div class="mt-3">
                                    <label for="manufacturer"class="form-label">Nhà sản xuất</label>
                                    <select id="manufacturer" name="manufacturer" data-placeholder="Chọn nhà sản xuất" data-search="true" class="tail-select w-full" required>
                                        @foreach ($manufacturer as $mft)
                                        <option value="{{ $mft->id }}">{{ $mft->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-3">
                                    <label for="category"class="form-label">Danh mục</label>
                                    <select id="category" name="category" data-placeholder="Chọn Danh mục sản phẩm" data-search="true" class="tail-select w-full" required>
                                        @foreach ($category as $cate)
                                        <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-3">
                                    <label for="depreciation"class="form-label">Khấu hao</label>
                                    <select id="depreciation" name="depreciation" data-placeholder="chọn thời gian khấu hao" class="tail-select w-full" required>
                                        @foreach ($depreciation as $depr)
                                        <option value="{{ $depr->id }}">{{ $depr->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-3">
                                    <label for="eol"class="form-label">vòng đời</label>
                                    <div class="input-group mt-2 sm:mt-0">
                                        <input id="eol" name="eol" type="number" class="form-control disabled" placeholder="số năm" value="">
                                        <div class="input-group-text">Tháng</div>
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
                                <img class="model-img rounded-md" alt="" src="{{url('storage/placeholders/200x200.jpg')}}">
                            </div>
                            <div class="mx-auto cursor-pointer relative mt-5">
                                <button type="button" class="btn btn-primary w-full">Change Photo</button>
                                <input id="model_img" name="model_img" type="file" class="w-full h-full top-0 left-0 absolute opacity-0">
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