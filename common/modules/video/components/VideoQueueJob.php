<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 28-Mar-24, 12:31
 */

namespace common\modules\video\components;

use api\services\TelegramService;
use common\modules\video\models\Video;
use soft\helpers\SiteHelper;
use yii\base\Component;
use yii\queue\JobInterface;

class VideoQueueJob extends Component implements JobInterface
{
    public $videoId;

    /**
     * @param $queue
     */
    public function execute($queue)
    {

        $model = Video::findOne($this->videoId);

        $message = 'VideoQueueJob: Video not found: ' . $this->videoId;
        if ($model == null) {
            echo $message . "\n";
            return;
        }

        $message = "Started Executing Video Strem Job \n" . $model->name . "\n" . "Queue ID:" . $model->queue_id;
        echo $message . "\n";

        try {
            $model->streamVideo();
        } catch (\Exception $e) {
            $message = "Video Strem Job Error: \n" . $e->getMessage();
            SiteHelper::flashError($message);
        }

    }
}