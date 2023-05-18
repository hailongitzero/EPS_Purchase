@extends('../layout/side-menu')

@section('subhead')
    <title>Dashboard - EPS - Genco3 Tiếp nhận và Xử lý cầu</title>
@endsection

@section('subcontent')
    <div class="relative">
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 z-10">
                <div class="mt-14 mb-3 grid grid-cols-12 sm:gap-10 intro-y">
                    <div class="col-span-12 md:col-span-6 xl:col-span-3 py-6 sm:pl-5 md:pl-0 lg:pl-5 relative text-center sm:text-left">
                        <div class="text-sm xxl:text-base font-medium -mb-1">
                            Hi {{ $userInfo->name }},
                        </div>
                        <div class="text-base xxl:text-lg justify-center sm:justify-start flex items-center text-gray-700 dark:text-gray-500 leading-3 mt-5 xxl:mt-5">
                            Số yêu cầu mới
                        </div>
                        <div class="xxl:flex mt-5 mb-3">
                            <div class="flex items-center justify-center sm:justify-start">
                                <div class="relative text-3xl xxl:text-4xl font-bold leading-6 pl-4">
                                    {{ $totalDailyRequest }}
                                </div>
                            </div>
                        </div>
                        <div class="text-base xxl:text-lg justify-center sm:justify-start flex items-center text-gray-700 dark:text-gray-500 leading-3 mt-2 xxl:mt-3">
                            Số lượng truy cập
                        </div>
                        <div class="xxl:flex mt-5 mb-3">
                            <div class="flex items-center justify-center sm:justify-start">
                                <div class="relative text-3xl xxl:text-xl font-bold leading-6 pl-4">
                                    {{ $totalVisit }}
                                </div>
                            </div>
                        </div>
                        <div class="text-base xxl:text-sm justify-center sm:justify-start flex items-center text-gray-700 dark:text-gray-500 leading-2 mt-2 xxl:mt-3">
                            Số lượng truy cập trong ngày
                        </div>
                        <div class="xxl:flex mt-5 mb-3">
                            <div class="flex items-center justify-center sm:justify-start">
                                <div class="relative text-3xl xxl:text-xl font-bold leading-6 pl-4">
                                    {{ $totalDailyVisit }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6 xl:col-span-5 py-6 border-black border-opacity-10 border-t md:border-t-0 md:border-l md:border-r border-dashed px-10 sm:px-28 md:px-5 -mx-5">
                        <div class="grid grid-cols-12">
                            <div class="col-span-12 md:col-span-6 xxl:col-span-3 flex flex-wrap items-center pt-2">
                                <div class="flex items-center w-full sm:w-auto justify-center sm:justify-end mr-auto mb-5 xxl:mb-0">
                                    <div class="w-2 h-2 bg-theme-26 rounded-full -mt-4"></div>
                                    <div class="ml-3.5">
                                        <div id="department-request-period-sum" class="relative text-xl xxl:text-2xl font-medium leading-6 xxl:leading-5 pl-3.5"></div>
                                        <div class="text-gray-600 dark:text-gray-600 mt-2">Yêu cầu</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 md:col-span-6 xxl:col-span-3 flex flex-wrap items-center">
                                <select id="department-request-type" class="form-select py-1.5 px-3 mx-auto">
                                    <option value="">Tất cả</option>
                                    @foreach($requestType as $tp)
                                    <option value="{{ $tp->request_type}}">{{ $tp->type_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-12 md:col-span-12 xxl:col-span-6 mx-auto my-auto">
                                <input id="department-request-date-range" data-daterange="true" data-id="department-request-date-range" class="request-date-range form-control block mx-auto">
                            </div>
                        </div>
                        <canvas class="mt-4" id="department-request-period-chart" height="267"></canvas>
                    </div>
                    <div class="col-span-12 md:col-span-6 xl:col-span-4 py-6 border-black border-opacity-10 border-t sm:border-t-0 border-l md:border-l-0 border-dashed -ml-4 pl-4 md:ml-0 md:pl-0">
                        <input id="all-request-date-range" data-daterange="true" data-id="all-request-date-range" class="request-date-range form-control w-44 h-10 block mx-auto">
                        <div class="relative mt-6">
                            <canvas class="mt-8" id="request-group-chart" height="190"></canvas>
                            <div class="flex flex-col justify-center items-center absolute w-full h-full top-0 left-0">
                                <div id="request-group-chart-sum" class="text-xl xxl:text-2xl font-medium"></div>
                                <div class="text-gray-600 dark:text-gray-600 mt-0.5">Tổng số yêu cầu</div>
                            </div>
                        </div>
                        <div class="mx-auto w-10/12 xxl:w-2/3 mt-8">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-theme-26 rounded-full mr-3"></div>
                                <span class="truncate">Yêu cầu mới</span>
                                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                                <span id="request-group-chart-s1" class="font-medium xl:ml-auto"></span>
                            </div>
                            <div class="flex items-center mt-4">
                                <div class="w-2 h-2 bg-theme-10 rounded-full mr-3"></div>
                                <span class="truncate">đang thực hiện</span>
                                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                                <span id="request-group-chart-s2" class="font-medium xl:ml-auto"></span>
                            </div>
                            <div class="flex items-center mt-4">
                                <div class="w-2 h-2 bg-theme-23 rounded-full mr-3"></div>
                                <span class="truncate">hoàn thành</span>
                                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                                <span id="request-group-chart-s3" class="font-medium xl:ml-auto"></span>
                            </div>
                            <div class="flex items-center mt-4">
                                <div class="w-2 h-2 bg-theme-35 rounded-full mr-3"></div>
                                <span class="truncate">Từ chối</span>
                                <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                                <span id="request-group-chart-s4" class="font-medium xl:ml-auto"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="report-box-3 px-5 pt-8 pb-14 col-span-12 z-10">
                <div class="grid grid-cols-12 gap-6 relative intro-y">
                    <div class="col-span-12 xl:col-span-6 xxl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <div class="box p-5">
                                <div class="flex">
                                    <div class="w-32 h-32 flex-none image-fit rounded-md overflow-hidden">
                                        <img alt="Đỗ Hữu Lợi" src="./dist/images/profile-7.jpg">
                                    </div>
                                    <div class="ml-5 mr-auto">
                                        <div class="text-xl font-bold leading-8 mt-6">Đỗ Hữu Lợi</div>
                                        <div class="text-base text-gray-600 mt-1">Tổ trưởng Tổ CNTT</div>
                                        <div class="text-base text-gray-600 mt-1">Điện thoại: 0969460939</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 xl:col-span-6 xxl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <div class="box p-5">
                                <div class="flex">
                                    <div class="w-32 h-32 flex-none image-fit rounded-md overflow-hidden">
                                        <img alt="Trần Trung Thiên" src="{{ url('storage/users/thien.jpg') }}">
                                    </div>
                                    <div class="ml-5 mr-auto">
                                        <div class="text-xl font-bold leading-8 mt-6">Trần Trung Thiên</div>
                                        <div class="text-base text-gray-600 mt-1">Chuyên viên CNTT</div>
                                        <div class="text-base text-gray-600 mt-1">Điện thoại: 0967256785</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 xl:col-span-6 xxl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <div class="box p-5">
                                <div class="flex">
                                    <div class="w-32 h-32 flex-none image-fit rounded-md overflow-hidden">
                                        <img alt="Trà Lê Tiến Đạt" src="./dist/images/profile-7.jpg">
                                    </div>
                                    <div class="ml-5 mr-auto">
                                        <div class="text-xl font-bold leading-8 mt-6">Trà Lê Tiến Đạt</div>
                                        <div class="text-base text-gray-600 mt-1">Chuyên viên CNTT</div>
                                        <div class="text-base text-gray-600 mt-1">Điện thoại: </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 xl:col-span-6 xxl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <div class="box p-5">
                                <div class="flex">
                                    <div class="w-32 h-32 flex-none image-fit rounded-md overflow-hidden">
                                        <img alt="Lê Kim Thiện Phúc" src="./dist/images/profile-7.jpg">
                                    </div>
                                    <div class="ml-5 mr-auto">
                                        <div class="text-xl font-bold leading-8 mt-6">Lê Kim Thiện Phúc</div>
                                        <div class="text-base text-gray-600 mt-1">Chuyên viên CNTT</div>
                                        <div class="text-base text-gray-600 mt-1">Điện thoại: </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="report-box-3 report-box-3--content grid grid-cols-12 gap-6 xl:-mt-5 xxl:-mt-8 -mb-10 z-40 xxl:z-10">
        <div class="col-span-12 lg:col-span-8">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: Weekly Top Products -->
                <div class="col-span-12 mt-6">
                    <div class="intro-y block sm:flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-5">Tin nổi bật</h2>
                    </div>
                    <div class="intro-y overflow-auto lg:overflow-visible mt-8 sm:mt-0">
                        
                    </div>
                </div>
                <!-- END: Weekly Top Products -->
            </div>
        </div>
        <div class="col-span-12 lg:col-span-4 relative z-10">
            <div class="xxl:border-l border-theme-25 pb-10 intro-y">
                <div class="xxl:pl-6 grid grid-cols-12 gap-6">
                    <!-- BEGIN: Recent Activities -->
                    <div class="col-span-12 mt-3 xxl:mt-6">
                        <div class="intro-x flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">Hoạt động gần đây</h2>
                        </div>
                        <div class="report-timeline mt-5 relative">
                            @foreach($lastActive as $act)
                            <div class="intro-x relative flex items-center mb-3">
                                <div class="report-timeline__image">
                                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden">
                                        <img alt="{{ $act['name'] }}" src="{{ url($act['photo'] ? $act['photo'] : 'storage/users/no-user.jpg') }}">
                                    </div>
                                </div>
                                <div class="box px-5 py-3 ml-4 flex-1 zoom-in">
                                    <div class="flex items-center">
                                        <div class="font-medium">{{ $act['name'] }}</div>
                                        <div class="text-xs text-gray-500 ml-auto">{{ date('Y-m-d', strtotime(str_replace('/', '-', $act['time']))) }}</div>
                                    </div>
                                    <div class="text-gray-300 mt-1">
                                        <a href="{{ url($userInfo->role == 2 ? 'administrator' : 'moderator') }}" data-target="{{ $act['tab'] }}" class="notification-link rounded-full p-1 {{ $act['class'] }}">{{ $act['active'] }}</a>
                                        </div>
                                    <div class="text-gray-600 mt-1">{{ $act['subject'] }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- END: Recent Activities -->
                </div>
            </div>
        </div>
    </div>
@endsection
