<?php


namespace common\modules\video\traits;

use common\modules\video\models\Video;
use common\modules\video\widgets\PlayerVideojs;
use common\packages\videojs\VideojsWidget;
use soft\helpers\Html;
use soft\helpers\Url;

trait VideoMediaSourceTrait
{

    /**
     * @param $options array
     * @return string
     * @throws \Exception
     */
    public function renderVideoPlayer(array $options = [])
    {
        $options['model'] = $this;
        return PlayerVideojs::widget($options);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getVideoPlayerField()
    {
        if ($this->has_streamed_src) {
            return $this->renderVideoPlayer();
        }
        return Html::tag('div', $this->getStreamSrcText(), ['class' => 'alert alert-info']);
    }

    /**
     * @return string
     */
    public function getStreamSrcText()
    {
        if ($this->stream_status_id == Video::IN_QUEUE) {
            return 'Ushbu filmga yuklangan video qayta ishlash uchun navbatda turibdi.';
        }
        if ($this->stream_status_id == Video::IS_STREAMING) {
            return 'Ushbu filmga yuklangan video qayta ishlashnmoqda.';
        }
        if ($this->stream_status_id == Video::STREAM_FINISHED) {
            return 'Ushbu filmga yuklangan video tayyor';
        }
        if ($this->stream_status_id == Video::STREAM_ERROR) {
            return 'Ushbu filmga yuklangan videoni qayta ishlashda xatolik yuz berdi.';
        }

        return 'Ushbu filmga video yuklanganmagan.';
    }

    /**
     * @return array
     */
    public function getSources()
    {
        $reps = $this->representationsList;
        $source = [];
        foreach ($reps as $rep) {
            $source[] = [
                'src' => Url::withHostInfo($this->streamUrl($rep)),
                'type' => 'application/x-mpegURL',
                'label' => $rep,
            ];
        }
        return $source;
    }

    public function getSourcesInSingleUrl()
    {
        $reps = $this->representationsList;
        $baseSrc = $this->stream_src;
        //  /april11/sintel/sintel-hd_,512x288_450_b,640x360_700_b,768x432_1000_b,1024x576_1400_m,.mp4.csmil/master.m3u8
        $repSource = '';
        foreach ($reps as $rep) {
            $repSource .= $rep . 'x' . $rep . '_' . $rep . '_b,';
            break;
        }
        return $baseSrc . '_' . $repSource . 'p.m3u8';


    }
}
