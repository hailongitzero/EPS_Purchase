import dayjs from 'dayjs';
import feather from "feather-icons";
import Tabulator from "tabulator-tables";
import tippy, { roundArrow } from "tippy.js";
import { format } from "./form-component";

(function (cash) {
    "use strict";

    // Tabulator
    if (cash("#handle-requests-table").length) {
        var statusArr = { A: "Yêu cầu mới", B: "Tiếp nhận yêu cầu", C: "Gia hạn", D: "Đang xử lý", E: "Chuyển xử lý", F: "Hoàn thành", X: "Từ chối"};
        var statusClassArr = { A: "bg-theme-9", B: "bg-theme-22", C: "bg-theme-26", D: "bg-theme-22", E: "bg-theme-14", F: "bg-theme-10", X: "bg-theme-35"};
        var priorityArr = {L: "Thấp", M: "Trung bình", H: "Cao"};
        var priorityClassArr = {L: "bg-theme-10", M: "bg-theme-23", H: "bg-theme-24" };
        // Setup Tabulator
        let table = new Tabulator("#handle-requests-table", {
            ajaxURL: "/handle-request",
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
                cash('#handle_request_tab_sum').text(response.total);
                cash('.total_handle_result').text(response.total);
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
                if (cash('#handle-req-tab').hasClass('active')){
                    cash('#request_title').text(data.subject);
                }

                if(!data.main_person) {
                    if (!cash('.handle_request .btn-extend-modal').hasClass('hidden')){
                        cash('.handle_request .btn-extend-modal').addClass('hidden');
                    }
                    if (!cash('.handle_request .btn_return').hasClass('hidden')){
                        cash('.handle_request .btn_return').addClass('hidden');
                    }
                    if (!cash('.handle_request .btn_reject').hasClass('hidden')){
                        cash('.handle_request .btn_reject').addClass('hidden');
                    }
                } else {
                    if (cash('.handle_request .btn-extend-modal').hasClass('hidden')){
                        cash('.handle_request .btn-extend-modal').removeClass('hidden');
                    }
                    if (cash('.handle_request .btn_return').hasClass('hidden')){
                        cash('.handle_request .btn_return').removeClass('hidden');
                    }
                    if (cash('.handle_request .btn_reject').hasClass('hidden')){
                        cash('.handle_request .btn_reject').removeClass('hidden');
                    }
                }

                formFormat.forEach(function(item) {
                    field = item.field;
                    // get component data
                    if ( item.has_sub ) {
                        subField = item.sub_data.field;
                        subValue = item.sub_data.value;
                        if ( data[field] ){
                            meaning = data[field][subField];
                            value = data[field][subValue];
                        } else {
                            meaning = null;
                            value = null;
                        }
                    } else {
                        meaning = data[field];
                    }

                    // set componnent data
                    if ( item.type == "text") {
                        if ( meaning ){
                            cash('.handle_request .'+item.id).text(meaning);
                            cash('.handle_request .'+item.id).closest('.scope').removeClass('hidden');
                        } else {
                            cash('.handle_request .'+item.id).text('');
                            cash('.handle_request .'+item.id).closest('.scope').addClass('hidden');
                        }
                    } else if ( item.type == "number") {
                        if ( meaning ){
                            cash('.handle_request .'+item.id).text(meaning.toLocaleString("de-DE"));
                            cash('.handle_request .'+item.id).closest('.scope').removeClass('hidden');
                        } else {
                            cash('.handle_request .'+item.id).text('');
                            cash('.handle_request .'+item.id).closest('.scope').addClass('hidden');
                        }
                    } else if (item.type == "date") {
                        if ( meaning ){
                            cash('.handle_request .'+item.id).text(dayjs(meaning).format('DD-MM-YYYY'));
                        } else {
                            cash('.handle_request .'+item.id).text('');
                        }
                    } else if (item.type == "input") {
                        if ( meaning ){
                            cash('.handle_request .'+item.id).val(meaning);
                        } else {
                            cash('.handle_request .'+item.id).val('');
                        }
                    } else if (item.type == "select") {
                        if (window[item.id]){
                            if ( meaning) {
                                window[item.id].options.select(value, '#');
                            } else {
                                window[item.id].options.select(' ', '#');
                            }
                        }
                    } else if (item.type == "datepicker") {
                        if ( meaning && window[item.id] ) {
                            window[item.id].setDate(dayjs(meaning), true);
                        }
                    } else if (item.type == "html") {
                        if ( meaning ){
                            cash('.handle_request .'+item.id).html(meaning);
                            cash('.handle_request .'+item.id).closest('.accordion-item').removeClass('hidden');
                        } else {
                            cash('.handle_request .'+item.id).html('');
                            cash('.handle_request .'+item.id).closest('.accordion-item').addClass('hidden');
                        }
                    } else if (item.type == "file") {
                        var link = ''
                        if (data[field]) {
                            data[field].forEach(function(subData) {
                                link += '<span class="text-xs px-1 rounded-full border mr-1"><a href="./download/'+ subData['file_id'] +' " class="text-theme-17">' + subData[subField] + '</a></span> ';
                            });
                        }
                        cash('.handle_request .'+item.id).html(link);
                    }
                });
                if (typeof ckHandleContent !== "undefined") {
                    ckHandleContent.setData("");
                }
                resetAccordion();
                // window.location.href="#request-info";
            },
            renderComplete: function() {
                feather.replace({
                    "stroke-width": 1.5,
                });if (cash('#handle-req-tab').hasClass('active')){
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
            let value = cash("#handle-requests-table-html-filter-value").val();
            let dept = cash("#handle-requests-table-html-department").val();
            let type = cash("#handle-requests-table-html-request-tp").val();
            table.setFilter([
                {field:'subject', type:'like', value:value},
                {field:'dept', type:'=', value:dept},
                {field:'type', type:'=', value:type},
            ]);
        }

        // Filter by Id
        if (cash('#handle-req-tab').hasClass('active')){
            const params = new URLSearchParams(location.search);
            if (params.get('id')){
                table.setFilter("id", "=", params.get('id'));
            }
        }

        // On submit filter form
        cash("#handle-requests-table-html-filter-form")[0].addEventListener(
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
        cash("#handle-requests-table-html-filter-value").on("change", function (event) {
            if ( cash(this).val().length > 2 ) {
                filterHTMLForm();
            }
        });

        cash('#handle-requests-table-html-department').on('change', function(e){
            filterHTMLForm();
        });

        cash('#handle-requests-table-html-request-tp').on('change', function(e){
            filterHTMLForm();
        });

        // show editor content modal
        cash('.handle_request .btn-extend-modal').on('click', function(){
            if (window.requestData && window.requestData.extend_content) {
                ckExtendContent.setData(window.requestData.extend_content);
            } else {
                ckExtendContent.setData("");
            }
        });


        // reset form when change tab
        cash('.nav-tabs #handle-req-tab').on("click", function (event) {
            table.refreshFilter();
            if (typeof ckHandleContent !== "undefined") {
                ckHandleContent.setData("");
            }
            resetAccordion();
            if (format) {
                format.forEach(element => {
                    if (element.type == "text" || element.type == "date") {
                        cash('.'+element.id).text("");
                    }
                    if (element.type == "file" || element.type == "text_arr") {
                        cash('.'+element.id).html("");
                    }
                    if (element.type == "attachment") {
                        if (window[element.id]){
                            window[element.id].removeAllFiles(true);
                        }
                    }
                });
            }
        });

        // xử lý yêu cầu
        cash('.handle_request .btn_complete').on('click', function(){
            handelRequest('F')
        });
        cash('.handle_request .btn_reject').on('click', function(){
            handelRequest('X')
        });
        cash('.handle_request .btn_return').on('click', function(){
            handelRequest('E')
        });

        // modal
        cash('#modal_extend_date').on("click", ".close_modal_extend", function (e) {
            if ( !window.requestData){
                alert('Vui lòng chọn yêu cầu cần gia hạn.')
                return false;
            }
            if (ckExtendContent.getData() == "") {
                alert('Vui lòng nhập nội dung gia hạn.')
                return false;
            }

            window.requestData.extend_content = ckExtendContent.getData();
            window.requestData.extend_to = cash('.dpk_extend_dt').val();

            handelRequest('C');
        });

        function handelRequest(status) {
            if (!window.requestData){
                alert("Vui lòng chọn yêu cầu cần xử lý");
                return false;
            }

            var postLink = "handle-request";
            var form = cash('.handle_request');
            var handle_content = ckHandleContent.getData();
            var request_id = window.requestData.request_id;
            var attachment = window[form.find('#attachment').data('id')].files;
            var final_cost = cash('#inp_final_cost').val();
            var final_resource = cash('#cbx_final_resource option:checked').val();
            if (handle_content == "" && status != 'C') {
                alert("Vui lòng nhập nội dung xử lý.");
                return false;
            }
            var formData = new FormData();
            formData.append('request_id', request_id);
            formData.append('handle_content', handle_content);
            formData.append('final_cost', final_cost);
            formData.append('final_resource', final_resource);
            formData.append('status', status);
            if ( status == "C"){
                formData.append('extend_content', requestData.extend_content);
                formData.append('extend_to', window.requestData.extend_to);
                postLink = "extend-request";
            }
            if (status == "E") {
                postLink = "return-request";
            }
            cash(attachment).each(function(id, file){
                formData.append('attachment[]', file);
            });

            axios.post(postLink, formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                    'Accept': 'application/json',
                  },
            }).then(res => {
                alert(res.data.message);
                filterHTMLForm();
            }).catch(err => {
                console.log(err.response.data.errors);
                if (err.response.data.errors) {
                    alert(err.response.data.errors);
                } else if (err.response.data.message){
                    alert(err.response.data.message);
                }
            });
        }

        function resetAccordion(){
            cash('.handle_request .accordion-item .accordion-btn').each(function(){
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
