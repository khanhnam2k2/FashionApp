let currentDate = new Date();
let currentYear = currentDate.getFullYear();
var dataTotalOrder = [];
var myChart;
var monthsInYear = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
$(document).ready(function () {
    const ctx = document.getElementById('myChart');

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
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: '# Money',
                    data: data,
                    borderWidth: 1,
                    borderRadius: Number.MAX_VALUE,
                },
                ],
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
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
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                year: year
            }
        }).done(function (res) {
            dataTotalOrder = Object.values(res.data);
            createChart(monthsInYear, dataTotalOrder);
        }).fail(function () {
            notiError();
        })
    }

    getTotalOrderInYear(currentYear);


    for (let i = currentYear; i >= currentYear - 3; i--) {
        $('#yearSelect').append($('<option>', {
            value: i,
            text: i
        }));
    }

    $('#yearSelect').change(function () {
        getTotalOrderInYear($(this).val())
    });


});