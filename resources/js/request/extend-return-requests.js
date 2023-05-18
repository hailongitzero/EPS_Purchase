import dayjs from 'dayjs';
import feather from "feather-icons";
import Tabulator from "tabulator-tables";
import tippy, { roundArrow } from "tippy.js";
import { format } from "./form-component";
import tail from "tail.select";

(function (cash) {
    "use strict";

    // Tabulator
    if (cash("#extend-return-request-table").length) {
        var statusArr = { A: "Yêu cầu mới", B: "Tiếp nhận yêu cầu", C: "Gia hạn", D: "Đang xử lý", E: "Chuyển xử lý", F: "Hoàn thành", X: "Từ chối"};
        var statusClassArr = { A: "bg-theme-9", B: "bg-theme-22", C: "bg-theme-26", D: "bg-theme-22", E: "bg-theme-14", F: "bg-theme-10", X: "bg-theme-35"};
        var priorityArr = {L: "Thấp", M: "Trung bình", H: "Cao"};
        var priorityClassArr = {L: "bg-theme-10", M: "bg-theme-23", H: "bg-theme-24" };

        // Setup Tabulator
        let table = new Tabulator("#extend-return-request-table", {
            ajaxURL: "/extend-return-request",
            layoutColumnsOnNewData: true,
            ajaxFiltering: true,
            ajaxSorting: true,
            selectable: 1,
            selectablePersistence: true,
            printAsHtml: true,
            printStyled: true,
            pagination: "remote",
            paginationSize: 10,
            paginationSizeSelector: [10, 20, 30, 40],
            layout: "fitColumns",
            responsiveLayout: "collapse",
            placeholder: "Không có yêu cầu mới",
            ajaxResponse: function(url, params, response){
                cash('#extend_return_request_tab_sum').text(response.total);
                cash('.total_return_result').text(response.total);
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
                            <div class="font-medium whitespace-nowrap">${
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
                    minWidth: 200,
                    width: 200,
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
                    title: "Người xử lý",
                    minWidth: 200,
                    width: 200,
                    field: "handler",
                    hozAlign: "center",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter(cell, formatterParams) {
                        var sub_prs = "";
                        if (cell.getData().sub_handler.length) {
                            cell.getData().sub_handler.forEach(prs => {
                                sub_prs += `<div class="w-10 h-10 image-fit zoom-in -ml-5"><img alt="${prs.name}" title="${prs.name}"
                                    class="tooltip rounded-full" src="${
                                        prs.user.photo != null ? prs.user.photo : 'storage/users/no-user.jpg'
                                    }"></div>`;
                            });
                        }
                        if (cell.getData().handler)
                        {
                            return `<div class="flex-col lg:justify-center"><div class="flex">
                            <div class="w-10 h-10 image-fit zoom-in mx-auto">
                                <img alt="${cell.getData().handler != null ? cell.getData().handler['name'] : ''}"
                                    title="${cell.getData().handler != null ? cell.getData().handler['name'] : ''}"
                                    class="tooltip rounded-full" src="${
                                    cell.getData().handler['photo'] != null ? cell.getData().handler['photo'] : 'storage/users/no-user.jpg'
                                }">
                            </div>${sub_prs}</div>
                            </div>`;
                        } else {
                            return `<div class="flex-col lg:justify-center">Chưa tiếp nhận</div>`;
                        }
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
                if (cash('#extend-return-req-tab').hasClass('active')){
                    cash('#request_title').text(data.subject);
                }
                formFormat.forEach(function(item) {
                    field = item.field;
                    // get component data
                    if (item.has_sub) {
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
                            cash('.extend_return_request .'+item.id).text(meaning);
                            cash('.extend_return_request .'+item.id).closest('.scope').removeClass('hidden');
                        } else {
                            cash('.extend_return_request .'+item.id).text('');
                            cash('.extend_return_request .'+item.id).closest('.scope').addClass('hidden');
                        }
                    } else if (item.type == "date") {
                        if ( meaning ){
                            cash('.extend_return_request .'+item.id).text(dayjs(meaning).format('DD-MM-YYYY'));
                            cash('.extend_return_request .'+item.id).closest('.scope').removeClass('hidden');
                        } else {
                            cash('.extend_return_request .'+item.id).text('');
                            cash('.extend_return_request .'+item.id).closest('.scope').addClass('hidden');
                        }
                    } else if (item.type == "html") {
                        if ( meaning ){
                            cash('.extend_return_request .'+item.id).html(meaning);
                            cash('.extend_return_request .'+item.id).closest('.accordion-item').removeClass('hidden');
                        } else {
                            cash('.extend_return_request .'+item.id).html('');
                            cash('.extend_return_request .'+item.id).closest('.accordion-item').addClass('hidden');
                        }
                    } else if (item.type == "file") {
                        var link = ''
                        if (data[field]) {
                            data[field].forEach(function(subData) {
                                link += '<span class="text-xs px-1 rounded-full border mr-1"><a href="./download/'+ subData['file_id'] +' " class="text-theme-17">' + subData[subField] + '</a></span> ';
                            });
                        }
                        cash('.extend_return_request .'+item.id).html(link);
                    } else if ( item.type == "text_arr" ) {
                        var person = '';
                        if ( data[field].length ) {
                            data[field].forEach(function(subData) {
                                person += subData[subField] + '<br/>'
                            });
                            cash('.extend_return_request .'+item.id).closest('.scope').removeClass('hidden');
                        } else {
                            cash('.extend_return_request .'+item.id).closest('.scope').addClass('hidden');
                        }
                        cash('.extend_return_request .'+item.id).html(person);
                    }

                });
                if (data.status == "C") {
                    cash('.extend_return_request .drd-handle-request').addClass('hidden');
                    cash('.extend_return_request .btn-accept-extend').removeClass('hidden');
                    cash('.extend_return_request .btn-deny-extend').removeClass('hidden');
                    cash('.extend_return_request select.cbx_handler').closest('.scope').addClass('hidden')
                    cash('.extend_return_request #assign_content').closest('.accordion-item').addClass('hidden');
                    cash('.extend_return_request .acd_request_extend_content').closest('.accordion-item').removeClass('hidden');
                } else if (data.status == "E") {
                    cash('.extend_return_request .drd-handle-request').removeClass('hidden');
                    cash('.extend_return_request .btn-accept-extend').addClass('hidden');
                    cash('.extend_return_request .btn-deny-extend').addClass('hidden');
                    cash('.extend_return_request select.cbx_handler').closest('.scope').removeClass('hidden')
                    cash('.extend_return_request #assign_content').closest('.accordion-item').removeClass('hidden');
                    cash('.extend_return_request .acd_request_extend_content').closest('.accordion-item').addClass('hidden');
                }

                resetAccordion();
                // window.location.href="#request-info";
            },
            renderComplete: function() {
                feather.replace({
                    "stroke-width": 1.5,
                });
                if (cash('#extend-return-req-tab').hasClass('active')){
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
            let value = cash("#extend-return-request-table-html-filter-value").val();
            let dept = cash("#extend-return-request-table-html-department").val();
            let type = cash("#extend-return-request-table-html-request-tp").val();
            table.setFilter([
                {field:'subject', type:'like', value:value},
                {field:'dept', type:'=', value:dept},
                {field:'type', type:'=', value:type},
            ]);
        }

        // Filter by Id
        if (cash('#extend-return-req-tab').hasClass('active')){
            const params = new URLSearchParams(location.search);
            if (params.get('id')){
                table.setFilter("id", "=", params.get('id'));
            }
        }

        // On submit filter form
        cash("#extend-return-request-table-html-filter-form")[0].addEventListener(
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
        cash("#extend-return-request-table-html-filter-value").on("change", function (event) {
            if ( cash(this).val().length > 2 ) {
                filterHTMLForm();
            }
        });

        cash('#extend-return-request-table-html-department').on('change', function(e){
            filterHTMLForm();
        });

        cash('#extend-return-request-table-html-request-tp').on('change', function(e){
            filterHTMLForm();
        });

        cash('.nav-tabs #extend-return-req-tab').on("click", function (event) {
            table.refreshFilter();
            resetAccordion();
        });

        // add sub handle person
        cash('.extend_return_request  .btn-add-handler').on('click', function() {
            var component = '<div class="col-span-12 grid grid-cols-12 mt-2"><div class="col-span-12 xxl:col-span-6"><select class="cbx_sub_handler tail-select w-full"><option value="">Chọn người xử lý</option></select></div><div class="col-span-12 xxl:col-span-6"><button class="btn-remove-handler btn p-2 ml-2 box border border-gray-400 text-gray-700 dark:text-gray-300" aria-expanded="false"><span class="w-5 h-5 flex items-center justify-center"><i class="w-4 h-4" data-feather="minus"></i></span></button></div></div></div>';
            cash(this).closest('div').after(component);

            //initial tail select cbx_sub_handler
            cash(".extend_return_request  select.cbx_sub_handler").each(function (index, val) {
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

        cash('.extend_return_request .btn-accept-extend').on('click', function() {
            extendRequest("A");
        });
        cash('.extend_return_request .btn-deny-extend').on('click', function() {
            extendRequest("D");
        });

        // đăng ký người xử lý yêu cầu
        cash('.extend_return_request .btn_assign').on('click', function(){
            if ( !window.requestData ) {
                alert("Vui lòng chọn yêu cầu cần xử lý");
                return;
            }
            var form = cash('.extend_return_request');
            var formCheck = true;
            var request_id = window.requestData.request_id;
            var handler = form.find('select.cbx_handler').val();
            var assign_content = ckReturnAssignContent.getData();

            form.find('select.cbx_sub_handler').each(function(index, element){
                if (cash(element).val() == "") {
                    formCheck = false;
                } else if (cash(element).val() == handler) {
                    formCheck = false;
                }
            });

            if ( handler == "" ) {
                formCheck = false;
            }

            if ( !formCheck ) {
                alert("Vui lòng kiểm tra lại thông tin xử lý.")
                return;
            }
            var formData = new FormData();
            formData.append('request_id', request_id);
            formData.append('handler', handler);
            formData.append('assign_content', assign_content);
            form.find('select.cbx_sub_handler').each(function(index, element){
                if (cash(element).val()){
                    formData.append('sub_handler[]', cash(element).val());
                }
            });
            axios.post(`assign-return-request`, formData, {
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
        cash('.extend_return_request .btn_complete').on('click', function(){
            handelReturnRequest('F')
        });
        cash('.extend_return_request .btn_reject').on('click', function(){
            handelReturnRequest('X')
        });

        function extendRequest(status) {
            if (!window.requestData){
                alert("Vui lòng chọn yêu cầu cần xử lý");
                return false;
            }

            var request_id = window.requestData.request_id;
            var formData = new FormData();
            formData.append('request_id', request_id);
            formData.append('extend', status);
            axios.post("extend-request-decide", formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                    'Accept': 'application/json',
                  },
            }).then(res => {
                alert(res.data.message);
                filterHTMLForm();
            }).catch(err => {
                alert(err);
            });
        }

        function handelReturnRequest(status) {
            var form = cash('.extend_return_request');
            var handle_content = ckReturnAssignContent.getData();
            var request_id = window.requestData.request_id;
            if (handle_content == "") {
                alert("Vui lòng nhập nội dung xử lý (mục Giao việc)");
                return false;
            }
            var formData = new FormData();
            formData.append('request_id', request_id);
            formData.append('handle_content', handle_content);
            formData.append('status', status);

            axios.post(`handle-return-request`, formData, {
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
            cash('.extend_return_request .accordion-item .accordion-btn').each(function(){
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
