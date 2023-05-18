@extends('../layout/side-menu')

@section('subhead')
<title>EPS - Genco3 Quản lý yêu cầu</title>
@endsection

@section('subcontent')
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Danh sách yêu cầu
    </h2>
</div>
<div class="pos intro-y grid grid-cols-12 gap-5 mt-5">
    <!-- BEGIN: Post Content -->
    <div class="intro-y col-span-12 lg:col-span-8">
        <div class="intro-y grid grid-cols-12 box p-5">
            <div class="box p-2 bg-theme-14 intro-x col-span-12">
                <div class="flex flex-wrap">
                    <div id="request_title" class="text-white relative text-xl pl-3.5">Thông tin yêu cầu</div>
                </div>
            </div>
        </div>
        <div class="post intro-y overflow-hidden box mt-4">
            <div class="manage_request post__tabs nav nav-tabs flex-col sm:flex-row bg-gray-300 dark:bg-dark-2 text-gray-600" role="tablist">
                <a title="Danh sách yêu cầu của tôi" data-toggle="tab" data-target=".my_request" href="javascript:;" class="tooltip w-full sm:w-40 py-4 text-center flex justify-center items-center active" id="my-req-tab" role="tab" aria-selected="false">
                    <i data-feather="file-text" class="w-4 h-4 mr-2"></i> yêu cầu của tôi <span id="my_request_tab_sum" class="px-2 py-1 rounded-full bg-theme-35 text-white ml-1">
                </a>
            </div>
            <div class="post__content tab-content">
                <div id="my_request" class="my_request tab-pane active" role="tabpanel" aria-labelledby="content-tab">
                    <div class="intro-y box p-5 mt-5">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Post Content -->
    <!-- BEGIN: Post Info -->
    <div id="request-info" class="col-span-12 lg:col-span-4 post__content tab-content">
        <div class="intro-y grid grid-cols-12 box p-5">
            <div class="box p-2 bg-theme-14 intro-x col-span-12">
                <div class="flex flex-wrap">
                    <div class="mx-auto">
                        <div class="text-white relative text-xl pl-3.5">
                            Chi tiết yêu cầu
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="my_request tab-pane box mt-4 active" role="tabpanel" aria-labelledby="content-tab">
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
                                <button class="accordion-btn" type="button" data-bs-toggle="collapse" data-bs-target="#req-accordion-collapse-3" aria-expanded="true" aria-controls="faq-accordion-collapse-3"> Người xử lý chính: <span class="txt_handler"></span></button>
                            </div>
                            <div id="req-accordion-collapse-3" class="accordion-collapse collapse show" aria-labelledby="req-accordion-content-3" data-bs-parent="#req-accordion-5">
                                <div class="acd_request_handler_content accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <div id="req-accordion-content-4" class="accordion-header">
                                <button class="accordion-btn" type="button" data-bs-toggle="collapse" data-bs-target="#req-accordion-collapse-4" aria-expanded="true" aria-controls="faq-accordion-collapse-4"> Người xử lý phụ: <span class="txt_sub_handler"></span></button>
                            </div>
                            <div id="req-accordion-collapse-4" class="accordion-collapse collapse show" aria-labelledby="req-accordion-content-4" data-bs-parent="#req-accordion-5">
                                <div class="acd_request_sub_hander_content accordion-body text-gray-700 dark:text-gray-600 leading-relaxed">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 border-b border-gray-200 mt-3 pb-3">
                    <label class="form-label font-medium">đính kèm</label>
                    <div class="txt_attachment"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Post Info -->
</div>
@endsection