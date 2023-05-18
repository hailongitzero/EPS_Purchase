import xlsx from "xlsx";
import feather from "feather-icons";
import Tabulator from "tabulator-tables";

(function (cash) {
    "use strict";

    // Tabulator
    if (cash("#suppliers-table").length) {
        // Setup Tabulator
        let table = new Tabulator("#suppliers-table", {
            ajaxURL: "/suppliers/get",
            ajaxFiltering: true,
            ajaxSorting: true,
            printAsHtml: true,
            printStyled: true,
            selectable: 1,
            ajaxProgressiveLoad:"scroll",
            ajaxProgressiveLoadScrollMargin:200,
            paginationSize: 20,
            height: "700px",
            layout: "fitColumns",
            responsiveLayout: "collapse",
            placeholder: "Không tìm thấy dữ liệu",
            ajaxResponse: function(url, params, response){
                cash('#total_suppliers').text(response.total);
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
                                <div class="font-medium whitespace-nowrap">${
                                    cell.getData().name
                                }</div>
                                <div class="text-gray-600 text-xs whitespace-nowrap">
                                    ${cell.getData().address != null ? cell.getData().address : ''}
                                </div>
                            </div>
                        </div>`;
                    },
                },
                {
                    title: "Liên hệ",
                    width: 300,
                    field: "support_email",
                    hozAlign: "center",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter(cell, formatterParams) {
                        return `<div>
                            <div class="whitespace-nowrap">email: ${
                                cell.getData().email != null ? cell.getData().email : ''
                            }</div>
                            <div class="font-medium text-gray-600 whitespace-nowrap">phone: ${
                                cell.getData().phone != null ? cell.getData().phone : ''
                            }</div>
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
                    title: "Tùy chọn",
                    width: 100,
                    field: "actions",
                    responsive: 1,
                    headerSort: false,
                    hozAlign: "center",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter(cell, formatterParams) {
                        let a = cash(`<div class="flex lg:justify-center items-center">
                            <a class="btn btn-outline-primary edit flex items-center mr-3" href="/supplier/update/${cell.getData().id}">
                                <i data-feather="check-square" class="w-4 h-4"></i>
                            </a>
                            <button class="btn ${cell.getData().deleted_at == null ? "btn-outline-danger" : "btn-outline-primary"} delete flex items-center tooltip" title=" ${cell.getData().deleted_at == null ? "Xóa" : "Dùng"}">
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
                    axios.post(`supplier-delete`,{
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
            let value = cash("#suppliers-table-filter-value").val();
            let status = cash("#suppliers-table-filter-status").val();
            table.setFilter([
                {field:'name', type:'=', value:value},
                {field:'status', type:'=', value:status},
            ]);
        }

        // On submit filter form
        cash("#suppliers-table-filter-form")[0].addEventListener(
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
         cash("#suppliers-table-filter-value").on("change", function (event) {
            if ( cash(this).val().length > 2 ) {
                filterHTMLForm();
            }
        });

        cash('#suppliers-table-filter-status').on('change', function(e){
            filterHTMLForm();
        });

        // On reset filter form
        cash("#suppliers-filter-reset").on("click", function (event) {
            cash("#suppliers-filter-value").val("");
            filterHTMLForm();
        });

        cash('.suppliers-table-collapse').on('click', function(){
            filterHTMLForm();
        });
    }
})(cash);
