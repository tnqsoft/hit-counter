<?php

require_once("./classes/bootstrap.php");

use HitCounter\HitCounter;

$hitCounter = new HitCounter();
$hitCounter->trackingVisit();
$hitCounter->calculateSummary();

$list = $hitCounter->getVisited(7);
$labels = array();
$values = array();
foreach ($list as $item) {
    $labels[] = "'".$item->visit_date."'";
    $values[] = $item->total_view;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>PHP Hit Counter</title>
    </head>
    <body>

        <div style="width: 600px; height: 300px;">
            <canvas id="canvas1"></canvas>
        </div>

        <p>Online: <?php echo $hitCounter->getOnlineNow(); ?></p>
        <p>Visit Today: <?php echo $hitCounter->getVisitToday(); ?></p>
        <p>Visit Yesterday: <?php echo $hitCounter->getVisitYesterday(); ?></p>
        <p>Total Visit: <?php echo $hitCounter->getTotalVisit(); ?></p>

        <script src="js/moment-with-locales.min.js"></script>
        <script src="js/Chart.min.js"></script>

        <script>
            var color = Chart.helpers.color;
            //----------------------------------------------------------------------
            var dateCtx = document.getElementById("canvas1").getContext('2d');
            var dateChartData = {
                type: 'line',
                data: {
                    labels: [<?php echo implode(',', $labels); ?>],
                    datasets: [{
                        label: '',
                        data: [<?php echo implode(',', $values); ?>],
                        backgroundColor: 'rgba(255,255,255,0)',
                        borderColor: 'rgb(11, 157, 250)',
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        position: 'top',
                        display: false
                    },
                    title: {
                        display: true,
                        text: ''
                    },
                    scales: {
                        xAxes: [{
                            type: 'time',
                            time: {
                                unit: 'day',
                                displayFormats: {
                                    day: 'DD/MM'
                                },
                                tooltipFormat: 'DD/MM/YYYY'
                            },
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Date'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            ticks : {
                                beginAtZero : true
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Total view'
                            }
                        }]
                    },
                    tooltips: {
                        intersect: false,
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.yLabel;
                            },
                        }
                    }
                }
            };
            var dateChart = new Chart(dateCtx, dateChartData);
        </script>
    </body>
</html>
