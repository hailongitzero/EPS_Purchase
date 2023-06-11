import dayjs from 'dayjs';
import feather from "feather-icons";
import Tabulator from "tabulator-tables";
import tippy, { roundArrow } from "tippy.js";
import { format } from "./form-component";
import tail from "tail.select";
import Velocity from "velocity-animate";

(function (cash) {
    "use strict";

    // Tabulator
    if (cash("#new-requests-table").length) {
        var statusArr = { A: "Yêu cầu mới", B: "Tiếp nhận yêu cầu", C: "Gia hạn", D: "Đang xử lý", E: "Chuyển xử lý", F: "Hoàn thành", X: "Từ chối"};
        var statusClassArr = { A: "bg-theme-9", B: "bg-theme-22", C: "bg-theme-26", D: "bg-theme-22", E: "bg-theme-14", F: "bg-theme-10", X: "bg-theme-35"};
        var priorityArr = {L: "Thấp", M: "Trung bình", H: "Cao"};
        var priorityClassArr = {L: "bg-theme-10", M: "bg-theme-23", H: "bg-theme-24" };

        // Setup Tabulator
        let table = new Tabulator("#new-requests-table", {
            ajaxURL: "/new-requests",
            ajaxFiltering: true,
            ajaxSorting: true,
            printAsHtml: true,
            printStyled: true,
            selectable: 1,
            pagination: "remote",
            paginationSize: 10,
            paginationSizeSelector: [10, 20, 30, 40],
            layout: "fitColumns",
            responsiveLayout: "collapse",
            placeholder: "Không có yêu cầu mới",
            ajaxResponse: function(url, params, response){
                cash('#new_requests_tab_sum').text(response.total);
                cash('.total_new_result').text(response.total);
                return response;
            },
            columns: [
                {
                    formatter: "responsiveCollapse",
                    width: 40,
                    minWidth: 30,
                    align: "center",
                    resizable: false,
                    headerSort: false,
                },

                // For HTML table
                {
                    title: "Stt",
                    width: 60,
                    minWidth: 60,
                    align: "center",
                    resizable: false,
                    headerSort: false,
                    responsive: 0,
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter:"rownum",
                },
                {
                    title: "Tiêu đề",
                    minWidth: 200,
                    responsive: 0,
                    field: "subject",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter(cell, formatterParams) {
                        var status = cell.getData().status;
                        var priority = cell.getData().priority;

                        return `<div>
                            <div class="font-medium whitespace-nowrap ${dayjs(cell.getData().complete_date) < dayjs() ? "text-theme-24" : ""}">${
                                cell.getData().subject
                            }</div>
                            <div class="text-gray-600 text-xs whitespace-nowrap">${
                                dayjs(cell.getData().created_at).format('DD-MM-YYYY')
                            }</div>
                            <div class="my-2 text-xs whitespace-nowrap">
                                <span class="px-2 py-1 rounded-full ${statusClassArr[status]} text-white mr-1">
                                    ${statusArr[status]}
                                </span>
                                <span class="px-2 py-1 rounded-full ${priorityClassArr[priority]} text-white mr-1">
                                    ${priorityArr[priority]}
                                </span>
                            </div>
                        </div>`;
                    },
                },
                {
                    title: "Người tạo",
                    width: 130,
                    field: "requester",
                    hozAlign: "center",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter(cell, formatterParams) {
                        return `<div class="flex-col lg:justify-center">
                                    <div class="w-10 h-10 image-fit zoom-in mx-auto">
                                        <img alt="${cell.getData().requester != null ? cell.getData().requester['name'] : ''}"
                                            title="${cell.getData().requester != null ? cell.getData().requester['name'] : ''}"
                                            class="tooltip rounded-full" src="${
                                                cell.getData().requester['photo'] ? cell.getData().requester['photo'] : 'storage/users/no-user.jpg'
                                            }">
                                    </div>
                                </div>`;
                    },
                },
                {
                    title: "Hạn xử lý",
                    width: 130,
                    field: "complete_date",
                    hozAlign: "center",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter(cell, formatterParams) {
                        var sReturn = `<div class="flex-col lg:justify-center">
                            <div class="whitespace-nowrap text-center">${
                                dayjs(cell.getData().complete_date).format('DD-MM-YYYY')
                            }</div>
                        </div>`;
                        return sReturn;
                    },
                },

                // For print format
                {
                    title: "",
                    field: "",
                    visible: false,
                    print: true,
                    download: true,
                },
                {
                    title: "",
                    field: "",
                    visible: false,
                    print: true,
                    download: true,
                },
            ],
            rowSelected: function(row){
                var data = row.getData();
                var formFormat = format;
                var field = "";
                var subField = "";
                var subValue = "";
                var value = "";
                var meaning = "";

                // add global data
                window.requestData = data;
                // set page title
                if (cash('#new-req-tab').hasClass('active')){
                    cash('#request_title').text(data.subject);
                }
                //remove sub handle person
                cash('.btn-remove-handler').closest('div.grid').detach ();

                formFormat.forEach(function(item) {
                    field = item.field;
                    // get component data
                    if ( item.has_sub ) {
                        subField = item.sub_data.field;
                        subValue = item.sub_data.value;
                        if ( data[field] ){
                            meaning = data[field][subField];
                            value = data[field][subValue];
                        }
                    } else {
                        meaning = data[field];
                    }

                    // set componnent data
                    if ( item.type == "text") {
                        if ( meaning ){
                            cash('.new_requests .'+item.id).text(meaning);
                            cash('.new_requests .'+item.id).closest('.scope').removeClass('hidden');
                        } else {
                            cash('.new_requests .'+item.id).closest('.scope').addClass('hidden');
                        }
                    } else if ( item.type == "number") {
                        if ( meaning ){
                            cash('.new_requests .'+item.id).text(meaning.toLocaleString());
                            cash('.new_requests .'+item.id).closest('.scope').removeClass('hidden');
                        } else {
                            cash('.new_requests .'+item.id).text('');
                            cash('.new_requests .'+item.id).closest('.scope').addClass('hidden');
                        }
                    } else if (item.type == "date") {
                        if ( meaning ){
                            cash('.new_requests .'+item.id).text(dayjs(meaning).format('DD-MM-YYYY'));
                        }
                    } else if (item.type == "input") {
                        if ( meaning ){
                            cash('.new_requests #'+item.id).val(meaning);
                        } else {
                            cash('.new_requests #'+item.id).val('');
                        }
                    } else if (item.type == "select") {
                        if ( meaning) {
                            window[item.id].options.select(value, '#');
                        } else {
                            window[item.id].options.select(' ', '#');
                        }
                    } else if (item.type == "datepicker") {
                        if ( meaning ) {
                            window[item.id].setDate(dayjs(meaning), true);
                        }
                    } else if (item.type == "html") {
                        if ( meaning ){
                            cash('.new_requests .'+item.id).html(meaning);
                            cash('.new_requests .'+item.id).closest('.accordion-item').removeClass('hidden');
                        } else {
                            cash('.new_requests .'+item.id).closest('.accordion-item').addClass('hidden');
                        }
                    } else if (item.type == "file") {
                        var link = ''
                        if (data[field]) {
                            data[field].forEach(function(subData) {
                                link += '<span class="text-xs px-1 rounded-full border mr-1"><a href="./download/'+ subData['file_id'] +' " class="text-theme-17">' + subData[subField] + '</a></span> ';
                            });
                        }
                        cash('.new_requests .'+item.id).html(link);
                    }

                    ckAssignContent.setData("");
                });
                resetAccordion();
                // window.location.href="#request-info";
            },
            renderComplete: function() {
                feather.replace({
                    "stroke-width": 1.5,
                });
                if (cash('#new-req-tab').hasClass('active')) {
                    if (table.getRows()[0]) {
                        table.selectRow(table.getRows()[0]);
                    }else{
                        window.requestData = undefined;
                    }
                }
                // Tooltips
                cash(".tooltip").each(function () {
                    let options = {
                        content: cash(this).attr("title"),
                    };

                    if (cash(this).data("trigger") !== undefined) {
                        options.trigger = cash(this).data("trigger");
                    }

                    if (cash(this).data("placement") !== undefined) {
                        options.placement = cash(this).data("placement");
                    }

                    if (cash(this).data("theme") !== undefined) {
                        options.theme = cash(this).data("theme");
                    }

                    if (cash(this).data("tooltip-content") !== undefined) {
                        options.content = cash(cash(this).data("tooltip-content"))[0];
                    }

                    cash(this).removeAttr("title");

                    tippy(this, {
                        arrow: roundArrow,
                        animation: "shift-away",
                        ...options,
                    });
                });
            },
        });

        // Redraw table onresize
        window.addEventListener("resize", () => {
            table.redraw();
            feather.replace({
                "stroke-width": 1.5,
            });
        });

        // Filter function
        function filterHTMLForm() {
            let value = cash("#new-requests-table-html-filter-value").val();
            let dept = cash("#new-requests-table-html-department").val();
            let type = cash("#new-requests-table-html-request-tp").val();
            table.setFilter([
                {field:'subject', type:'like', value:value},
                {field:'dept', type:'=', value:dept},
                {field:'type', type:'=', value:type},
            ]);
        }

        // Filter by Id
        if (cash('#new-req-tab').hasClass('active')){
            const params = new URLSearchParams(location.search);
            if (params.get('id')){
                table.setFilter("id", "=", params.get('id'));
            }
        }

        // On submit filter form
        cash("#new-requests-table-html-filter-form")[0].addEventListener(
            "keypress",
            function (event) {
                let keycode = event.keyCode ? event.keyCode : event.which;
                if (keycode == "13") {
                    event.preventDefault();
                    filterHTMLForm();
                }
            }
        );
        // On seach change value
        cash("#new-requests-table-html-filter-value").on("change", function (event) {
            if ( cash(this).val().length > 2 ) {
                filterHTMLForm();
            }
        });

        cash('#new-requests-table-html-department').on('change', function(e){
            filterHTMLForm();
        });

        cash('#new-requests-table-html-request-tp').on('change', function(e){
            filterHTMLForm();
        });

        // add sub handle person
        cash('.new_requests .btn-add-handler').on('click', function() {
            var component = '<div class="col-span-12 grid grid-cols-12 mt-2"><div class="col-span-12 xxl:col-span-6"><select class="cbx_sub_handler tail-select w-full"><option value="">Chọn người xử lý</option></select></div><div class="col-span-12 xxl:col-span-6"><button class="btn-remove-handler btn p-2 ml-2 box border border-gray-400 text-gray-700 dark:text-gray-300" aria-expanded="false"><span class="w-5 h-5 flex items-center justify-center"><i class="w-4 h-4" data-feather="minus"></i></span></button></div></div></div>';
            cash(this).closest('div').after(component);

            //initial tail select cbx_sub_handler
            cash(".new_requests select.cbx_sub_handler").each(function (index, val) {
                if (index+1 < this.length) {
                    return;
                }
                let options = {};

                if (cash(this).data("placeholder")) {
                    options.placeholder = cash(this).data("placeholder");
                }

                if (cash(this).attr("class") !== undefined) {
                    options.classNames = cash(this).attr("class");
                }

                if (cash(this).data("search")) {
                    options.search = true;
                }

                if (cash(this).attr("multiple") !== undefined) {
                    options.descriptions = true;
                    options.hideSelected = true;
                    options.hideDisabled = true;
                    options.multiLimit = 15;
                    options.multiShowCount = false;
                    options.multiContainer = true;
                    options.multiple = false;
                }

                let tsl = tail(this, options);

                var items = cbx_handler.options.items["#"];

                tsl.options.add(items, false, false, false, false, false, true);
                feather.replace({
                    "stroke-width": 1.5,
                });
            });

            // initial remove sub handler function
            cash('.btn-remove-handler').on('click', function() {
                cash(this).closest('div.grid').detach ();
            });
        });

        // đăng ký người xử lý yêu cầu
        cash('.new_requests .btn_assign').on('click', function(){
            if ( !window.requestData ) {
                alert("Vui lòng chọn yêu cầu cần xử lý");
                return;
            }

            var form = cash('.new_requests');
            var formCheck = true;
            var request_id = window.requestData.request_id;
            var complete_date = form.find('.dpk_complete_dt').val();
            var request_type = form.find('select.cbx_request_tp').val();
            var handler = form.find('select.cbx_handler').val();
            var final_cost = cash('#final_cost').val();
            var final_resource = cash('#final_resource option:checked').val();
            var assign_content = ckAssignContent.getData();
            var attachment = window[form.find('#attachment').data('id')].files;

            form.find('select.cbx_sub_handler').each(function(index, element){
                if (cash(element).val() == "") {
                    formCheck = false;
                } else if (cash(element).val() == handler) {
                    formCheck = false;
                }
            });

            if ( handler == "" || assign_content == "") {
                formCheck = false;
            }

            if ( !formCheck ) {
                alert("Vui lòng kiểm tra lại thông tin xử lý.")
                return;
            }
            var formData = new FormData();
            formData.append('_token', cash('meta[name="csrf-token"]').attr('content'));
            formData.append('header', cash('meta[name="csrf-token"]').attr('content'));
            formData.append('request_id', request_id);
            formData.append('complete_date', complete_date);
            formData.append('request_type', request_type);
            formData.append('handler', handler);
            formData.append('final_cost', final_cost);
            formData.append('final_resource', final_resource);
            formData.append('assign_content', assign_content);
            form.find('select.cbx_sub_handler').each(function(index, element){
                if (cash(element).val()){
                    formData.append('sub_handler[]', cash(element).val());
                }
            });
            cash(attachment).each(function(id, file){
                formData.append('attachment[]', file);
            });

            axios.post(`assign-request`, formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                    'Accept': 'application/json',
                    },
            }).then(res => {
                alert(res.data.message);
                filterHTMLForm();
            }).catch(err => {
                if (err.response.data.errors) {
                    for (const [key, val] of Object.entries(err.response.data.errors)) {
                        alert(val);
                    }
                } else if (err.response.data.message){
                    alert(err.response.data.message);
                }
            });
        });

        // admin xử lý yêu cầu
        cash('.new_requests .btn_complete').on('click', function(){
            handelRequest('F')
        });
        cash('.new_requests .btn_reject').on('click', function(){
            handelRequest('X')
        });


        cash('.nav-tabs #new-req-tab').on("click", function (event) {
            table.refreshFilter()
            window[cash('#attachment').data('id')].removeAllFiles(true);
            cash('.new_requests .btn-remove-handler').closest('div.grid').detach ();
            ckAssignContent.setData("");
            // clear form data
            if (format) {
                format.forEach(element => {
                    if (element.type == "text" || element.type == "date") {
                        cash('.'+element.id).text("");
                    }
                    if (element.type == "file" || element.type == "text_arr" || element.type == "html" ) {
                        cash('.'+element.id).html("");
                    }
                    if (element.type == "attachment") {
                        window[element.id].removeAllFiles(true);
                    }
                });
            }
        });

        function handelRequest(status) {

            var form = cash('.new_requests');
            var handle_content = ckAssignContent.getData();
            var request_id = window.requestData.request_id;
            var final_cost = cash('#inp_final_cost').val();
            var final_resource = cash('#cbx_final_resource option:checked').val();
            var attachment = window[form.find('#attachment').data('id')].files;
            if (handle_content == "") {
                alert("Vui lòng nhập nội dung xử lý (mục Giao việc)");
                return false;
            }
            var formData = new FormData();
            formData.append('request_id', request_id);
            formData.append('handle_content', handle_content);
            formData.append('final_cost', final_cost);
            formData.append('final_resource', final_resource);
            formData.append('status', status);
            cash(attachment).each(function(id, file){
                formData.append('attachment[]', file);
            });

            axios.post(`handle-request`, formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                    'Accept': 'application/json',
                  },
            }).then(res => {
                alert(res.data.message);
                filterHTMLForm();
            }).catch(err => {
                if (err.response.data.errors) {
                    for (const [key, val] of Object.entries(err.response.data.errors)) {
                        alert(val);
                    }
                } else if (err.response.data.message){
                    alert(err.response.data.message);
                }
            });
        }

        function resetAccordion(){
            cash('.new_requests .accordion-item .accordion-btn').each(function(){
                if (cash(this).hasClass("collapsed")) {
                    Velocity(
                        cash(this).closest(".accordion-item").find(".accordion-collapse"),
                        "slideDown",
                        {
                          duration: 300,
                          complete: function (el) {
                            cash(el).addClass("show");
                            cash(el)
                              .closest(".accordion-item")
                              .find(".accordion-btn")
                              .removeClass("collapsed")
                              .attr("aria-expanded", true);
                          },
                        }
                    );
                }
            });
        }
    }
})(cash);
