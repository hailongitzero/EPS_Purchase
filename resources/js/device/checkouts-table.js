import xlsx from "xlsx";
import feather from "feather-icons";
import Tabulator from "tabulator-tables";

(function (cash) {
    "use strict";

    // Tabulator
    if (cash("#checkouts-table").length) {
        // Setup Tabulator
        let table = new Tabulator("#checkouts-table", {
            ajaxURL: "/checkouts/get",
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
                                <img alt="${cell.getData().asset != null ? cell.getData().asset.name : ''}"
                                    title="${cell.getData().asset != null ? cell.getData().asset.name : ''}"
                                    class="tooltip" src="${
                                        cell.getData().asset ? cell.getData().asset.image : 'storage/placeholders/200x200.jpg'
                                    }">
                            </div>
                            <div class="ml-4 mr-auto">
                                <div class="font-medium whitespace-nowrap">
                                    <a href="/asset/update/${cell.getData().asset.id}">${
                                    cell.getData().asset.name
                                    }</a>
                                </div>
                                <div class="text-gray-600 text-xs whitespace-nowrap">
                                    ${cell.getData().asset.model != null ? cell.getData().asset.model.name : ''}
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
                                ${cell.getData().asset.department != null ? cell.getData().asset.department.department_name : ''}
                            </div>
                        </div>`;
                    },
                },
                {
                    title: "Người yêu cầu",
                    width: 200,
                    field: "department",
                    hozAlign: "center",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter(cell, formatterParams) {
                        return `<div>
                            <div class="font-medium whitespace-nowrap">
                                ${cell.getData().requester != null ? cell.getData().requester.name: ''}
                            </div>
                            <div class="text-gray-600 text-xs whitespace-nowrap">
                                ${cell.getData().requester != null ? cell.getData().requester.department.department_name : ''}
                                </a>
                            </div>
                        </div>`;
                    },
                },
                {
                    title: "Số lượng",
                    width: 100,
                    field: "quantity",
                    headerSort: false,
                    hozAlign: "center",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter(cell, formatterParams) {
                        return `<div>
                            <div class="font-medium whitespace-nowrap">
                                ${cell.getData().asset != null ? cell.getData().asset.quantity: ''}
                            </div>
                        </div>`;
                    },
                },
                {
                    title: "Chi tiết",
                    width: 100,
                    field: "actions",
                    responsive: 1,
                    headerSort: false,
                    hozAlign: "center",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter(cell, formatterParams) {
                        let a = cash(`<div class="flex lg:justify-center items-center font-medium text-theme-17">
                            <a class="btn btn-outline-primary flex items-center" href="/checkouts/detail/${cell.getData().id}">
                                <i data-feather="activity" class="w-4 h-4"></i>
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
            let requester = cash("#checkouts-table-filter-user").val();
            let asset = cash('#checkouts-table-filter-asset').val();
            table.setFilter([
                {field:'asset_name', type:'=', value:asset},
                {field:'requester', type:'=', value:requester}
            ]);
        }

        // On submit filter form
        cash("#checkouts-table-filter-form")[0].addEventListener(
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
         cash("#checkouts-table-filter-user").on("change", function (event) {
            if ( cash(this).val().length > 2 ) {
                filterHTMLForm();
            }
        });

        // On seach change value
        cash("#checkouts-table-filter-asset").on("change", function (event) {
            if ( cash(this).val().length > 2 ) {
                filterHTMLForm();
            }
        });
    }
})(cash);
