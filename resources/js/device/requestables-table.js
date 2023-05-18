import xlsx from "xlsx";
import feather from "feather-icons";
import Tabulator from "tabulator-tables";

(function (cash) {
    "use strict";

    // Tabulator
    if (cash("#requestables-table").length) {
        // Setup Tabulator
        let table = new Tabulator("#requestables-table", {
            ajaxURL: "/requestables/get",
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
            placeholder: "Không tìm thấy dữ liệu",
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
                    title: "Tên",
                    minWidth: 200,
                    responsive: 0,
                    field: "name",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter(cell, formatterParams) {
                        return `<div class="flex items-center">
                            <div class="w-10 h-10 image-fit zoom-in mx-auto">
                                <img alt="${cell.getData().name != null ? cell.getData().name : ''}"
                                    title="${cell.getData().name != null ? cell.getData().name : ''}"
                                    class="tooltip" src="${
                                        cell.getData().image ? cell.getData().image : 'storage/placeholders/200x200.jpg'
                                    }">
                            </div>
                            <div class="ml-4 mr-auto">
                                <div class="font-medium whitespace-nowrap">
                                    ${cell.getData().name}
                                </div>
                                <div class="text-gray-600 text-xs whitespace-nowrap">
                                    ${cell.getData().model != null ? cell.getData().model.name : ''}
                                    </a>
                                </div>
                            </div>
                        </div>`;
                    },
                },
                {
                    title: "Lưu kho",
                    width: 200,
                    field: "department",
                    hozAlign: "center",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter(cell, formatterParams) {
                        return `<div>
                            <div class="font-medium whitespace-nowrap">
                                ${cell.getData().assigned != null ? cell.getData().assigned.name : ''}
                            </div>
                            <div class="text-gray-600 text-xs whitespace-nowrap">
                                ${cell.getData().department != null ? cell.getData().department.department_name : ''}
                                </a>
                            </div>
                        </div>`;
                    },
                },
                {
                    title: "Số lượng",
                    width: 120,
                    field: "quantity",
                    hozAlign: "center",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                },
                {
                    title: "Trạng thái",
                    width: 200,
                    field: "status",
                    headerSort: false,
                    hozAlign: "center",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter(cell, formatterParams) {
                        return `<div>
                            <div class="font-medium whitespace-nowrap" style="color:${cell.getData().status.color}">
                                ${cell.getData().status != null ? cell.getData().status.name : ''}
                            </div>
                        </div>`;
                    },
                },
                {
                    title: "Yêu cầu",
                    width: 120,
                    field: "actions",
                    responsive: 1,
                    headerSort: false,
                    hozAlign: "center",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter(cell, formatterParams) {
                        let a = cash(`<div class="flex lg:justify-center items-center font-medium text-theme-17">
                            <a class="btn ${cell.getData().user_requested[0] ? 'btn-outline-danger' : 'btn-outline-primary'} request flex items-center tooltip"
                                href="${cell.getData().user_requested[0] ? 'javascript:;' : 'requestable/request/'+cell.getData().id}" data-status="${cell.getData().user_requested[0] ? 'cancel' : 'add'}"
                                title="${cell.getData().user_requested[0] ? 'Hủy' : 'Yêu cầu'}">
                                <i data-feather="${cell.getData().user_requested[0] ? 'rotate-ccw' : 'navigation'}" class="w-4 h-4"></i>
                            </a>
                        </div>`);
                        return a[0];
                    },
                },
            ],
            rowClick:function(e, row){
                if (cash(e.srcElement).hasClass('request')){
                    var link = "";
                    if(cash(e.srcElement).data('status') == "add"){
                        return false;
                    } else {
                        link = "requestable-cancel"
                    }
                    axios.post(link,{
                        id: row.getData().user_requested[0] ? row.getData().user_requested[0].id : '',
                        asset_id: row.getData().id,
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
            },
            renderComplete: function(){
                feather.replace({
                    "stroke-width": 1.5,
                })
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
            let value = cash("#requestables-table-filter-value").val();
            table.setFilter('name', '=', value);
        }

        // On submit filter form
        cash("#requestables-table-filter-form")[0].addEventListener(
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
         cash("#requestables-table-filter-value").on("change", function (event) {
            if ( cash(this).val().length > 2 ) {
                filterHTMLForm();
            }
        });

        // On reset filter form
        cash("#requestables-filter-reset").on("click", function (event) {
            cash("#requestables-filter-value").val("");
            filterHTMLForm();
        });
    }
})(cash);
