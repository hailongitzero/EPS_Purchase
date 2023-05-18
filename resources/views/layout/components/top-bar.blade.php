<!-- BEGIN: Top Bar -->
<div class="top-bar-boxed border-b border-theme-2 -mt-7 md:-mt-5 -mx-3 sm:-mx-8 px-3 sm:px-8 md:pt-0 mb-12">
    <div class="h-full flex items-center">
        <!-- BEGIN: Logo -->
        <a href="" class="-intro-x hidden md:flex">
            <img alt="EPS-Genco3" class="w-40" src="{{ asset('dist/images/logo.png') }}">
        </a>
        <!-- END: Logo -->
        <!-- BEGIN: Breadcrumb -->
        <div class="-intro-x breadcrumb mr-auto">
            @foreach ($breadcrumb as $brc)
                @if ($brc['page_name'] != '' )
                <a href="{{ ($brc['route_name'] != '' ? route($brc['route_name']) : 'javascript:;') }}"
                    class="{{ ($brc['route_name'] != '' ? 'breadcrumb--active' : '') }}">{{ $brc['page_name'] }}</a>
                <i data-feather="chevron-right" class="breadcrumb__icon"></i>
                @endif
            @endforeach
        </div>
        <!-- END: Breadcrumb -->
        <!-- BEGIN: Notifications -->
        @if ($userInfo->role != 0 )
        <div class="intro-x dropdown mr-4 sm:mr-6">
            <div class="dropdown-toggle notification notification--bullet cursor-pointer" role="button" aria-expanded="false">
                <i data-feather="bell" class="notification__icon dark:text-gray-300"></i>
            </div>
            <div class="notification-content pt-2 dropdown-menu">
                <div class="notification-content__box dropdown-menu__content box dark:bg-dark-6">
                    <div class="notification-content__title">Thông báo</div>
                    @foreach ($notifications as $key => $val)
                        @if ($userInfo->role == $val['role'])
                            <div class="cursor-pointer relative flex items-center {{ $key ? 'mt-3' : '' }}">
                                <div class="w-10 h-10 rounded-full {{ $val['class']}} flex-none text-center mr-1 py-2">
                                    <span class="text-white text-lg">{{ $val['count'] }}</span>
                                </div>
                                <div class="ml-2 overflow-hidden">
                                    <div class="flex items-center">
                                        <a href="{{ url($val['role'] == 2 ? 'administrator' : 'moderator') }}" data-target="{{ $val['tab'] }}" class="notification-link font-medium truncate mr-5">{{ $val['title'] }}</a>
                                    </div>
                                    <div class="w-full truncate text-gray-600 mt-0.5">{{ $val['subtitle'] }}</div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        <!-- END: Notifications -->
        <!-- BEGIN: Account Menu -->
        <div class="intro-x dropdown w-8 h-8">
            <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in scale-110" role="button" aria-expanded="false">
                <img alt="{{ $userInfo->name }}" src="{{  $userInfo->photo ? url($userInfo->photo) : url('storage/users/no-user.jpg') }}">
            </div>
            <div class="dropdown-menu w-56">
                <div class="dropdown-menu__content box bg-theme-11 dark:bg-dark-6 text-white">
                    <div class="p-4 border-b border-theme-12 dark:border-dark-3">
                        <div class="font-medium">{{ $userInfo->name }}</div>
                        <div class="text-xs text-theme-13 mt-0.5 dark:text-gray-600">{{ $userInfo->department->department_name }}</div>
                    </div>
                    <div class="p-2">
                        <a href="{{ url('/profile/'.$userInfo->username) }}" class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 dark:hover:bg-dark-3 rounded-md">
                            <i data-feather="user" class="w-4 h-4 mr-2"></i> Thông tin cá nhân
                        </a>
                    </div>
                    <div class="p-2 border-t border-theme-12 dark:border-dark-3">
                        <a href="{{ route('logout') }}" class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 dark:hover:bg-dark-3 rounded-md">
                            <i data-feather="toggle-right" class="w-4 h-4 mr-2"></i> Đăng xuất
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Account Menu -->
    </div>
</div>
<!-- END: Top Bar -->
