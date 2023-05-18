import dayjs from 'dayjs';
import xlsx from "xlsx";
import feather from "feather-icons";
import Tabulator from "tabulator-tables";
import tippy, { roundArrow } from "tippy.js";
import { format } from "./form-component";

(function (cash) {
    "use strict";

    // Tabulator
    if (cash("#handling-requests-table").length) {
        var statusArr = { A: "Yêu cầu mới", B: "Tiếp nhận yêu cầu", C: "Gia hạn", D: "Đang xử lý", E: "Chuyển xử lý", F: "Hoàn thành", X: "Từ chối"};
        var statusClassArr = { A: "bg-theme-9", B: "bg-theme-22", C: "bg-theme-26", D: "bg-theme-22", E: "bg-theme-14", F: "bg-theme-10", X: "bg-theme-35"};
        var priorityArr = {L: "Thấp", M: "Trung bình", H: "Cao"};
        var priorityClassArr = {L: "bg-theme-10", M: "bg-theme-23", H: "bg-theme-24" };

        // Setup Tabulator
        let table = new Tabulator("#handling-requests-table", {
            ajaxURL: "/handling-request",
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
            placeholder: "Không có yêu cầu",
            ajaxResponse: function(url, params, response){
                cash('#handling_request_tab_sum').text(response.total);
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
                            <div class="font-medium whitespace-nowrap ${ !cell.getData().handle_date && dayjs(cell.getData().complete_date) < dayjs() ? "text-theme-24" : ""}">${
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
                                            class="tooltip rounded-full" src="/${
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
                                    class="tooltip rounded-full" src="/${
                                        prs.user.photo != null ? prs.user.photo : 'storage/users/no-user.jpg'
                                    }"></div>`;
                            });
                        }
                        var sReturn = `<div class="flex-col lg:justify-center"><div class="flex">
                            <div class="w-10 h-10 image-fit zoom-in mx-auto">
                                <img alt="${cell.getData().handler != null ? cell.getData().handler['name'] : ''}"
                                    title="${cell.getData().handler != null ? cell.getData().handler['name'] : ''}"
                                    class="tooltip rounded-full" src="/${
                                        cell.getData().handler['photo'] != null ? cell.getData().handler['photo'] : 'storage/users/no-user.jpg'
                                }">
                            </div>${sub_prs}</div></div>`;
                        return cell.getData().handler != null ? sReturn : `<div class="flex-col lg:justify-center">Chưa tiếp nhận</div>`;
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
                cash('.handling_request #request_title').text(data.subject);
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
                            cash('.handling_request .'+item.id).text(meaning);
                            cash('.handling_request .'+item.id).closest('.scope').removeClass('hidden');
                        } else {
                            cash('.handling_request .'+item.id).closest('.scope').addClass('hidden');
                        }
                    } else if (item.type == "date") {
                        if ( meaning ){
                            cash('.handling_request .'+item.id).text(dayjs(meaning).format('DD-MM-YYYY'));
                        }
                    } else if (item.type == "html") {
                        if ( meaning ){
                            cash('.handling_request .'+item.id).html(meaning);
                            cash('.handling_request .'+item.id).closest('.accordion-item').removeClass('hidden');
                        } else {
                            cash('.handling_request .'+item.id).closest('.accordion-item').addClass('hidden');
                        }
                    } else if (item.type == "view_editor") {
                        if ( item.has_sub) {
                            var subContent = '';
                            if (data[item.field]){
                                data[item.field].forEach( function(subData) {
                                    subContent += subData[item.sub_data['field']] + "<br/><br/>";
                                });
                            }
                            window.requestData['sub_handle_content'] = subContent
                        }
                    } else if (item.type == "file") {
                        var link = ''
                        if (data[field].length) {
                            data[field].forEach(function(subData) {
                                link += '<span class="text-xs px-1 rounded-full border mr-1"><a href="./download/'+ subData['file_id'] +' " class="text-theme-17">' + subData[subField] + '</a></span>';
                            });
                        }
                        cash('.handling_request .'+item.id).html(link);
                    } else if ( item.type == "text_arr" ) {
                        var person = '';
                        if ( data[field].length ) {
                            data[field].forEach(function(subData) {
                                person += subData[subField] + '<br/>'
                            });
                            cash('.handling_request .'+item.id).closest('.scope').removeClass('hidden');
                            cash('.handling_request .'+item.id).html(person);
                        } else {
                            cash('.handling_request .'+item.id).closest('.scope').addClass('hidden');
                        }
                    }

                });

                resetAccordion();
                // window.location.href="#request-info";
            },
            renderComplete: function() {
                feather.replace({
                    "stroke-width": 1.5,
                });
                table.selectRow(table.getRows()[0]);
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
            let value = cash("#handling-requests-table-html-filter-value").val();
            table.setFilter("subject", "like", value);
        }

        // On submit filter form
        cash("#handling-requests-table-html-filter-form")[0].addEventListener(
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
        cash("#handling-requests-table-html-filter-value").on("change", function (event) {
            if ( cash(this).val().length > 2 ) {
                filterHTMLForm();
            }
        });

        cash('.nav-tabs #handling-req-tab').on("click", function (event) {
            table.refreshFilter();
        });

        function resetAccordion(){
            cash('.handling_request .accordion-item').find('.accordion-collapse').removeClass("show").css('display', 'none');
            cash('.handling_request .accordion-item')
              .find(".accordion-button")
              .addClass("collapsed")
              .attr("aria-expanded", false);

            cash(cash('.handling_request .accordion-item')[0]).find('.accordion-collapse').addClass("show").css('display', 'block');
            cash(cash('.handling_request .accordion-item')[0])
              .find(".accordion-button")
              .removeClass("collapsed")
              .attr("aria-expanded", true);
        }
    }
})(cash);
