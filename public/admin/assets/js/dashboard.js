let currentDate = new Date();
let currentYear = currentDate.getFullYear();
var dataTotalOrder = [];
var myChart;
var monthsInYear = [
    "Tháng 1",
    "Tháng 2",
    "Tháng 3",
    "Tháng 4",
    "Tháng 5",
    "Tháng 6",
    "Tháng 7",
    "Tháng 8",
    "Tháng 9",
    "Tháng 10",
    "Tháng 11",
    "Tháng 12",
];

$(document).ready(function () {
    const ctx = document.getElementById("myChart");

    /**
     * Create chart
     * @param {Array} labels label chart
     * @param {Array} data data chart
     */
    function createChart(labels, data) {
        if (myChart) {
            myChart.destroy(); // Cancel the old chart if it exists
        }
        myChart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "# VNĐ",
                        data: data,
                        borderWidth: 1,
                        borderRadius: Number.MAX_VALUE,
                    },
                ],
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
            },
        });
    }

    /**
     * get total order in year
     * @param {DateTime} year year of get total order
     */
    function getTotalOrderInYear(year) {
        $.ajax({
            type: "POST",
            url: urlGetTotalOrderInYear,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                year: year,
            },
        })
            .done(function (res) {
                dataTotalOrder = Object.values(res.data);
                createChart(monthsInYear, dataTotalOrder);
            })
            .fail(function () {
                notiError();
            });
    }

    getTotalOrderInYear(currentYear);

    // Get 3 year order
    for (let i = currentYear; i >= currentYear - 3; i--) {
        $("#yearSelect").append(
            $("<option>", {
                value: i,
                text: i,
            })
        );
    }

    // Get total order when change year
    $("#yearSelect").change(function () {
        getTotalOrderInYear($(this).val());
    });
});
