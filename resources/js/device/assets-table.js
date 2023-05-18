import xlsx from "xlsx";
import feather from "feather-icons";
import Tabulator from "tabulator-tables";

(function (cash) {
    "use strict";

    // Tabulator
    if (cash("#assets-table").length) {
        // Setup Tabulator
        let table = new Tabulator("#assets-table", {
            ajaxURL: "/assets/get",
            ajaxFiltering: true,
            ajaxSorting: true,
            selectable: 1,
            ajaxProgressiveLoad:"scroll", //enable progressive loading
            ajaxProgressiveLoadScrollMargin:100,
            paginationSize: 20,
            height: "700px",
            layout: "fitColumns",
            responsiveLayout: "collapse",
            placeholder: "Không tìm thấy dữ liệu",
            ajaxResponse: function(url, params, response){
                cash('#total_assets').text(response.total);
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
                                    <a href="/asset/update/${cell.getData().id}">${
                                    cell.getData().name
                                    }</a>
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
                    title: "Họ tên/Đơn vị",
                    width: 200,
                    field: "department",
                    headerSort:false,
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
                                ${cell.getData().assigned != null ? cell.getData().assigned.department.department_name : cell.getData().department.department_name}
                                </a>
                            </div>
                        </div>`;
                    },
                },
                {
                    title: "Số lượng",
                    width: 100,
                    field: "quantity",
                    hozAlign: "center",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                },
                {
                    title: "Trạng thái",
                    width: 150,
                    field: "status_id",
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
                    title: "Chi tiết",
                    width: 100,
                    field: "actions",
                    headerSort:false,
                    responsive: 1,
                    hozAlign: "center",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter(cell, formatterParams) {
                        let a = cash(`<div class="flex lg:justify-center items-center font-medium text-theme-17">
                            <a class="btn btn-outline-primary edit flex items-center tooltip" href="/asset/clone/${cell.getData().id}" title="Sao chép">
                                <i class="fa fa-clipboard" aria-hidden="true"></i>
                            </a>
                            <a class="btn btn-outline-primary edit flex items-center tooltip ml-2" href="/asset/deploy/${cell.getData().id}" title="Cấp">
                                <i class="fa fa-tags" aria-hidden="true"></i>
                            </a>
                        </div>`);
                        return a[0];
                    },
                },
            ],
            rowClick:function(e, row){
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
            let value = cash("#assets-table-filter-value").val();
            let status = cash('#assets-table-filter-status').val();
            let dept = cash('#assets-table-filter-department').val();
            table.setFilter([
                {field:'name', type:'=', value:value},
                {field:'status', type:'=', value:status},
                {field:'dept', type:'=', value:dept},
            ]);
        }

        // On submit filter form
        cash("#assets-table-filter-form")[0].addEventListener(
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
         cash("#assets-table-filter-value").on("change", function (event) {
            if ( cash(this).val().length > 2 ) {
                filterHTMLForm();
            }
        });

        cash('#assets-table-filter-status').on('change', function(e){
            filterHTMLForm();
        });

        cash('#assets-table-filter-department').on('change', function(e){
            filterHTMLForm();
        });

        // On reset filter form
        cash("#assets-filter-reset").on("click", function (event) {
            cash("#assets-filter-value").val("");
            filterHTMLForm();
        });

        cash('.asset-table-collapse').on('click', function(){
            filterHTMLForm();
        });
    }
})(cash);
