// function to get first day of the week
function FirstDayOfWeek (week, year) {
    let date = new Date(year, 0, 1 + week * 7)
    date.setDate(date.getDate() + (1 - date.getDay())) // 0 - Sunday, 1 - Monday etc
    return date
}

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

function ShowChart () {
    let week = document.getElementById('week_input').value
    let year = week.split('-')[0] * 1,
        weekNumber = week.split('-')[1].substring(1) * 1 // to skip the W

    let currentDay = FirstDayOfWeek(weekNumber, year) // get the first day of the week

    let chartLabels = []
    for (let i = 0; i < 7; i++) {
        chartLabels.push(FormatDate(currentDay))
        currentDay.setDate(currentDay.getDate() + 1) // move to next day
    }

    // render chart with the new days label
    var calorie_bar_chart = document
        .getElementById('calorieBarChartWeekly')
        .getContext('2d')
    var BarChart = new Chart(calorie_bar_chart, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [
                {
                    data: [12, 19, 11, 15, 9, 13, 17],
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
