import dayjs from "dayjs";
import Litepicker from "litepicker";
import "litepicker/dist/plugins/ranges"

(function (cash) {
    "use strict";

    // Litepicker
    cash(".new_request_datepicker").each(function () {
        let startDate = dayjs().add(7, 'day');
        let options = {
            autoApply: true,
            singleMode: false,
            numberOfColumns: 2,
            numberOfMonths: 2,
            startDate: startDate,
            minDate: dayjs(),
            showWeekNumbers: true,
            format: "DD-MM-YYYY",
            dropdowns: {
                minYear: 2010,
                maxYear: 2120,
                months: true,
                years: true,
            },
        };

        if (cash(this).data("single-mode")) {
            options.singleMode = true;
            options.numberOfColumns = 1;
            options.numberOfMonths = 1;
        }

        if (cash(this).data("format")) {
            options.format = cash(this).data("format");
        }

        if (!cash(this).val()) {
            let date = dayjs().format(options.format);
            date += !options.singleMode
                ? " - " + dayjs().add(1, "month").format(options.format)
                : "";
            cash(this).val(date);
        }

        new Litepicker({
            element: this,
            ...options,
        });
    });

    cash(".datepicker").each(function () {
        let options = {
            autoApply: true,
            singleMode: false,
            singleDate: true,
            numberOfColumns: 2,
            numberOfMonths: 2,
            showWeekNumbers: true,
            format: "DD-MM-YYYY",
            dropdowns: {
                minYear: 2010,
                maxYear: 2120,
                months: true,
                years: true,
            },
        };

        if (cash(this).data("single-mode")) {
            options.singleMode = true;
            options.numberOfColumns = 1;
            options.numberOfMonths = 1;
        }

        if (cash(this).data("format")) {
            options.format = cash(this).data("format");
        }

        if (!cash(this).val()) {
            let date = dayjs().format(options.format);
            date += !options.singleMode
                ? " - " + dayjs().add(1, "month").format(options.format)
                : "";
            cash(this).val(date);
        }

        if (cash(this).data('id')){
            window[cash(this).data('id')] = new Litepicker({
                element: this,
                ...options,
            });
        } else {
            new Litepicker({
                element: this,
                ...options,
            });
        }
    });

    cash(".datepicker_empty").each(function () {
        let options = {
            autoApply: true,
            singleMode: false,
            singleDate: true,
            numberOfColumns: 2,
            numberOfMonths: 2,
            showWeekNumbers: true,
            format: "DD-MM-YYYY",
            dropdowns: {
                minYear: 2010,
                maxYear: 2120,
                months: true,
                years: true,
            },
        };

        if (cash(this).data("single-mode")) {
            options.singleMode = true;
            options.numberOfColumns = 1;
            options.numberOfMonths = 1;
        }

        if (cash(this).data("format")) {
            options.format = cash(this).data("format");
        }

        if (cash(this).data('id')){
            window[cash(this).data('id')] = new Litepicker({
                element: this,
                ...options,
            });
        } else {
            new Litepicker({
                element: this,
                ...options,
            });
        }
    });

    cash(".request-date-range").each(function () {
        var start = dayjs().date(1).month(0).year(2017);
        var end = dayjs();
        let options = {
            autoApply: true,
            singleMode: false,
            singleDate: true,
            numberOfColumns: 2,
            numberOfMonths: 2,
            showWeekNumbers: true,
            format: "DD-MM-YYYY",
            startDate: start,
            endDate: end,
            dropdowns: {
                minYear: 2010,
                maxYear: 2120,
                months: true,
                years: true,
            },
            plugins: ['ranges'],
            ranges: {
                autoApply: true
            }
        };

        if (cash(this).data("single-mode")) {
            options.singleMode = true;
            options.numberOfColumns = 1;
            options.numberOfMonths = 1;
        }

        if (cash(this).data("format")) {
            options.format = cash(this).data("format");
        }

        if (!cash(this).val()) {
            let date = dayjs().format(options.format);
            date += !options.singleMode
                ? " - " + dayjs().add(1, "month").format(options.format)
                : "";
            cash(this).val(date);
        }

        if (cash(this).data('id')){
            window[cash(this).data('id')] = new Litepicker({
                element: this,
                ...options,
            });
        } else {
            new Litepicker({
                element: this,
                ...options,
            });
        }
    });

    cash(".expired_datepicker").each(function () {
        let startDate = dayjs().add(3, 'day');
        let options = {
            autoApply: true,
            singleMode: false,
            numberOfColumns: 2,
            numberOfMonths: 2,
            startDate: startDate,
            minDate: dayjs(),
            showWeekNumbers: true,
            format: "DD-MM-YYYY",
            dropdowns: {
                minYear: 2020,
                maxYear: 2120,
                months: true,
                years: true,
            },
        };

        if (cash(this).data("single-mode")) {
            options.singleMode = true;
            options.numberOfColumns = 1;
            options.numberOfMonths = 1;
        }

        if (cash(this).data("format")) {
            options.format = cash(this).data("format");
        }

        if (!cash(this).val()) {
            let date = dayjs().format(options.format);
            date += !options.singleMode
                ? " - " + dayjs().add(1, "month").format(options.format)
                : "";
            cash(this).val(date);
        }

        new Litepicker({
            element: this,
            ...options,
        });
    });

})(cash);
