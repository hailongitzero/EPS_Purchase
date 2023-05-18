import chart, { helpers } from "chart.js";
import dayjs from "dayjs";

(function (cash) {
    "use strict";

    if (cash("#department-request-period-chart").length) {
        let ctx = cash("#department-request-period-chart")[0].getContext("2d");
        var type = cash('#department-request-type').val();
        var label = [], data = [];
        var fromdate = dayjs(window["department-request-date-range"].getStartDate().dateInstance).format('YYYY-MM-DD');
        var toDate = dayjs(window["department-request-date-range"].getEndDate().dateInstance).format('YYYY-MM-DD');
        axios.post(`department-request-period`, {
            fromDate: fromdate,
            toDate: toDate,
            type: type
        }).then(res => {
            label = [], data = [];
            res.data.list.forEach(element => {
                label.push(element.label);
                data.push(element.data);
            });
            cash('#department-request-period-sum').text(res.data.sum[0].cnt);
            let myLineChart = new chart(ctx, lineChartData(label, data));
        }).catch(err => {});
        
        cash('#department-request-type').on('change', function(){
            updateLineChart(ctx);
        });
        window['department-request-date-range'].on('selected', (fromdate, toDate) => {
            updateLineChart(ctx);
        });
    }

    if (cash("#request-group-chart").length) {
        let ctx = cash("#request-group-chart")[0].getContext("2d");
        var data = [];
        var fromDate = dayjs(window['all-request-date-range'].getStartDate().dateInstance).format('YYYY-MM-DD');
        var toDate = dayjs(window['all-request-date-range'].getEndDate().dateInstance).format('YYYY-MM-DD');

        axios.post(`request-group-period`, {
            fromDate: fromDate,
            toDate: toDate
        }).then(res => {
            data = [];
            res.data.result.forEach(element => {
                data.push(element.cnt);
                cash('#request-group-chart-s'+element.type).text(element.cnt);
            });
            cash('#request-group-chart-sum').text(res.data.sum[0].total);
            let myDoughnutChart = new chart(ctx, doughnutChartData(data));
        }).catch(err => {
        });
        
        window['all-request-date-range'].on('selected', (fromdate, toDate) => {
            updatePieChart(ctx);
        });
    }

    cash('.statistics-layout').each(function(){
        var type = 'm';
        var reqType = cash('#statistics-request-type').val();

        axios.post(`request-statistics-period`, {
            type: type,
            reqType: reqType,
        }).then(res => {
            res.data.result.forEach(element => {
                cash('#statistics-layout-s'+element.type).text(element.cnt);
            });
            cash('#statistics-layout-sum').text(res.data.sum[0].total);
        }).catch(err => {
            console.log(err);
        });

        cash('.statistics-layout-btn').on('click', function(){
            if (cash(this).attr('id') == 'statistics-layout-btn-year') {
                type = 'y';
            } else {
                type = 'm';
            }
            reqType = cash('#statistics-request-type').val();

            axios.post(`request-statistics-period`, {
                type: type,
                reqType: reqType,
            }).then(res => {
                res.data.result.forEach(element => {
                    cash('#statistics-layout-s'+element.type).text(element.cnt);
                });
                cash('#statistics-layout-sum').text(res.data.sum[0].total);
            }).catch(err => {
                console.log(err);
            });
        });

        cash('#statistics-request-type').on('change', function(){
            if (cash('#statistics-layout-btn-year').hasClass('active')) {
                type = 'y';
            } else {
                type = 'm';
            }
            reqType = cash('#statistics-request-type').val();

            axios.post(`request-statistics-period`, {
                type: type,
                reqType: reqType,
            }).then(res => {
                res.data.result.forEach(element => {
                    cash('#statistics-layout-s'+element.type).text(element.cnt);
                });
                cash('#statistics-layout-sum').text(res.data.sum[0].total);
            }).catch(err => {
                console.log(err);
            });
        });
    });

    function lineChartData(label, data) {
        return {
            type: "bar",
            data: {
                labels: label,
                datasets: [
                    {
                        label: "Yêu cầu",
                        borderWidth: 2,
                        borderColor: "transparent",
                        barPercentage: 0.5,
                        barThickness: 8,
                        maxBarThickness: 6.5,
                        minBarLength: 2,
                        data: data,
                        backgroundColor: "#1c3faa",
                    },
                ],
            },
            options: {
                legend: {
                    display: false,
                },
                scales: {
                    xAxes: [
                        {
                            ticks: {
                                fontSize: 11,
                                fontColor: "#718096",
                            },
                            gridLines: {
                                display: false,
                            },
                        },
                    ],
                    yAxes: [
                        {
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                color: "#D8D8D8",
                                zeroLineColor: "#D8D8D8",
                                borderDash: [2, 2],
                                zeroLineBorderDash: [2, 2],
                                drawBorder: false,
                            },
                        },
                    ],
                },
            },
        }
    }

    function doughnutChartData(data){
        return {
            type: "doughnut",
            data: {
                labels: ["Yellow", "Dark"],
                datasets: [
                    {
                        data: data,
                        backgroundColor: ["#203f90", "#13b176", "#fbc500", "#E63b1f"],
                        hoverBackgroundColor: ["#203f90", "#13b176", "#fbc500", "#E63b1f"],
                        borderWidth: 5,
                        borderColor: cash("html").hasClass("dark")
                            ? "#303953"
                            : "#e3eaf2",
                    },
                ],
            },
            options: {
                legend: {
                    display: false,
                },
                cutoutPercentage: 82,
            },
        };
    }

    function updateLineChart(ctx){
        var type = cash('#department-request-type').val();
        var fromdate = dayjs(window['department-request-date-range'].getStartDate().dateInstance).format('YYYY-MM-DD');
        var toDate = dayjs(window['department-request-date-range'].getEndDate().dateInstance).format('YYYY-MM-DD');
        axios.post(`department-request-period`, {
            fromDate: fromdate,
            toDate: toDate,
            type: type
        }).then(res => {
            var label = [], data = [];
            res.data.list.forEach(element => {
                label.push(element.label);
                data.push(element.data);
            });
            
            cash('#department-request-period-sum').text(res.data.sum[0].cnt);
            let myLineChart = new chart(ctx, lineChartData(label, data));
        }).catch(err => {});
    }

    function updatePieChart(ctx){
        var fromDate = dayjs(window['all-request-date-range'].getStartDate().dateInstance).format('YYYY-MM-DD');
        var toDate = dayjs(window['all-request-date-range'].getEndDate().dateInstance).format('YYYY-MM-DD');
        
        axios.post(`request-group-period`, {
            fromDate: fromDate,
            toDate: toDate
        }).then(res => {
            data = [];
            res.data.result.forEach(element => {
                data.push(element.cnt);
                cash('#request-group-chart-s'+element.type).text(element.cnt);
            });
            cash('#request-group-chart-sum').text(res.data.sum[0].total);
            let myDoughnutChart = new chart(ctx, doughnutChartData(data));
        }).catch(err => {
            console.log(err);
        });
    }
})(cash);