<?php


namespace backend\assets;

use yii\web\AssetBundle;

class AdminLte3Asset extends AssetBundle
{

    public $baseUrl = '@web/template/adminlte3/';

    public $css = [
        'base-assets/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css',
        'custom.css',
    ];

    public $js = [
        'base-assets/sweetalert2/sweetalert2.min.js',
        'base-assets/chart.js/Chart.min.js',
        'base-assets/js/adminlte.min.js',
        'base-assets/js/demo.js',
        'custom.js',
        'https://code.highcharts.com/highcharts.js',
        'https://code.highcharts.com/modules/data.js',
        'https://code.highcharts.com/highcharts-more.js',
        'https://code.highcharts.com/modules/exporting.js',
        'https://code.highcharts.com/modules/export-data.js',
        'https://code.highcharts.com/modules/accessibility.js',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
        'yii\web\YiiAsset',
    ];

}
