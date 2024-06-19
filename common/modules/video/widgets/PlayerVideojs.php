<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 28-Mar-24, 15:54
 */

namespace common\modules\video\widgets;

use common\modules\video\models\Video;
use common\packages\videojs\VideojsWidget;
use yii\base\InvalidArgumentException;

class PlayerVideojs extends VideojsWidget
{
    /**
     * @var Video
     */
    public $model;

    /**
     * @var bool
     */
    public $renderQualitySelector = true;

    public function init()
    {

        if ($this->model == null) {
            throw new InvalidArgumentException('Model is not set');
        }

        $this->renderSource();
        parent::init();
    }

    /**
     * Render source
     */
    public function renderSource()
    {
        $this->source = $this->model->getSources();
    }
}