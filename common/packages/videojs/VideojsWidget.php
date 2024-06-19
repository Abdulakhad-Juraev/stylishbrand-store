<?php

namespace common\packages\videojs;

use http\Exception\InvalidArgumentException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\base\Widget;
use yii\web\JsExpression;
use yii\web\View;

/**
 *
 * @property string|mixed $playerName
 */
class VideojsWidget extends Widget
{
    /**
     * @var array Html options for video tag
     */
    public $options = [];

    /**
     * @var string|array source url of video
     */
    public $source;

    /**
     * @var array videojs player 'src' options. This options will be rendered as javascript
     * @see https://docs.videojs.com/docs/api/player.html#Methodssrc
     */
    public $sourceOptions = [];

    /**
     * @var int|null the amount of seconds of video (optional)
     */
    public $duration;

    /**
     * @var string url to poster image
     */
    public $poster;

    /**
     * @var int Starter point of the video
     */
    public $currentTime = 0;

    /**
     * @var array plugin options. This options will be rendered as javascript
     * @see https://videojs.com/guides/options
     */
    public $pluginOptions = [];

    /**
     * @var bool
     */
    public $registerEvents = false;

    /**
     * @var bool
     */
    public $isYoutubeLink = false;

    /**
     * @var bool
     */
    public $renderQualitySelector = false;

    /**
     * @var string Player name
     */
    private $_playerName;

    /**
     * @return mixed
     */
    public function getPlayerName()
    {
        return $this->_playerName;
    }

    /**
     * @param mixed $playerName
     */
    public function setPlayerName($playerName)
    {
        $this->_playerName = $playerName;
    }




    public function run()
    {



        $this->options = ArrayHelper::merge($this->defaultOptions(), $this->options);
        Html::addCssClass($this->options, 'video-js vjs-theme-fantasy');

        $id = $this->options['id'];
        $this->setPlayerName('player_' . $id);

        $this->sourceOptions = ArrayHelper::merge($this->defaultSourceOptions(), $this->sourceOptions);
        $this->pluginOptions = ArrayHelper::merge($this->defaultPluginOptions(), $this->pluginOptions);
        $this->registerAssets();
        $vjsNoJs = '  <p class="vjs-no-js">
            To view this video please enable JavaScript, and consider upgrading to a web browser that
            <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
        </p>';
        echo Html::tag('video', $vjsNoJs, $this->options);

    }

    public function defaultOptions()
    {
        $options = [
            'id' => $this->getId(),
            'controls' => true,
            'preload' => 'auto',
            'height' => 'auto',
        ];

        if ($this->poster) {
            $options['poster'] = $this->poster;
        }
        return $options;
    }

    /**
     * Default plugin options
     * @return array
     * @see pluginOptions
     */
    public function defaultPluginOptions()
    {
        return [
            'fluid' => true,
        ];
    }

    /**
     * Default optios for videojs source
     * @return array
     * @see sourceOptions
     */
    public function defaultSourceOptions()
    {
        return [
            'type' => $this->isYoutubeLink() ? 'video/youtube' : 'application/x-mpegURL',
            'withCredentials' => true,
        ];
    }

    /**
     * Register assets
     */
    public function registerAssets()
    {
        VideojsAsset::register($this->view);
        $this->initPlayer();
        $this->setPlayerDuration();
        $this->setPlayerCurrentTime();
    }

    /**
     * Init player
     */
    public function initPlayer()
    {

        $id = $this->options['id'];
        $pluginOptions = Json::encode($this->pluginOptions);
        $source = Json::encode($this->renderSource());

        $js = new JsExpression("
                let {$this->playerName} = videojs('{$id}',{$pluginOptions});
                {$this->playerName}.src({$source});
                let tracks = {$this->playerName}.textTracks();
                console.log(tracks); 
                videojs('{$this->getId()}').ready(function() { this.hotkeys({ volumeStep: 0.1, seekStep: 5, enableModifiersForNumbers: false });});
            ");

        if ($this->renderQualitySelector) {
            $js .= new JsExpression("{$this->playerName}.controlBar.addChild('QualitySelector');");
        }

        $this->view->registerJs($js, View::POS_END);
    }


    /**
     * @return array
     */
    private function renderSource()
    {
        $source = $this->source;
        $sourceOptions = $this->sourceOptions;
        if (is_string($source)) {
            return ArrayHelper::merge(['src' => $source], $sourceOptions);
        }

        if (is_array($source)) {
            $sources = [];
            foreach ($source as $src) {
                if (is_string($src)) {
                    $sources[] = ArrayHelper::merge(['src' => $src], $sourceOptions);
                } else {
                    $sources[] = ArrayHelper::merge($sourceOptions, $src);
                }
            }
            return $sources;
        }
        return [];
    }

    /**
     * Set player duration
     */
    public function setPlayerDuration()
    {
        $duration = intval($this->duration);
        if ($duration > 0) {
            $js = new JsExpression("
             {$this->playerName}.duration = function() {
                  return {$duration}; 
                }
            ");

            $this->view->registerJs($js);
        }
    }

    /**
     * Set player current time
     */
    public function setPlayerCurrentTime()
    {
        $duration = intval($this->duration);
        $currentTime = intval($this->currentTime);
        if ($currentTime > 0) {
            if ($duration > 0) {
                if ($currentTime <= $duration) {
                    $js = new JsExpression("
                        {$this->playerName}.currentTime({$currentTime}) 
                    ");
                    $this->view->registerJs($js);
                }
            } else {
                $js = new JsExpression("
                        {$this->playerName}.currentTime({$currentTime}) 
                    ");
                $this->view->registerJs($js);
            }

        }
    }

    /**
     * @return bool
     */
    public function isYoutubeLink()
    {
        if ($this->isYoutubeLink === null) {
            $this->isYoutubeLink = strpos($this->source, 'youtube') !== false;
        }
        return $this->isYoutubeLink;
    }

}
