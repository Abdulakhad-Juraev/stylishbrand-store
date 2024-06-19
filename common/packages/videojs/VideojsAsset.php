<?php

namespace common\packages\videojs;

use yii\web\AssetBundle;

class VideojsAsset extends AssetBundle
{

    public $sourcePath = '@common/packages/videojs/assets';

    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];

    public $js = [
        'js/video.js',
        'js/videojs-http-streaming.js',
        'js/videojs.hotkeys.min.js',
        'js/youtube.min.js',
        "js/silvermine-videojs-quality-selector.min.js",
    ];

    public $css = [
        'css/video-js.css',
        'css/silvermine-videojs-quality-selector.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

}
