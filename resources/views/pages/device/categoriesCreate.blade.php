@extends('../layout/side-menu')

@section('subhead')
<title>EPS - Genco3 Quản lý thiết bị</title>
@endsection

@section('subcontent')
<div class="flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Tạo mới Danh mục</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0 ml-auto">
        <a href="{{url('category')}}" class="btn btn-primary shadow-md mr-2 tooltip" title="Danh sách danh mục"><i data-feather="list" class="w-4 h-4"></i></a>
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
            <form action="{{url('category-create')}}" method="POST" autocomplete="off" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="flex flex-col-reverse xl:flex-row flex-col">
                    <div class="flex-1 mt-6 xl:mt-0">
                        <div class="grid grid-cols-12 gap-x-5">
                            <div class="col-span-12">
                                <div>
                                    <label for="name"class="form-label">Tên</label>
                                    <input id="name" name="name" type="text" class="form-control disabled" placeholder="Input text" value="">
                                </div>
                                <div class="mt-3">
                                    <label for="type" class="form-label">Loại</label>
                                    <select id="type" name="type" class="tail-select w-full">
                                        @foreach($category_type as $type)
                                        <option value="{{$type->category_type}}">{{$type->category_type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-52 mx-auto xl:mr-0 xl:ml-6">
                        <div class="border-2 border-dashed shadow-sm border-gray-200 dark:border-dark-5 rounded-md p-5">
                            <div class="h-40 relative image-fit cursor-pointer zoom-in mx-auto">
                                <img class="category-img rounded-md" alt="" src="{{url('storage/placeholders/200x200.jpg')}}">
                                <!-- <div title="Xoá hình đại diện này?" class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-theme-24 right-0 top-0 -mr-2 -mt-2"> <i data-feather="x" class="w-4 h-4"></i> </div> -->
                            </div>
                            <div class="mx-auto cursor-pointer relative mt-5">
                                <button type="button" class="btn btn-primary w-full">Change Photo</button>
                                <input id="category_img" name="category_img" type="file" class="w-full h-full top-0 left-0 absolute opacity-0">
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