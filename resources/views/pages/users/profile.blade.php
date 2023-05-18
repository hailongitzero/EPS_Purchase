@extends('../layout/side-menu')

@section('subhead')
<title>EPS - Genco3 Quản lý yêu cầu</title>
@endsection

@section('subcontent')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Thông tin nguời dùng
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-6">
        <!-- BEGIN: Profile Menu -->
        <div class="col-span-12 lg:col-span-4 xxl:col-span-3 flex lg:block flex-col-reverse">
            <div class="intro-y box mt-5">
                <div class="relative flex items-center p-5">
                    <div class="w-12 h-12 image-fit">
                        <img alt="{{ $user->name }}" class="rounded-full" src="{{ url($user->photo ? $user->photo  : 'storage/users/no-user.jpg') }}">
                    </div>
                    <div class="ml-4 mr-auto">
                        <div class="font-medium text-base">{{ $user->name }}</div>
                        <div class="text-gray-600">{{ $user->department->department_name }}</div>
                    </div>
                </div>
                <div class="p-5 border-t border-gray-200 dark:border-dark-5">
                    <a class="flex items-center text-theme-17 dark:text-gray-300 font-medium" href="{{ url('profile/'.$user->username) }}"> <i data-feather="user" class="w-4 h-4 mr-2"></i> Thông tin cá nhân </a>
                </div>
                <div class="p-5 border-t border-gray-200 dark:border-dark-5">
                    <a class="flex items-center text-theme-17 dark:text-gray-300 font-medium" href="{{ url('profile/'.$user->username.'/device') }}"> <i data-feather="monitor" class="w-4 h-4 mr-2"></i> Thiết bị </a>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger show">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
        <!-- END: Profile Menu -->
        <div class="col-span-12 lg:col-span-8 xxl:col-span-9">
            <!-- BEGIN: Display Information -->
            <div class="intro-y box lg:mt-5">
                <div class="flex items-center p-5 border-b border-gray-200 dark:border-dark-5">
                    <h2 class="font-medium text-base mr-auto">
                        Thông tin cá nhân
                    </h2>
                </div>
                <div class="p-5">
                    <form action="../update-profile" method="POST" autocomplete="off" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="flex flex-col-reverse xl:flex-row flex-col">
                            <div class="flex-1 mt-6 xl:mt-0">
                                <div class="grid grid-cols-12 gap-x-5">
                                    <div class="col-span-12 xxl:col-span-6">
                                        <div>
                                            <label for="name"class="form-label">Họ tên</label>
                                            <input id="name" name="name" type="text" class="form-control disabled" placeholder="Input text" value="{{ $user->name }}" readonly>
                                        </div>
                                        <div class="mt-3">
                                            <label for="department" class="form-label">Phòng ban / Phân xưởng</label>
                                            <select id="department" name="department" data-search="true" class="tail-select w-full">
                                                @foreach ( $department as $dept )
                                                <option value="{{ $dept->department_id }}" {{ $dept->department_id == $user->department_id ? 'selected' : '' }}>{{ $dept->department_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mt-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input id="username" name="username" type="text" class="form-control" placeholder="username" value="{{ $user->username }}" readonly>
                                        </div>
                                        <div class="mt-3">
                                            <label for="position" class="form-label">Chức vụ</label>
                                            <input id="position" name="position" type="text" class="form-control" placeholder="Chức vụ" value="{{ $user->position }}">
                                        </div>
                                    </div>
                                    <div class="col-span-12 xxl:col-span-6">
                                        <div>
                                            <label for="mail" class="form-label">Email</label>
                                            <input id="email" name="email" type="email" class="form-control" placeholder="Input text" value="{{ $user->email }}" readonly>
                                        </div>
                                        <div class="mt-3">
                                            <label for="role" class="form-label">Phân quyền</label>
                                            <select id="role" name="role" data-search="true" class="tail-select w-full" {{ $role == 0 ? 'readonly' : '' }}>
                                                <option value="0" {{ $user->role == 0 ? 'selected' : '' }}>Nhân viên</option>
                                                <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>Chuyên viên</option>
                                                <option value="2" {{ $user->role == 2 ? 'selected' : '' }}>Quản trị</option>
                                            </select>
                                        </div>
                                        <div class="mt-3">
                                            <label for="gender" class="form-label">Giới tính</label>
                                            <select id="gender" name="gender" data-search="true" class="tail-select w-full">
                                                <option value="0" {{ $user->gender == 0 ? 'selected' : '' }}>Không xác định</option>
                                                <option value="1" {{ $user->gender == 1 ? 'selected' : '' }}>Nam</option>
                                                <option value="2" {{ $user->gender == 2 ? 'selected' : '' }}>Nữ</option>
                                            </select>
                                        </div>
                                        <div class="mt-3">
                                            <label for="telephone" class="form-label">Điện thoại</label>
                                            <input id="telephone" name="telephone" type="text" class="form-control" placeholder="0999 999 999" value="{{ $user->telephone }}">
                                        </div>
                                    </div>
                                    <div class="col-span-12">
                                        <div class="mt-3">
                                            <label for="description" class="form-label">Mô tả</label>
                                            <textarea id="description" name="description" class="form-control" placeholder="Giới thiệu bản thân"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn-update-profile btn btn-primary w-20 mt-3">Lưu</button>
                            </div>
                            <div class="w-52 mx-auto xl:mr-0 xl:ml-6">
                                <div class="border-2 border-dashed shadow-sm border-gray-200 dark:border-dark-5 rounded-md p-5">
                                    <div class="h-40 relative image-fit cursor-pointer zoom-in mx-auto">
                                        <img class="avatar-img rounded-md" alt="{{ $user->name}}" src="{{ url($user->photo ? $user->photo : 'storage/users/no-user.jpg') }}">
                                        <!-- <div title="Xoá hình đại diện này?" class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-theme-24 right-0 top-0 -mr-2 -mt-2"> <i data-feather="x" class="w-4 h-4"></i> </div> -->
                                    </div>
                                    <div class="mx-auto cursor-pointer relative mt-5">
                                        <button type="button" class="btn btn-primary w-full">Change Photo</button>
                                        <input id="avatar" name="avatar" type="file" class="w-full h-full top-0 left-0 absolute opacity-0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="intro-y box lg:mt-5">
                <div class="flex items-center p-5 border-b border-gray-200 dark:border-dark-5">
                    <h2 class="font-medium text-base mr-auto">
                        Đổi mật khẩu
                    </h2>
                </div>
                <div class="p-5">
                    <div class="flex flex-col-reverse xl:flex-row flex-col">
                        <div class="flex-1 mt-6 xl:mt-0">
                            <form action="../change-password" method="POST" autocomplete="off">
                                {{ csrf_field() }}
                                <div class="grid grid-cols-12 gap-x-5">
                                    <div class="col-span-12 xxl:col-span-6">
                                        @if ($role == 0)
                                        <div>
                                            <label for="old-password" class="form-label">Mật khẩu cũ</label>
                                            <input id="old-password" type="password" class="form-control" placeholder="Password">
                                        </div>
                                        @else
                                            <input name="id" type="hidden" value="{{ $user->id }}">
                                        @endif
                                        <div class="mt-3">
                                            <label for="password" class="form-label">Mật khẩu mới</label>
                                            <input id="password" name="password" type="password" class="form-control" placeholder="Password">
                                        </div>
                                        <div class="mt-3">
                                            <label for="confirm-password" class="form-label">Nhập lại mật khẩu mới</label>
                                            <input id="confirm-password" name="password_confirmation" type="password" class="form-control" placeholder="Password">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary w-32 mt-3">Đổi mật khẩu</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Display Information -->
        </div>
    </div>
@endsection