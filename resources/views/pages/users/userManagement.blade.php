@extends('../layout/side-menu')

@section('subhead')
<title>EPS - Genco3 Quản lý yêu cầu</title>
@endsection

@section('subcontent')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Quản lý người dùng
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a class="btn btn-primary shadow-md mr-2" data-toggle="modal" data-target="#modal_add_user"> <i data-feather="user-plus" class="w-4 h-4 mr-2"></i> Thêm người dùng </a>
            <div class="hidden md:block mx-auto text-gray-600">Hiển thị từ {{ $users->firstItem() }} tới {{ $users->perPage() * $users->currentPage() > $users->total() ? $users->lastItem() : $users->perPage() * $users->currentPage() }} của {{ $users->total() }} người dùng</div>
            <form method="GET" action="user-management" class="flex w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-72 relative text-gray-700 dark:text-gray-300">
                <select name="dept" data-search="true" class="form-control tail-select w-full">
                    <option value="" >Tất cả phòng ban</option>
                    @foreach( $department as $dpt)
                        <option value="{{ $dpt->department_id }}" {{ $dpt->department_id == $dept ? 'selected' : '' }}>{{ $dpt->department_name }}</option>
                    @endforeach
                </select>
                </div>
                <div class="w-56 relative text-gray-700 dark:text-gray-300 ml-5">
                    <input name="name" type="text" class="form-control w-56 box pr-10 placeholder-theme-8" placeholder="Tìm kiếm..." value="{{ $name }}">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i> 
                </div>
                <button class="btn btn-primary shadow-md w-auto ml-2 mb-1"><i class="w-4 h-4 mr-2" data-feather="search"></i> Tìm kiếm </button>
            </form>
        </div>
        <!-- BEGIN: Users Layout -->
        @foreach ($users as $user)
        <div class="intro-y col-span-12 md:col-span-6">
            <div class="box">
                <div class="flex flex-col lg:flex-row items-center p-5">
                    <div class="w-24 h-24 lg:w-12 lg:h-12 image-fit lg:mr-1">
                        <img alt="{{ $user->name }}" class="rounded-full" src="{{ $user->photo ? url($user->photo) : url('storage/users/no-user.jpg') }}">
                    </div>
                    <div class="lg:ml-2 lg:mr-auto text-center lg:text-left mt-3 lg:mt-0">
                        <a href="" class="font-medium">{{ $user->name }}</a> 
                        <div class="text-gray-600 text-xs mt-0.5">{{ $user->department->department_name}}</div>
                    </div>
                    <div class="flex mt-4 lg:mt-0">
                        <a href="{{ url('/profile/'.$user->username) }}" class="btn btn-primary w-32 mr-2 mb-2"> <i data-feather="activity" class="w-4 h-4 mr-2"></i> Thông tin </a>
                        <button class="btn btn-delete-user btn-danger w-auto mr-2 mb-2" data-username="{{$user->username}}"> <i data-feather="trash" class="w-4 h-4"></i></button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        <!-- END: Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
            @if ($users->hasPages())
            <ul class="pagination">
                @if ($users->currentPage() > 3)
                <li>
                    <a class="pagination__link" href="{{ $users->url(1) }}"> <i class="w-4 h-4" data-feather="chevrons-left"></i> </a>
                </li>
                @endif
                @if ($users->currentPage() > 1)
                <li>
                    <a class="pagination__link" href=""> <i class="w-4 h-4" data-feather="chevron-left"></i> </a>
                </li>
                @endif
                @if ($users->currentPage() > 1)
                <li> <a class="pagination__link" href="{{ $users->previousPageUrl() }}">{{ $users->currentPage() - 1}}</a> </li>
                @endif
                <li> <a class="pagination__link pagination__link--active" href="javascript:;">{{ $users->currentPage() }}</a> </li>
                @if ($users->hasMorePages())
                <li> <a class="pagination__link" href="{{ $users->nextPageUrl() }}">{{ $users->currentPage() + 1}}</a> </li>
                @endif
                @if ($users->currentPage() < $users->lastPage())
                <li>
                    <a class="pagination__link" href="{{ $users->nextPageUrl() }}"> <i class="w-4 h-4" data-feather="chevron-right"></i> </a>
                </li>
                @endif
                @if ($users->currentPage() < $users->lastPage() - 2)
                <li>
                    <a class="pagination__link" href="{{ $users->url($users->lastPage()) }}"> <i class="w-4 h-4" data-feather="chevrons-right"></i> </a>
                </li>
                @endif
            </ul>
            @endif
        </div>
        <!-- END: Pagination -->
        <!-- END: Pagination -->
    </div>
    <div id="modal_add_user" class="modal" data-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content"> <a data-dismiss="modal" href="javascript:;"> <i data-feather="x" class="w-8 h-8 text-gray-500"></i> </a>
                <div class="modal-body p-0">
                    <div class="grid grid-cols-12 gap-x-5 p-10">
                        <div class="col-span-12 xxl:col-span-6">
                            <div>
                                <label for="name"class="form-label">Họ tên</label>
                                <input id="name" name="name" type="text" class="form-control disabled" placeholder="Input text">
                                <div id="error-name" class="w-5/6 text-theme-6 mt-2"></div>
                            </div>
                            <div class="mt-3">
                                <label for="department" class="form-label">Phòng ban / Phân xưởng</label>
                                <select id="department" name="department" data-search="true" class="tail-select w-full">
                                    @foreach ( $department as $dept )
                                    <option value="{{ $dept->department_id }}">{{ $dept->department_name }}</option>
                                    @endforeach
                                </select>
                                <div id="error-department" class="w-5/6 text-theme-6 mt-2"></div>
                            </div>
                            <div class="mt-3">
                                <label for="username" class="form-label">Username</label>
                                <input id="username" name="username" type="text" class="form-control" placeholder="username">
                                <div id="error-username" class="w-5/6 text-theme-6 mt-2"></div>
                            </div>
                            <div class="mt-3">
                                <label for="position" class="form-label">Chức vụ</label>
                                <input id="position" name="position" type="text" class="form-control" placeholder="Chức vụ">
                                <div id="error-position" class="w-5/6 text-theme-6 mt-2"></div>
                            </div>
                        </div>
                        <div class="col-span-12 xxl:col-span-6">
                            <div>
                                <label for="email" class="form-label">Email</label>
                                <input id="email" name="email" type="email" class="form-control" placeholder="Input text">
                                <div id="error-email" class="w-5/6 text-theme-6 mt-2"></div>
                            </div>
                            <div class="mt-3">
                                <label for="role" class="form-label">Phân quyền</label>
                                <select id="role" name="role" data-search="true" class="tail-select w-full">
                                    <option value="0">Nhân viên</option>
                                    <option value="1">Chuyên viên</option>
                                    <option value="2">Quản trị</option>
                                </select>
                                <div id="error-role" class="w-5/6 text-theme-6 mt-2"></div>
                            </div>
                            <div class="mt-3">
                                <label for="gender" class="form-label">Giới tính</label>
                                <select id="gender" name="gender" data-search="true" class="tail-select w-full">
                                    <option value="0">Không xác định</option>
                                    <option value="1">Nam</option>
                                    <option value="2">Nữ</option>
                                </select>
                                <div id="error-gender" class="w-5/6 text-theme-6 mt-2"></div>
                            </div>
                            <div class="mt-3">
                                <label for="telephone" class="form-label">Điện thoại</label>
                                <input id="telephone" name="telephone" type="text" class="form-control" placeholder="0999 999 999">
                                <div id="error-telephone" class="w-5/6 text-theme-6 mt-2"></div>
                            </div>
                        </div>
                    </div>
                    <div class="pb-8 text-center pt-5">
                    <button type="button" class="btn-add-user btn btn-primary w-20 mt-3">Lưu</button>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- END: Modal Content -->
@endsection