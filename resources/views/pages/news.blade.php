@extends('../layout/side-menu')

@section('subhead')
<title>EPS - Genco3 Quản lý yêu cầu</title>
@endsection

@section('subcontent')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Quản lý tin tức
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-6">
        <!-- BEGIN: Profile Menu -->
        <div class="col-span-12 flex lg:block flex-col-reverse">
            <div class="intro-y col-span-12 md:col-span-6">
                <div class="box">
                    <div class="flex flex-col lg:flex-row items-center p-5">
                        <div class="w-32 h-32 lg:w-32 lg:h-32 image-fit lg:mr-1">
                            <img alt="" class="" src="">
                        </div>
                        <div class="lg:ml-2 lg:mr-auto text-center lg:text-left mt-3 lg:mt-0">
                            <input id="crud-form-1" type="text" class="form-control w-full" placeholder="Link hình">
                            <input id="crud-form-1" type="text" class="form-control w-full" placeholder="Tiêu đề">
                            <textarea name="" id="" rows="3" class="form-control w-full" placeholder="Trích dẫn"></textarea>
                        </div>
                        <div class="flex mt-4 lg:mt-0">
                            <button class="btn btn-primary py-1 px-2 mr-2">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection