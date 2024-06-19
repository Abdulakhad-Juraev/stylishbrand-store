<?php
/*
 *  @author Shukurullo Odilov <shukurullo0321@gmail.com>
 *  @link telegram: https://t.me/yii2_dasturchi
 *  @date 21.05.2022, 10:38
 */


/* @var $this \yii\web\View */

use Monolog\Logger;
use Streaming\FFMpeg;
use Streaming\Format\X264;

$config = [
    'ffmpeg.binaries' => Yii::$app->params['ffprobeBin'],
    'ffprobe.binaries' => Yii::$app->params['ffmpegBin'],
    'timeout' => 7200, // The timeout for the underlying process
    'ffmpeg.threads' => 12,  // The number of threads that FFMpeg should use
];

$log = new Logger('FFmpeg_Streaming');


$orgSrc = 'e:\Video\stream\video1.mp4';
$ffmpeg = FFMpeg::create($config, $log);
$video = $ffmpeg->open($orgSrc);
$hls = $video->hls();

//$format = new X264();
//
//$hls->setFormat($format)
//    ->autoGenerateRepresentations(\common\modules\film\models\Film::REPRESENTATIONS);
//
//$metadata = $hls->metadata();
//
///** @var \Streaming\Representation[] $streams */
//$streams = $hls->getRepresentations()->all();

//dd($streams);


$film = \common\modules\film\models\Film::find()->andWhere(['has_streamed_src' => true])->one();

$src = $film->getMainStreamUrl();
echo \common\packages\videojs\VideojsWidget::widget([
    'source' => $src,
])
//echo $film->renderVideoPlayer();

?>
