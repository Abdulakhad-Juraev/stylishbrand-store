<?php

/* @var $this soft\web\View */

use common\modules\user\models\UserPayment;

$this->title = "Ma'lumotlar";
$this->addBreadCrumb($this->title);

$today = strtotime('today');
$lastOneMonth = strtotime('-1 month');
$dates = [];
$newDate = [];
for ($i = 30; $i >= 0; $i--) {
    $date = date('d.m.Y', strtotime('-' . $i . ' days'));
    $dates[$date] = [
        'count' => 0,
        'summa' => 0,
    ];
}

//dd($dates);
$userPayments = UserPayment::find()
//    ->select(['count(*) as count', 'sum(amount) as summa', "(date_format(FROM_UNIXTIME(created_at), '%d.%m.%Y' )) as date"])
    ->andWhere(['>=', 'created_at', $lastOneMonth])
//    ->groupBy('date')
//    ->orderBy('date')
//    ->asArray()
    ->all();
//dd($userPayments);

foreach ($dates as $key => $date) {
    $count = 0;
    $sum = 0;
    foreach ($userPayments as $userPayment) {
        if (date('d.m.Y', $userPayment->created_at) === $key) {
            $count++;
            $sum += $userPayment->amount;
        }
    }
    $dates[$key]['count'] = $count;
    $dates[$key]['summa'] = $sum;
}

//foreach ($userPayments as $userPayment) {
//    $dates[$userPayment['date']]['count'] = $userPayment['count'];
//    $dates[$userPayment['date']]['summa'] = $userPayment['summa'];
//}


$monthDates = [];
$monthCount = [];
$monthSumma = [];

foreach ($dates as $date => $value) {
    $monthDates[] = $date;
    $monthCount[] = intval($value['count']);
    $monthSumma[] = intval($value['summa']);
}

?>

    <figure class="highcharts-figure">
        <div id="container"></div>
    </figure>

<?php
$css = <<<CSS
.highcharts-figure,
.highcharts-data-table table {
    min-width: 310px;
    max-width: 100%;
    margin: 1em auto;
}

.highcharts-data-table table {
    font-family: Verdana, sans-serif;
    border-collapse: collapse;
    border: 1px solid #ebebeb;
    margin: 10px auto;
    text-align: center;
    width: 100%;
    max-width: 100%;
}

.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}

.highcharts-data-table th {
    font-weight: 600;
    padding: 0.5em;
}

.highcharts-data-table td,
.highcharts-data-table th,
.highcharts-data-table caption {
    padding: 0.5em;
}

.highcharts-data-table thead tr,
.highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
}

.highcharts-data-table tr:hover {
    background: #f1f7ff;
}

CSS;

$this->registerCss($css);
$monthDates = json_encode($monthDates);
$monthCount = json_encode($monthCount);
$monthSumma = json_encode($monthSumma);
$js = <<<JS
Highcharts.chart('container', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'So\'nggi 1 oylik to\'lov qilgan foydalanuvchilar kunlik soni va miqdori'
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: {$monthDates}
       
    },
    yAxis: {
        title: {
            text: ''
        },
        labels: {
            formatter: function () {
                return this.value + '';
            }
        }
    },
    tooltip: {
        crosshairs: true,
        shared: true
    },
    plotOptions: {
        spline: {
            marker: {
                radius: 4,
                lineColor: '#666666',
                lineWidth: 1
            }
        }
    },
    series: [{
        name: 'Soni',
        marker: {
            symbol: 'square'
        },
        data: {$monthCount}

    }, {
        name: 'Miqdori',
        marker: {
            symbol: 'diamond'
        },
        data: {$monthSumma}
    }]
});
JS;
$this->registerJs($js);
?>