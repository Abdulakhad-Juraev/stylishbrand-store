<?php

use common\models\User;

$today = strtotime('today');
$lastOneMonth = strtotime('-1 month');
$dates = [];
for ($i = 30; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime('-' . $i . ' days'));
    $dates[$date] = 0;
}

$users = User::find()
//    ->select(['count(*) as count', "(date_format(FROM_UNIXTIME(created_at), '%Y-%m-%d' )) as date"])
    ->andWhere(['>=', 'created_at', $lastOneMonth])
//    ->groupBy('date')
//    ->orderBy('date')
    ->asArray()
    ->all();
$arrays = [];

foreach ($dates as $key => $date) {
    $count = 0;

    foreach ($users as $user) {
        if (date('Y-m-d', $user['created_at']) == $key) {
            $count++;
        }
    }
    $arrays[$key] = $count;
}

foreach ($arrays as $key=>$user) {
    $dates[$key] = $user;
}
$data = [];
foreach ($dates as $key => $date) {
    $data[] = [
        intval(strtotime($key) . '000'), intval($date)
    ];
}
?>
<figure class="highcharts-figure">
    <div id="container"></div>
</figure>
<?php

$data = json_encode($data);
$css = <<<CSS
#container {
  height: 400px;
}

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
  max-width: 500px;
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

$js = <<<JS
var averages = {$data};
Highcharts.chart('container', {

  title: {
    text: 'S\o\'nggi 1 oylik ro\'yhatdan o\'tganlar soni'
  },

  xAxis: {
    type: 'datetime',
    accessibility: {
      // rangeDescription: 'Range: may 1st 2022 to may 31st 2022.'
    }
  },

  yAxis: {
    title: {
      text: null
    }
  },

  tooltip: {
    crosshairs: true,
    shared: true,
    valueSuffix: ' ta'
  },

  series: [{
    name: 'Foydalanuvchilar',
    data: averages,
    zIndex: 1,
    marker: {
      fillColor: 'white',
      lineWidth: 2,
      lineColor: Highcharts.getOptions().colors[0]
    }
  }]
});
JS;
$this->registerJs($js);
?>
