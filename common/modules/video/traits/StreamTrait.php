<?php

namespace common\modules\video\traits;

use common\modules\video\models\Video;
use soft\helpers\ArrayHelper;
use soft\helpers\Url;
use soft\widget\button\ConfirmButton;
use Yii;

/**
 * @property string $streamPath
 * @property string[]|int[] $representationsList
 */
trait StreamTrait
{

    /**
     * @var null|array
     */
    private $_representationsList;

    use VideoStreamTrait;
    use VideoDeleteStreamTrait;

    //<editor-fold desc="Stream Status" defaultstate="collapsed">

    /**
     * @return string[]
     */
    public static function streamStatuses(): array
    {
        return [
            self::NO_STREAM => 'Yuklanmagan',
            self::IN_QUEUE => 'Navbatda',
            self::IS_STREAMING => 'Qayta ishlanmoqda',
            self::STREAM_FINISHED => 'Tayyor',
            self::STREAM_ERROR => 'Xatolik',
        ];
    }

    /**
     * @return mixed|null
     */
    public function getStreamStatusName()
    {
        return ArrayHelper::getArrayValue(self::streamStatuses(), $this->stream_status_id, $this->stream_status_id);
    }

    /**
     * Video ayni vaqtda stream qilinyaptimi?
     * @return bool
     */
    public function isStreaming(): bool
    {
        return $this->stream_status_id == Video::IS_STREAMING;
    }

    /**
     * Video ayni vaqtda stream qilinyaptimi?
     * @return bool
     */
    public function isInQueue(): bool
    {
        return $this->stream_status_id == Video::IN_QUEUE;
    }

    /**
     * @return string
     */
    public function getStreamPercent()
    {
        if (!$this->stream_percentage) {
            return '';
        }
        return $this->stream_percentage . '%';
    }

    //</editor-fold>

    //<editor-fold desc="Stream Url" defaultstate="collapsed">

    /**
     * Original videoni yuklash uchun base papka manzili
     * @return false|string
     */
    public function getBaseStreamUrl()
    {
        return self::BASE_STREAM_URL . '/' . $this->id;
    }

    /**
     * Original videoni yuklash uchun base papka manzili
     * @return false|string
     */
    public function getBaseStreamFolder()
    {
        return Yii::getAlias('@frontend/web') . $this->getBaseStreamUrl();
    }

    /**
     * Medianing stream qilingan videosining diskdagi joylashgan manzilini topish
     * @return string|false  stream qilingan videoning diskdagi manzili
     */
    public function getStreamPath()
    {
        if (empty($this->stream_src)) {
            return false;
        }
        return Yii::getAlias('@frontend/web') . $this->stream_src . '.m3u8';
    }

    /**
         * Medianing stream qilingan videosining diskdagi joylashgan manzilini topish
     * @return string  stream qilingan videoning diskdagi manzili
     */
    public function streamUrl($represantation = null)
    {
        if (empty($this->stream_src)) {
            return '';
        }

        if ($represantation == null) {
            return Url::withHostInfo($this->stream_src . '.m3u8');
        }

        return $this->stream_src . '_' . $represantation . 'p.m3u8';
    }

    /**
     * @return string
     */
    public function getMainStreamUrl()
    {
        return $this->streamUrl();
    }

    //</editor-fold>

    /**
     * @return array
     */
    public function getRepresentationsList(): array
    {
        if ($this->_representationsList === null) {

            $reps = $this->representations;
            if (empty($reps)) {
                $this->_representationsList = [];
            } else {
                $this->_representationsList = explode(',', $reps);
            }

        }
        return $this->_representationsList;
    }

    /**
     * @param $url
     * @param bool $ajax
     * @return string
     * @throws \Exception
     */
    public function addToQueueButton(bool $ajax = false, $url = null)
    {

        if (!$this->has_org_src) {
            return '';
        }

        $queueId = $this->queue_id;

        if ($queueId && Yii::$app->queue->isWaiting($this->queue_id)) {

            return '';
        }

        if ($url === null) {
            $url = ['add-to-queue', 'id' => $this->id];
        }

        return ConfirmButton::widget([
            'title' => "Qayta navbatga qo'yish",
            'confirmMessage' => "Siz ushbu videoni qaytadan navbatga qo'ymoqchimisiz?",
            'content' => $ajax ? '' : "Qayta navbatga qo'yish",
            'url' => $url,
            'ajax' => $ajax,
            'cssClass' => 'btn btn-warning',
            'icon' => 'sync-alt',
        ]);

    }

}
