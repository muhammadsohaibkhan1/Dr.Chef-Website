// function to format the date to dd/MM/yyyy
function FormatDate (d) {
    var dd = d.getDate()
    var mm = d.getMonth() + 1

    var yyyy = d.getFullYear()
    if (dd < 10) {
        dd = '0' + dd
    }
    if (mm < 10) {
        mm = '0' + mm
    }
    return dd + '/' + mm + '/' + yyyy
}

function DaysInMonth(month,year) {
   return new Date(year, month, 0).getDate();
  }

function getFirstDayOfMonth(year, month) {
    return new Date(year, month-1, 1);
  }

function ShowChart () {
    let month = document.getElementById('month_input').value;
    let yearValue = month.split('-')[0] * 1,
        monthValue = month.split('-')[1] * 1 // to skip the W
    let totalDays = DaysInMonth(monthValue, yearValue) // get the first day of the month
    let firstDay = getFirstDayOfMonth(yearValue, monthValue)
    let chartLabels = []
    let chartData = []
    let chartDataVar = 5

    for (let i = 0; i < totalDays; i++) {
        chartLabels.push(FormatDate(firstDay))
        firstDay.setDate(firstDay.getDate() + 1) // move to next day
    }

    for (let i = 0; i < totalDays; i++) {
        chartData.push(chartDataVar++)
    }

    // render chart with the new days label
    var calorie_bar_chart = document
        .getElementById('calorieBarChartMonthly')
        .getContext('2d')
    var BarChart = new Chart(calorie_bar_chart, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [
                {
                    data: chartData,
                    backgroundColor: [
                        'rgba(61, 37, 30, 0.6)',
                        'rgba(161, 120, 109, 0.6)',
                        'rgba(61, 37, 30, 0.6)',
                        'rgba(161, 120, 109, 0.6)',
                        'rgba(61, 37, 30, 0.6)',
                        'rgba(161, 120, 109, 0.6)',
                        'rgba(61, 37, 30, 0.6)'
                    ],
                    borderColor: [
                        'rgba(61, 37, 30, 1)',
                        'rgba(161, 120, 109, 1)',
                        'rgba(61, 37, 30, 1)',
                        'rgba(161, 120, 109, 1)',
                        'rgba(61, 37, 30, 1)',
                        'rgba(161, 120, 109, 1)',
                        'rgba(61, 37, 30, 1)'
                    ],
                    borderWidth: 1
                }
            ]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [
                    {
                        ticks: {
                            beginAtZero: true
                        }
                    }
                ]
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}
