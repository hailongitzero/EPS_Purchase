@extends('../layout/side-menu')

@section('subhead')
<title>EPS - Genco3 Tạo Yêu Cầu</title>
@endsection

@section('subcontent')
<div class="flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Tạo Yêu Cầu</h2>
</div>
<!-- Slider -->
<div id="single-item-slider" class="p-5">
    <div class="preview">
        <div class="mx-6">
            <!-- Thông tin yêu cầu -->
            <div class="box py-5 sm:py-10 mt-5">
                <div class="make-request-item">
                    <!-- thông tin người gửi -->
                    <div class="px-2">
                        <div class="h-full dark:bg-dark-1 rounded-md">
                            <div class="border-gray-200 dark:border-dark-5">
                                <div class="font-medium text-center text-lg">Thông tin yêu cầu</div>
                                <!-- <div class="text-gray-600 text-center mt-2">To start off, please enter your username, email address and password.</div> -->
                            </div>
                            <div class="px-5 sm:px-10 mt-5 mb-5 pt-5 border-t border-gray-200 dark:border-dark-5">
                                <div class="grid grid-cols-12 gap-4 gap-y-5 mt-5">
                                    <div class="form-control col-span-12 sm:col-span-6 lg:col-span-3">
                                        <label for="department" class="form-label">Phòng ban / Phân xưởng</label>
                                        <select id="department" name="department" data-search="true" class="form-control w-full" disabled>
                                            @foreach( $deptUser as $dept)
                                                <option value="{{ $dept->department_id }}" {{ $userInfo->department_id == $dept->department_id ? 'selected' : '' }}>{{ $dept->department_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-control col-span-12 sm:col-span-6 lg:col-span-3">
                                        <label for="requester" class="form-label">Người yêu cầu</label>
                                        <select id="requester" name="requester" data-search="true" class="form-control w-full" disabled>
                                        @foreach( $deptUser as $dept)
                                            <optgroup label="{{ $dept->department_name }}">
                                            @foreach($dept->users as $usr)
                                                <option value="{{ $usr->username }}" {{ $userInfo->username == $usr->username ? 'selected' : '' }}>{{ $usr->name }}</option>
                                            @endforeach
                                            </optgroup>
                                        @endforeach
                                        </select>
                                    </div>
                                    <div class="form-control col-span-12 sm:col-span-6 lg:col-span-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input id="email" name="email" type="text" class="form-control" placeholder="example@gmail.com" value="{{ $userInfo->email }}" disabled>
                                    </div>
                                    <div class="form-control col-span-12 sm:col-span-6 lg:col-span-3">
                                        <label for="telephone" class="form-label">Điện thoại</label>
                                        <input id="telephone" name="telephone" type="text" class="form-control" placeholder="Số điện thoại" value="{{ $userInfo->telephone }}">
                                    </div>
                                    <div class="form-control col-span-12 sm:col-span-6 lg:col-span-3">
                                        <label for="request_tp" class="form-label">Loại yêu cầu</label>
                                        <select id="request_tp" name="request_tp" class="tail-select w-full" data-search="true">
                                            <option value="" data-cc-mail-check="0">---Chọn loại yêu cầu---</option>
                                            @foreach( $reqTp as $tp )
                                            <option value="{{ $tp->request_type }}" data-cc-mail-check="{{ $tp->cc_mail_check }}">{{ $tp->type_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-control col-span-12 sm:col-span-6 lg:col-span-3">
                                        <label for="cc_email" class="form-label">Cc Mail</label>
                                        <select id="cc_email" name="cc_email" data-placeholder="CC Email" data-search="true" class="tail-select w-full" multiple>
                                            @foreach( $deptUser as $dept)
                                                <optgroup label="{{ $dept->department_name }}">
                                                @foreach($dept->users as $usr)
                                                    <option value="{{ $usr->email }}">{{ $usr->name }}</option>
                                                @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-control col-span-12 sm:col-span-6 lg:col-span-3">
                                        <label for="priority" class="form-label">Độ ưu tiên</label>
                                        <select id="priority" name="priority" class="form-control tail-select w-full">
                                            <option value="L">Thấp</option>
                                            <option value="M" selected>Trung bình</option>
                                            <option value="H">Cao</option>
                                        </select>
                                    </div>
                                    <div class="form-control col-span-12 sm:col-span-6 lg:col-span-3">
                                        <label for="completion_date" class="form-label">Thời gian đáp ứng</label>
                                        <input id="completion_date" name="completion_date" class="new_request_datepicker form-control block mx-auto" data-single-mode="true">
                                    </div>
                                    <div class="form-control col-span-12 sm:col-span-6 lg:col-span-3">
                                        <label for="resource" class="form-label">Nguồn vốn</label>
                                        <select id="resource" name="resource" class="form-control tail-select w-full">
                                            <option value=" ">---Chọn nguồn vốn---</option>
                                            @foreach($resource as $rsc)
                                                <option value="{{ $rsc->resource_type }}">{{ $rsc->resource_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-control col-span-12 sm:col-span-6 lg:col-span-3">
                                        <label for="cost" class="form-label">Chi phí</label>
                                        <input id="cost" name="cost" type="text" class="form-control currency-mask">
                                    </div>
                                    <div class="form-control col-span-12">
                                        <label for="subject" class="form-label">Tiêu đề</label>
                                        <input id="subject" type="text" class="form-control" placeholder="Tiêu đề">
                                    </div>
                                    <div class="form-control col-span-12">
                                        <label for="content" class="form-label">Nội dung yêu cầu</label>
                                        <div id="content" name="ckRequestContent" class="editor"></div>
                                    </div>
                                    <div class="form-control col-span-12">
                                        <label for="attachment" class="form-label">Đính kèm</label>
                                        <div id="attachment" class="dropzone" data-id="req-files">
                                            <div class="fallback">
                                                <input name="file" type="file" />  
                                            </div>
                                            <div class="dz-message" data-dz-message>
                                                <div class="text-lg font-medium">Click vào đây để đính kèm.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-12 flex items-center justify-center sm:justify-end mt-5 make-request-item-navigator">
                                        <button class="btn btn-primary w-32 ml-2 next-button" data-controls="next">Gửi yêu cầu</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Gửi yêu cầu -->
                    <div class="px-2">
                        <div class="h-full dark:bg-dark-1 rounded-md">
                            <div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200 dark:border-dark-5">
                                <div class="font-medium text-center text-lg">Gửi yêu cầu</div>
                                <!-- <div class="text-gray-600 text-center mt-2">To start off, please enter your username, email address and password.</div> -->
                            </div>
                            <div class="px-5 sm:px-10 mt-5 mb-5 pt-5 border-t border-gray-200 dark:border-dark-5">
                                <div class="grid grid-cols-12 gap-4 gap-y-5 mt-5">
                                    <div class="col-span-12 request-success hidden">
                                        <div class="p-5 text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle w-16 h-16 text-theme-10 mx-auto mt-3"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> 
                                            <div class="text-3xl mt-5">Hoàn Thành</div>
                                            <div class="text-gray-600 mt-2">
                                                Yêu cầu của bạn đã được gửi đến Tổ CNTT để hỗ trợ<br/>
                                                Bạn vui lòng kiểm tra Email để theo dõi người xử lý nhé<br/>
                                                Xin cảm ơn!
                                            </div>
                                            <div class="text-gray-600 mt-2">
                                                Tự động chuyển hướng sau <span id="redirectInterval"></span> giây
                                            </div>
                                            <div class="col-span-12 flex items-center justify-center mt-5 make-request-item-navigator">
                                                <a href="{{ url('make-request') }}" class="btn btn-primary w-32 inline-block mr-1 mb-2">Yêu cầu mới</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-12 request-failure hidden">
                                        <div class="p-5 text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle w-16 h-16 text-theme-23 mx-auto mt-3"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg> 
                                            <div class="text-3xl mt-5">Lỗi...</div>
                                            <div class="text-gray-600 mt-2">
                                                Gửi yêu cầu không thành công.<br/>
                                                Vui lòng thử lại hoặc liên hệ phòng IT để được hỗ trợ.
                                            </div>
                                        </div>
                                        <div class="col-span-12 flex items-center justify-center mt-5 make-request-item-navigator">
                                            <button class="btn btn-secondary w-24 prev-button" data-controls="prev">Quay lại</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End gửi dung yêu cầu -->
                </div>
            </div>
            <!-- kết thúc thông tin yêu cầu -->
        </div>
    </div>
</div>
<!-- End Slider -->

@endsection