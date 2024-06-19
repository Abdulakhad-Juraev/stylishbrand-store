<?php

namespace soft\widget\ajaxcrud;

class CrudAsset extends \yii\web\AssetBundle
{

    public $sourcePath = '@soft/widget/ajaxcrud/assets';

    public $css = [
        'ajaxcrud.css'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'kartik\grid\GridViewAsset',
    ];

    public $js = [
        'ModalRemote.js',
        'ajaxcrud.js',
    ];

}