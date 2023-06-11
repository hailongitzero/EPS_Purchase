@extends('../layout/side-menu')

@section('subhead')
<title>EPS - Genco3 Quản lý yêu cầu</title>
@endsection

@section('subcontent')
<div class="flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Danh sách yêu cầu
    </h2>
</div>
<div class="pos grid grid-cols-12 gap-5 mt-5">
    <!-- BEGIN: Post Content -->
    <div class="col-span-12 lg:col-span-8">
        <div class="grid grid-cols-12 box p-5">
            <div class="box p-2 bg-theme-14 col-span-12">
                <div class="flex flex-wrap">
                    <div id="request_title" class="text-white relative text-xl pl-3.5">Thông tin yêu cầu</div>
                </div>
            </div>
        </div>
        <div class="post box mt-4">
            <div class="manage_request post__tabs nav nav-tabs flex-col sm:flex-row bg-gray-300 dark:bg-dark-2 text-gray-600" role="tablist">
                <a title="Tất cả yêu cầu" data-toggle="tab" data-target=".all_request" href="javascript:;" class="tooltip w-full sm:w-40 py-4 text-center flex flex-col justify-center items-center active" id="all-req-tab" role="tab" aria-controls="content" aria-selected="true">
                    <div>
                        <i data-feather="file-text" class="w-4 h-4"></i> Tất cả
                    </div>
                    <span id="all_request_tab_sum" class="px-2 py-1 rounded-full bg-theme-14 text-white"></span>
                </a>
                <a title="Danh sách yêu cầu đang xử lý" data-toggle="tab" data-target=".handle_request" href="javascript:;" class="tooltip w-full sm:w-40 py-4 text-center flex flex-col justify-center items-center" id="handle-req-tab" role="tab" aria-selected="false">
                    <div>
                        <i data-feather="file-text" class="w-4 h-4"></i> Đang xử lý
                    </div>
                    <span id="handle_request_tab_sum" class="px-2 py-1 rounded-full bg-theme-22 text-white"></span>
                </a>
                <a title="Danh sách yêu cầu gia hạn/chuyển xử lý" data-toggle="tab" data-target=".extend_return_request" href="javascript:;" class="tooltip w-full sm:w-40 py-4 text-center flex flex-col justify-center items-center" id="extend-return-req-tab" role="tab" aria-selected="false">
                    <div>
                        <i data-feather="file-text" class="w-4 h-4"></i> Gia hạn/Chuyển
                    </div>
                    <span id="extend_return_request_tab_sum" class="px-2 py-1 rounded-full bg-theme-22 text-white"></span>
                </a>
                <a title="Danh sách yêu cầu của tôi" data-toggle="tab" data-target=".my_request" href="javascript:;" class="tooltip w-full sm:w-40 py-4 text-center flex flex-col justify-center items-center" id="my-req-tab" role="tab" aria-selected="false">
                    <div>
                        <i data-feather="file-text" class="w-4 h-4"></i> YC của tôi
                    </div>
                    <span id="my_request_tab_sum" class="px-2 py-1 rounded-full bg-theme-10 text-white"></span>
                </a>
            </div>
            <div class="post__content tab-content">
                <div id="all_request" class="all_request tab-pane active" role="tabpanel" aria-labelledby="content-tab">
                    <div class="box p-5 mt-5">
                        <div class="flex flex-col sm:flex-row sm:items-end xl:items-start">
                            <form id="all-request-table-html-filter-form" class="xl:flex sm:ml-auto">
                                <div class="flex-1 relative sm:flex items-center mr-4">
                                    <select id="all-request-table-html-department" class="tail-select w-full" data-search=true>
                                        <option value="" data-cc-mail-check="0">---Chọn phòng ban---</option>
                                        @foreach( $deptUser as $dept )
                                        <option value="{{ $dept->department_id }}">{{ $dept->department_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex-1 relative sm:flex items-center mr-4">
                                    <select id="all-request-table-html-request-tp" class="tail-select w-full" data-search=true>
                                        <option value="" data-cc-mail-check="0">---Chọn loại yêu cầu---</option>
                                        @foreach( $requestTp as $tp )
                                        <option value="{{ $tp->request_type }}" data-cc-mail-check="{{ $tp->cc_mail_check }}">{{ $tp->type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex-1 relative sm:flex items-center sm:mr-4 mt-2 xl:mt-0">
                                    <input id="all-request-table-html-filter-value" type="text" class="form-control form-control-rounded w-64" placeholder="Tiêu đề...">
                                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i>
                                </div>
                                <a href="/export-request" target="_blank" class="btn-extend-modal btn btn-primary w-32 mt-1"> <i data-feather="file" class="w-4 h-4 mr-2"></i> Xuất Excel </a>
                            </form>
                        </div>
                        <div class="overflow-x-auto scrollbar-hidden">
                            <div id="all-request-table" class="mt-5 table-report table-report--tabulator"></div>
                            <div><p class="text-center"><span class="total_all_result">0</span>/<span class="total_all_request">{{$totalRequest}}</span> Tổng số yêu cầu</p></div>
                        </div>
                    </div>
                </div>
                <div id="handle_request" class="handle_request tab-pane" role="tabpanel" aria-labelledby="content-tab">
                    <div class="box p-5 mt-5">
                        <div class="flex flex-col sm:flex-row sm:items-end xl:items-start">
                            <form id="handle-requests-table-html-filter-form" class="xl:flex sm:ml-auto">
                                <div class="flex-1 relative sm:flex items-center mr-4">
                                    <select id="handle-requests-table-html-department" class="tail-select w-full" data-search=true>
                                        <option value="" data-cc-mail-check="0">---Chọn phòng ban---</option>
                                        @foreach( $deptUser as $dept )
                                        <option value="{{ $dept->department_id }}">{{ $dept->department_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex-1 relative sm:flex items-center mr-4">
                                    <select id="handle-requests-table-html-request-tp" class="tail-select w-full" data-search=true>
                                        <option value="" data-cc-mail-check="0">---Chọn loại yêu cầu---</option>
                                        @foreach( $requestTp as $tp )
                                        <option value="{{ $tp->request_type }}" data-cc-mail-check="{{ $tp->cc_mail_check }}">{{ $tp->type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex-1 relative sm:flex items-center sm:mr-4 mt-2 xl:mt-0">
                                    <input id="handle-requests-table-html-filter-value" type="text" class="form-control form-control-rounded w-64" placeholder="Tiêu đề...">
                                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i>
                                </div>
                            </form>
                        </div>
                        <div class="overflow-x-auto scrollbar-hidden">
                            <div id="handle-requests-table" class="mt-5 table-report table-report--tabulator"></div>
                            <div><p class="text-center"><span class="total_handle_result">0</span>/<span class="total_handle_request">{{$totalHandleRequest}}</span> Tổng số yêu cầu</p></div>
                        </div>
                    </div>
                </div>
                <div id="extend_return_request" class="extend_return_request tab-pane" role="tabpanel" aria-labelledby="content-tab">
                    <div class="box p-5 mt-5">
                        <div class="flex flex-col sm:flex-row sm:items-end xl:items-start">
                            <form id="extend-return-request-table-html-filter-form" class="xl:flex sm:ml-auto">
                                <div class="flex-1 relative sm:flex items-center mr-4">
                                    <select id="extend-return-request-table-html-department" class="tail-select w-full" data-search=true>
                                        <option value="" data-cc-mail-check="0">---Chọn phòng ban---</option>
                                        @foreach( $deptUser as $dept )
                                        <option value="{{ $dept->department_id }}">{{ $dept->department_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex-1 relative sm:flex items-center mr-4">
                                    <select id="extend-return-request-table-html-request-tp" class="tail-select w-full" data-search=true>
                                        <option value="" data-cc-mail-check="0">---Chọn loại yêu cầu---</option>
                                        @foreach( $requestTp as $tp )
                                        <option value="{{ $tp->request_type }}" data-cc-mail-check="{{ $tp->cc_mail_check }}">{{ $tp->type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex-1 relative sm:flex items-center sm:mr-4 mt-2 xl:mt-0">
                                    <input id="extend-return-request-table-html-filter-value" type="text" class="form-control form-control-rounded w-64" placeholder="Tiêu đề...">
                                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i>
                                </div>
                            </form>
                        </div>
                        <div class="overflow-x-auto scrollbar-hidden">
                            <div id="extend-return-request-table" class="mt-5 table-report table-report--tabulator"></div>
                            <div><p class="text-center"><span class="total_return_result">0</span>/<span class="total_return_request">{{$totalReturnRequest}}</span> Tổng số yêu cầu</p></div>
                        </div>
                    </div>
                </div>
                <div id="my_request" class="my_request tab-pane" role="tabpanel" aria-labelledby="content-tab">
                    <div class="box p-5 mt-5">
                        <div class="flex flex-col sm:flex-row sm:items-end xl:items-start">
                            <form id="my-request-table-html-filter-form" class="xl:flex sm:ml-auto">
                                <div class="flex-1 relative sm:flex items-center sm:mr-4 mt-2 xl:mt-0">
                                    <input id="my-request-table-html-filter-value" type="text" class="form-control form-control-rounded w-64" placeholder="Tiêu đề...">
                                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i>
                                </div>
                            </form>
                        </div>
                        <div class="overflow-x-auto scrollbar-hidden">
                            <div id="my-request-table" class="mt-5 table-report table-report--tabulator"></div>
                            <div><p class="text-center"><span class="total_my_result">0</span>/<span class="total_my_request">{{$totalMyRequest}}</span> Tổng số yêu cầu</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Post Content -->
    <!-- BEGIN: Post Info -->
    <div id="request-info" class="col-span-12 lg:col-span-4 post__content tab-content">
        <div class="grid grid-cols-12 box p-5">
            <div class="box p-2 bg-theme-14 col-span-12">
                <div class="flex flex-wrap">
                    <div class="mx-auto">
                        <div class="text-white relative text-xl pl-3.5">
                            Chi tiết yêu cầu
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="all_request tab-pane box mt-4 active" role="tabpanel" aria-labelledby="content-tab">
            <div class="grid grid-cols-12 p-5">
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Họ tên</label>
                    <p class="txt_name"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Phòng/Phân xưởng</label>
                    <p class="txt_department"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Thời gian đáp ứng</label>
                    <p class="txt_complete_dt"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Ngày xử lý</label>
                    <p class="txt_handle_dt"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Điện thoại</label>
                    <p class="txt_telephone"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Loại yêu cầu</label>
                    <p class="txt_request_type"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Nguồn vốn được duyệt</label>
                    <p class="txt_resource"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Chi phí được duyệt</label>
                    <p class="txt_cost"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Nguồn vốn thực tế</label>
                    <p class="txt_final_resource"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Chi phí thực tế</label>
                    <p class="txt_final_cost"></p>
                </div>
                <div class="col-span-12 scope border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Cc mail</label>
                    <p class="txt_cc_mail line-break"></p>
                </div>
                <div class="col-span-12 border-b border-gray-200 mt-3 pb-3">
                    <div id="req-accordion-1" class="accordion">
                        <div class="accordion-item">
                            <div id="req-accordion-content-1" class="accordion-header">
                                <button class="accordion-btn" type="button" data-bs-toggle="collapse" data-bs-target="#req-accordion-collapse-1" aria-expanded="true" aria-controls="req-accordion-collapse-1"> Nội dung yêu cầu </button>
                            </div>
                            <div id="req-accordion-collapse-1" class="accordion-collapse collapse show" aria-labelledby="req-accordion-content-1" data-bs-parent="#req-accordion-1">
                                <div class="acd_request_content accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div id="req-accordion-content-2" class="accordion-header">
                                <button class="accordion-btn" type="button" data-bs-toggle="collapse" data-bs-target="#req-accordion-collapse-2" aria-expanded="true" aria-controls="req-accordion-collapse-2"> Giao việc: <span class="txt_assign_person"></span> </button>
                            </div>
                            <div id="req-accordion-collapse-2" class="accordion-collapse collapse show" aria-labelledby="req-accordion-content-2" data-bs-parent="#req-accordion-1">
                                <div class="acd_request_assign_content accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div id="req-accordion-content-3" class="accordion-header">
                                <button class="accordion-btn" type="button" data-bs-toggle="collapse" data-bs-target="#req-accordion-collapse-3" aria-expanded="true" aria-controls="req-accordion-collapse-3"> Xử lý chính: <span class="txt_handler"></span></button>
                            </div>
                            <div id="req-accordion-collapse-3" class="accordion-collapse collapse show" aria-labelledby="req-accordion-content-3" data-bs-parent="#req-accordion-1">
                                <div class="acd_request_handler_content accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div id="req-accordion-content-4" class="accordion-header">
                                <button class="accordion-btn" type="button" data-bs-toggle="collapse" data-bs-target="#req-accordion-collapse-4" aria-expanded="true" aria-controls="req-accordion-collapse-4"> Xử lý phụ: <span class="txt_sub_handler"></span></button>
                            </div>
                            <div id="req-accordion-collapse-4" class="accordion-collapse collapse show" aria-labelledby="req-accordion-content-4" data-bs-parent="#req-accordion-1">
                                <div class="acd_request_sub_hander_content accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Đính kèm</label>
                    <div class="txt_attachment"></div>
                </div>
            </div>
        </div>
        <div class="handle_request tab-pane box mt-4" role="tabpanel" aria-labelledby="content-tab">
            <div class="grid grid-cols-12 p-5">
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Họ tên</label>
                    <p class="txt_name"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Phòng/Phân xưởng</label>
                    <p class="txt_department"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Ngày tạo</label>
                    <p class="txt_create_dt"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Thời gian đáp ứng</label>
                    <p class="txt_complete_dt"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Điện thoại</label>
                    <p class="txt_telephone"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Loại yêu cầu</label>
                    <p class="txt_request_type"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Nguồn vốn</label>
                    <p class="txt_resource"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Chi phí</label>
                    <p class="txt_cost"></p>
                </div>
                <div class="col-span-12 scope border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Cc mail</label>
                    <p class="txt_cc_mail line-break"></p>
                </div>
                <div class="col-span-12 border-b border-gray-200 mt-3 pb-3">
                    <div id="req-accordion-2" class="accordion">
                        <div class="accordion-item">
                            <div id="req-accordion-content-1" class="accordion-header">
                                <button class="accordion-btn" type="button" data-bs-toggle="collapse" data-bs-target="#req-accordion-collapse-1" aria-expanded="true" aria-controls="req-accordion-collapse-1"> Nội dung yêu cầu </button>
                            </div>
                            <div id="req-accordion-collapse-1" class="accordion-collapse collapse show" aria-labelledby="req-accordion-content-1" data-bs-parent="#req-accordion-2">
                                <div class="acd_request_content accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div id="req-accordion-content-2" class="accordion-header">
                                <button class="accordion-btn" type="button" data-bs-toggle="collapse" data-bs-target="#req-accordion-collapse-2" aria-expanded="true" aria-controls="req-accordion-collapse-2"> Giao việc: <span class="txt_assign_person"></span> </button>
                            </div>
                            <div id="req-accordion-collapse-2" class="accordion-collapse collapse show" aria-labelledby="req-accordion-content-2" data-bs-parent="#req-accordion-2">
                                <div class="acd_request_assign_content accordion-body text-gray-700 dark:text-gray-600 leading-relaxed"></div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div id="req-accordion-content-2" class="accordion-header">
                                <button class="accordion-btn" type="button" data-bs-toggle="collapse" data-bs-target="#req-accordion-collapse-2" aria-expanded="true" aria-controls="req-accordion-collapse-2"> Nội dung xử lý </span> </button>
                            </div>
                            <div id="req-accordion-collapse-2" class="accordion-collapse collapse show" aria-labelledby="req-accordion-content-2" data-bs-parent="#req-accordion-2">
                                <div class="accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                    <div id="handle_content" name="ckHandleContent" class="editor"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Đính kèm</label>
                    <div class="txt_attachment"></div>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3 mr-2">
                    <label for="cbx_final_resource" class="form-label font-medium">Nguồn vốn</label>
                    <select id="cbx_final_resource" name="cbx_final_resource" data-id="cbx_final_resource" class="cbx_final_resource tail-select">
                        <option value=" ">---Chọn nguồn vốn---</option>
                        @foreach($resource as $rsc)
                            <option value="{{ $rsc->resource_type }}">{{ $rsc->resource_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label for="inp_final_cost" class="form-label font-medium">Chi phí</label>
                    <input id="inp_final_cost" name="inp_final_cost" type="number" class="inp_final_cost form-control">
                </div>
                <div class="col-span-12 border-gray-200 mt-3 pb-3">
                    <div id="attachment" class="dropzone" data-id="assign_attachment">
                        <div class="fallback"><input name="file" type="file" /></div>
                        <div class="dz-message" data-dz-message>
                            <div class="text-lg font-medium">Click vào đây để đính kèm.</div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap col-span-12 border-gray-200 mt-3 pb-3">
                    <a class="btn-extend-modal btn btn-warning w-32 mr-2 mb-2" data-toggle="modal" data-target="#modal_extend_date"> <i data-feather="calendar" class="w-4 h-4 mr-2"></i> Gia hạn </a>
                    <div class="dropdown">
                        <button id="btn-dropdown-handle-request" class="dropdown-toggle btn btn-primary" aria-expanded="true">Xử lý yêu cầu</button>
                        <div class="dropdown-menu w-48">
                            <div class="dropdown-menu__content box dark:bg-dark-1 p-2">
                                <a href="#" class="btn_return flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
                                    <i data-feather="navigation" class="w-4 h-4 mr-2"></i> Chuyển xử lý </a>
                                <a href="#" class="btn_complete flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
                                    <i data-feather="flag" class="w-4 h-4 mr-2"></i> Hoàn thành </a>
                                <a href="#" class="btn_reject flex items-center block p-2 transition duration-300 ease-in-out bg-white dark:bg-dark-1 hover:bg-gray-200 dark:hover:bg-dark-2 rounded-md">
                                    <i data-feather="trash" class="w-4 h-4 mr-2"></i> Từ chối </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="extend_return_request tab-pane box mt-4" role="tabpanel" aria-labelledby="content-tab">
            <div class="grid grid-cols-12 p-5">
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3"><label class="form-label font-medium">Họ tên</label>
                    <p class="txt_name"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3"><label class="form-label font-medium">Phòng/Phân xưởng</label>
                    <p class="txt_department"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3"><label class="form-label font-medium">Ngày tạo</label>
                    <p class="txt_create_dt"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Thời gian đáp ứng</label>
                    <p class="txt_complete_dt"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Loại yêu cầu</label>
                    <p class="txt_request_type"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 scope border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Gia hạn tới</label>
                    <p class="txt_extend_dt"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Nguồn vốn</label>
                    <p class="txt_resource"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Chi phí</label>
                    <p class="txt_cost"></p>
                </div>
                <div class="col-span-12 scope border-b border-gray-200 mt-3 pb-3">
                <label class="form-label font-medium">Cc mail</label>
                    <p class="txt_cc_mail line-break"></p>
                </div>
                <div class="col-span-12 grid grid-cols-12 border-b border-gray-200 mt-3 pb-3">
                    <div class="col-span-12 xxl:col-span-6"><label class="form-label font-medium">Nội dung yêu cầu </label></div>
                    <div class="col-span-12 xxl:col-span-6">
                        <div class="flex flex-wrap"><a href="javascript:;" class="btn_view_editor btn btn-primary w-32 mr-2 mb-2" data-content="content" data-toggle="modal" data-target="#modal_preview"><i data-feather="file-text" class="w-4 h-4 mr-2"></i>Nội dung</a></div>
                    </div>
                </div>
                <div class="col-span-12 grid grid-cols-12 border-b border-gray-200 mt-3 pb-3">
                    <div class="col-span-12 xxl:col-span-6">
                        <label class="form-label font-medium">Giao việc</label>
                        <p class="txt_assign_person"></p>
                    </div>
                    <div class="col-span-12 xxl:col-span-6">
                        <div class="flex flex-wrap">
                            <a href="javascript:;" class="btn_view_editor btn btn-primary w-32 mr-2 mb-2" data-content="assign_content" data-toggle="modal" data-target="#modal_preview">
                                <i data-feather="file-text" class="w-4 h-4 mr-2"></i>
                                Nội dung
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 grid grid-cols-12 border-b border-gray-200 mt-3 pb-3">
                    <div class="col-span-12 xxl:col-span-6">
                        <label class="form-label font-medium">Xử lý chính</label>
                        <p class="txt_handler"></p>
                    </div>
                    <div class="col-span-12 xxl:col-span-6">
                        <div class="flex flex-wrap">
                            <a href="javascript:;" class="btn_view_editor btn btn-primary w-32 mr-2 mb-2" data-content=handle_content data-toggle="modal" data-target="#modal_preview">
                                <i data-feather="file-text" class="w-4 h-4 mr-2"></i>
                                Nội dung
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 grid grid-cols-12 scope border-b border-gray-200 mt-3 pb-3">
                    <div class="col-span-12 xxl:col-span-6">
                        <label class="form-label font-medium">Xử lý phụ</label>
                        <p class="txt_sub_handler"></p>
                    </div>
                    <div class="col-span-12 xxl:col-span-6">
                        <div class="flex flex-wrap">
                            <a href="javascript:;" class="btn_view_editor btn btn-primary w-32 mr-2 mb-2" data-content="sub_handle_content" data-toggle="modal" data-target="#modal_preview">
                                <i data-feather="file-text" class="w-4 h-4 mr-2"></i>
                                Nội dung
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="my_request tab-pane box mt-4" role="tabpanel" aria-labelledby="content-tab">
            <div class="grid grid-cols-12 p-5">
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Họ tên</label>
                    <p class="txt_name"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Phòng/Phân xưởng</label>
                    <p class="txt_department"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Ngày tạo</label>
                    <p class="txt_create_dt"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Ngày xử lý</label>
                    <p class="txt_handle_dt"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Thời gian đáp ứng</label>
                    <p class="txt_complete_dt"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Loại yêu cầu</label>
                    <p class="txt_request_type"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Nguồn vốn được duyệt</label>
                    <p class="txt_resource"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Chi phí được duyệt</label>
                    <p class="txt_cost"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Nguồn vốn thực tế</label>
                    <p class="txt_final_resource"></p>
                </div>
                <div class="col-span-12 xxl:col-span-6 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Chi phí thực tế</label>
                    <p class="txt_final_cost"></p>
                </div>
                <div class="col-span-12 scope border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Cc mail</label>
                    <p class="txt_cc_mail line-break"></p>
                </div>
                <div class="col-span-12 border-b border-gray-200 mt-3 pb-3">
                    <div id="req-accordion-5" class="accordion">
                        <div class="accordion-item">
                            <div id="req-accordion-content-1" class="accordion-header">
                                <button class="accordion-btn" type="button" data-bs-toggle="collapse" data-bs-target="#req-accordion-collapse-1" aria-expanded="true" aria-controls="req-accordion-collapse-1"> Nội dung yêu cầu </button>
                            </div>
                            <div id="req-accordion-collapse-1" class="accordion-collapse collapse show" aria-labelledby="req-accordion-content-1" data-bs-parent="#req-accordion-5">
                                <div class="acd_request_content accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div id="req-accordion-content-2" class="accordion-header">
                                <button class="accordion-btn" type="button" data-bs-toggle="collapse" data-bs-target="#req-accordion-collapse-2" aria-expanded="true" aria-controls="faq-accordion-collapse-2"> Giao việc: <span class="txt_assign_person"></span> </button>
                            </div>
                            <div id="req-accordion-collapse-2" class="accordion-collapse collapse show" aria-labelledby="req-accordion-content-2" data-bs-parent="#req-accordion-5">
                                <div class="acd_request_assign_content accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div id="req-accordion-content-3" class="accordion-header">
                                <button class="accordion-btn" type="button" data-bs-toggle="collapse" data-bs-target="#req-accordion-collapse-3" aria-expanded="true" aria-controls="faq-accordion-collapse-3"> Xử lý chính: <span class="txt_handler"></span></button>
                            </div>
                            <div id="req-accordion-collapse-3" class="accordion-collapse collapse show" aria-labelledby="req-accordion-content-3" data-bs-parent="#req-accordion-5">
                                <div class="acd_request_handler_content accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div id="req-accordion-content-4" class="accordion-header">
                                <button class="accordion-btn" type="button" data-bs-toggle="collapse" data-bs-target="#req-accordion-collapse-4" aria-expanded="true" aria-controls="faq-accordion-collapse-4"> Xử lý phụ: <span class="txt_sub_handler"></span></button>
                            </div>
                            <div id="req-accordion-collapse-4" class="accordion-collapse collapse show" aria-labelledby="req-accordion-content-4" data-bs-parent="#req-accordion-5">
                                <div class="acd_request_sub_hander_content accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">Đính kèm</label>
                    <div class="txt_attachment"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Post Info -->
</div>

<!-- BEGIN: Modal Content -->
<div id="modal_extend_date" class="modal" data-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content"> <a data-dismiss="modal" href="javascript:;"> <i data-feather="x" class="w-8 h-8 text-gray-500"></i> </a>
            <div class="modal-body p-0">
                <div class="p-10 text-center">
                    <div name="ckExtendContent" data-content="extend_content" class="editor"></div>
                </div>
                <div class="flex-colum text-center">
                    <label class="w-32 form-label font-medium">Gia hạn tới</label>
                    <input class="w-32 dpk_extend_dt datepicker form-control block mx-auto" data-id="dpk_extend_dt" data-single-mode="true">
                </div>
                <div class="pb-8 text-center pt-5">
                    <button type="button" class="btn-extend close_modal_extend btn btn-primary w-24" data-dismiss="modal">Gia hạn</button>
                </div>
            </div>
        </div>
    </div>
</div> <!-- END: Modal Content -->
@endsection
