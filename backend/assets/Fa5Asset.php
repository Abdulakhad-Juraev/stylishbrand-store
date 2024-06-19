<?php


namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class Fa5Asset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '/';
    public $css = [
        'fa5/css/fontawesome.css',
    ];

}
