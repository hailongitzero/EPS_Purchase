import xlsx from "xlsx";
import feather from "feather-icons";
import Tabulator from "tabulator-tables";
import dayjs from "dayjs";

(function (cash) {
    "use strict";

    // Tabulator
    if (cash("#maintenances-table").length) {
        // Setup Tabulator
        let table = new Tabulator("#maintenances-table", {
            ajaxURL: "/maintenances/get",
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
                    title: "Người dùng",
                    width: 300,
                    responsive: 0,
                    field: "name",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter(cell, formatterParams) {
                        return `<div class="flex items-center">
                            <div class="w-10 h-10 image-fit zoom-in mx-auto">
                                <img alt="${cell.getData().assigned ? cell.getData().assigned.name : ''}"
                                    title="${cell.getData().assigned ? cell.getData().assigned.name : ''}"
                                    class="tooltip" target="_blank" src="${
                                        cell.getData().assigned ? cell.getData().assigned.photo : 'storage/placeholders/200x200.jpg'
                                    }">
                            </div>
                            <div class="ml-4 mr-auto">
                                <div class="font-medium whitespace-nowrap">
                                    ${cell.getData().assigned.name}
                                </div>
                                <div class="text-gray-600 text-xs whitespace-nowrap">
                                    ${cell.getData().assigned ? cell.getData().assigned.department.department_name : ''}
                                    </a>
                                </div>
                            </div>
                        </div>`;
                    },
                },
                {
                    title: "Thiết bị",
                    minWidth: 200,
                    field: "department",
                    hozAlign: "center",
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
                    title: "Ngày cấp",
                   width: 200,
                    field: "quantity",
                    hozAlign: "center",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter(cell, formatterParams) {
                        return `<div>
                        <div class="font-medium whitespace-nowrap">${
                                dayjs(cell.getData().created_at).format('DD-MM-YYYY')
                            }</div>
                        </div>`;
                    },
                },
                {
                    title: "Chi tiết",
                    width: 100,
                    field: "actions",
                    responsive: 1,
                    hozAlign: "center",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    headerSort:false,
                    formatter(cell, formatterParams) {
                        let a = cash(`<div class="flex lg:justify-center items-center font-medium text-theme-17">
                            <a class="btn btn-outline-primary edit flex items-center" href="/asset/maintenance/${cell.getData().id}">
                                <i data-feather="settings" class="w-4 h-4"></i>
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
            let value = cash("#maintenances-table-filter-value").val();
            table.setFilter('name', '=', value);
        }

        // On submit filter form
        cash("#maintenances-table-filter-form")[0].addEventListener(
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
         cash("#maintenances-table-filter-value").on("change", function (event) {
            if ( cash(this).val().length > 2 ) {
                filterHTMLForm();
            }
        });

        // On reset filter form
        cash("#maintenances-filter-reset").on("click", function (event) {
            cash("#maintenances-filter-value").val("");
            filterHTMLForm();
        });

        // Export
        cash("#maintenances-export-csv").on("click", function (event) {
            table.download("csv", "data.csv");
        });

        cash("#maintenances-export-json").on("click", function (event) {
            table.download("json", "data.json");
        });

        cash("#maintenances-export-xlsx").on("click", function (event) {
            window.XLSX = xlsx;
            table.download("xlsx", "data.xlsx", {
                sheetName: "Products",
            });
        });

        cash("#maintenances-export-html").on("click", function (event) {
            table.download("html", "data.html", {
                style: true,
            });
        });

        // Print
        cash("#maintenances-print").on("click", function (event) {
            table.print();
        });
    }
})(cash);
