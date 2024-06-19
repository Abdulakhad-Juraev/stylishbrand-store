<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $baseUrl = '/template';

    public $css = [
        'css/main.css',
        '//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css',
    ];

    public $js = [
        'js/scrollreveal.js',
        'js/gsap.min.js',
        'js/ScrollTrigger.min.js',
        'js/swiper-bundle.min.js',
        'js/jsPlumb.min.js',
        'js/index.js',
        '//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js',
    ];

    public $depends = [
        'yii\bootstrap4\BootstrapPluginAsset',
    ];
}
