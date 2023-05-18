import dayjs from 'dayjs';
import feather from "feather-icons";
import Tabulator from "tabulator-tables";

(function (cash) {
    "use strict";

    // Tabulator
    if (cash("#licenses-table").length) {
        // Setup Tabulator
        let table = new Tabulator("#licenses-table", {
            ajaxURL: "/licenses/get",
            ajaxFiltering: true,
            ajaxSorting: true,
            selectable: 1,
            ajaxProgressiveLoad:"scroll", //enable progressive loading
            ajaxProgressiveLoadScrollMargin:200,
            paginationSize: 20,
            height: "700px",
            layout: "fitColumns",
            responsiveLayout: "collapse",
            placeholder: "Không tìm thấy dữ liệu",
            ajaxResponse: function(url, params, response){
                cash('#total_licenses').text(response.total);
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
                            <div class="mr-auto">
                                <div class="font-medium whitespace-nowrap">
                                    <a href="/licenses/update/${cell.getData().id}">${
                                    cell.getData().name
                                    }</a>
                                </div>
                                <div class="whitespace-nowrap">
                                    ${cell.getData().category != null ? cell.getData().category.name : ''}
                                </div>
                                <div class="text-gray-600 text-xs whitespace-nowrap">
                                    ${cell.getData().manufacturer != null ? cell.getData().manufacturer.name : ''}
                                    </a>
                                </div>
                            </div>
                        </div>`;
                    },
                },
                {
                    title: "Đăng ký",
                    width: 300,
                    field: "department",
                    hozAlign: "center",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter(cell, formatterParams) {
                        return `<div>
                            <div class="whitespace-nowrap">
                                Id: ${cell.getData().license_name}
                                </a>
                            </div>
                            <div class="whitespace-nowrap">
                                Email: ${cell.getData().license_email}
                                </a>
                            </div>
                        </div>`;
                    },
                },
                {
                    title: "Số lượng",
                    width: 120,
                    field: "seats",
                    hozAlign: "center",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter(cell, formatterParams) {
                        return `<div>
                            <div class="whitespace-nowrap">
                                ${cell.getData().limit_seats == 0 ? "Không giới hạn" : cell.getData().seats}
                                </a>
                            </div>
                        </div>`;
                    },
                },
                {
                    title: "Còn lại",
                    width: 150,
                    field: "seats",
                    hozAlign: "center",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter(cell, formatterParams) {
                        return `<div>
                            <div class="whitespace-nowrap">
                                ${cell.getData().limit_seats == 0 ? "Không giới hạn" : cell.getData().remain}
                                </a>
                            </div>
                        </div>`;
                    },
                },
                {
                    title: "Ngày hết hạn",
                    width: 150,
                    field: "seats",
                    hozAlign: "center",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter(cell, formatterParams) {
                        return `<div>
                            <div class="whitespace-nowrap">
                                ${cell.getData().limit_date == 0 ? "Vĩnh viễn" : dayjs(cell.getData().expiration_date).format('DD-MM-YYYY')}
                                </a>
                            </div>
                        </div>`;
                    },
                },
                {
                    title: "Trạng thái",
                    width: 120,
                    field: "status",
                    headerSort: false,
                    hozAlign: "center",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter(cell, formatterParams) {
                        return `<div class="flex items-center lg:justify-center ${
                            cell.getData().deleted_at == null
                                ? "text-theme-10"
                                : "text-theme-24"
                        }">
                            <i data-feather="check-square" class="w-4 h-4 mr-2"></i> ${
                                cell.getData().deleted_at == null ? "Active" : "Inactive"
                            }
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
                            <a class="btn btn-outline-primary edit flex items-center mr-3" href="/licenses/deploy/${cell.getData().id}">
                                <i data-feather="activity" class="w-4 h-4"></i>
                            </a>
                            <button class="btn ${cell.getData().deleted_at == null ? "btn-outline-danger" : "btn-outline-primary"} delete flex items-center tooltip" title=" ${cell.getData().deleted_at == null ? "Xóa" : "Dùng lại"}">
                                <i data-feather="${cell.getData().deleted_at == null ? "trash-2" : "rotate-ccw"}" class="w-4 h-4"></i>
                            </button>
                        </div>`);
                        return a[0];
                    },
                },
            ],
            rowClick:function(e, row){
                var ele = '';
                if (e.srcElement.nodeName.toLowerCase() == 'svg') {
                    ele = cash(e.srcElement).closest('button');
                } else if (e.srcElement.nodeName.toLowerCase() == 'button'){
                    ele = e.srcElement;
                } else {
                    return false;
                }
                if (cash(ele).hasClass('delete')){
                    axios.post(`licenses-delete`,{
                        id: row.getData().id,
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
            let value = cash("#licenses-table-filter-value").val();
            table.setFilter([
                {field:'name', type:'=', value:value},
            ]);
        }

        // On submit filter form
        cash("#licenses-table-filter-form")[0].addEventListener(
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
         cash("#licenses-table-filter-value").on("change", function (event) {
            if ( cash(this).val().length > 2 ) {
                filterHTMLForm();
            }
        });

        cash('#assets-table-filter-field').on('change', function(e){
            filterHTMLForm();
        });

        // On reset filter form
        cash("#licenses-filter-reset").on("click", function (event) {
            cash("#licenses-filter-value").val("");
            filterHTMLForm();
        });

        cash('.license-table-collapse').on('click', function(){
            filterHTMLForm();
        });
    }
})(cash);
