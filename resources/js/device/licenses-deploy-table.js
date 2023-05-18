import dayjs from 'dayjs';
import feather from "feather-icons";
import Tabulator from "tabulator-tables";

(function (cash) {
    "use strict";
    function isNumber(n) { return /^-?[\d.]+(?:e-?\d+)?$/.test(n); }
    // Tabulator
    if (cash("#licenses-deploy-table").length) {
        // Setup Tabulator
        let table = new Tabulator("#licenses-deploy-table", {
            ajaxURL: window.location.href + "/get",
            ajaxFiltering: true,
            ajaxSorting: true,
            selectable: 1,
            ajaxProgressiveLoad:"scroll",
            ajaxProgressiveLoadScrollMargin:200,
            paginationSize: 10,
            height: "350px",
            layout: "fitColumns",
            responsiveLayout: "collapse",
            placeholder: "Không tìm thấy dữ liệu",
            ajaxResponse: function(url, params, response){
                var total = cash('#license_total').text();
                var remain = response.total;
                if (isNumber(remain)){
                    var curRemain = total - remain;
                    cash('#license_remain').text(curRemain);
                }
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
                    title: "Thiết bị",
                    minWidth: 200,
                    responsive: 0,
                    field: "name",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter(cell, formatterParams) {
                        return `<div class="flex items-center">
                            <div class="ml-4 mr-auto">
                                <div class="font-medium whitespace-nowrap">
                                    <a href="/licenses/update/${cell.getData().id}">${
                                        cell.getData().asset ? cell.getData().asset.name : ''
                                    }</a>
                                </div>
                                <div class="whitespace-nowrap">
                                    ${cell.getData().asset ? cell.getData().asset.model.name : ''}
                                </div>
                            </div>
                        </div>`;
                    },
                },
                {
                    title: "Người dùng",
                    minWidth: 200,
                    field: "department",
                    hozAlign: "center",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter(cell, formatterParams) {
                        return `<div>
                            <div class="whitespace-nowrap">
                                ${cell.getData().assigned ? cell.getData().assigned.name : (cell.getData().asset.assigned ? cell.getData().asset.assigned.name : '') }
                                </a>
                            </div>
                            <div class="whitespace-nowrap">
                                ${cell.getData().assigned ? cell.getData().assigned.department.department_name : (cell.getData().asset.assigned ? cell.getData().asset.assigned.department.department_name : '')}
                                </a>
                            </div>
                        </div>`;
                    },
                },
                {
                    title: "Ngày cấp",
                    width: 150,
                    field: "seats",
                    hozAlign: "center",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter(cell, formatterParams) {
                        return `<div>
                            <div class="whitespace-nowrap">
                                ${dayjs(cell.getData().created_at).format('DD-MM-YYYY')}
                                </a>
                            </div>
                        </div>`;
                    },
                },
                {
                    title: "Thu hồi",
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
                            <button type="button" class="btn btn-outline-primary delete-seat flex items-center mr-3">
                                <i data-feather="rotate-ccw" class="w-4 h-4"></i>
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
                if (cash(ele).hasClass('delete-seat')){
                    axios.post(`delete`,{
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
            let user = cash("#licenses-deploy-table-filter-user").val();
            let asset = cash("#licenses-deploy-table-filter-asset").val();
            table.setFilter([
                {field:'user_name', type:'=', value:user},
                {field:'asset_name', type:'=', value:asset}
            ]);
        }

        // On submit filter form
        cash("#licenses-deploy-table-filter-form")[0].addEventListener(
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
        cash("#licenses-deploy-table-filter-user").on("change", function (event) {
            if ( cash(this).val().length > 2 ) {
                filterHTMLForm();
            }
        });
        // On seach change value
        cash("#licenses-deploy-table-filter-asset").on("change", function (event) {
            if ( cash(this).val().length > 2 ) {
                filterHTMLForm();
            }
        });

        cash('.licenses-deploy-table-collapse').on('click', function(){
            filterHTMLForm();
        });
    }
})(cash);
