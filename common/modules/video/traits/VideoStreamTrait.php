<?php

namespace common\modules\video\traits;

use common\modules\video\models\Video;
use common\services\TelegramService;
use Exception;
use Monolog\Logger;
use soft\helpers\FileHelper;
use Streaming\FFMpeg;
use Streaming\Format\X264;
use Yii;

trait VideoStreamTrait
{

    public static $defPer = 0;

    /**
     * Videoni stream qilish
     * @return bool
     * @throws \Yii\base\Exception
     */
    public function streamVideo()
    {

        if (!$this->isInQueue()) {
            $this->logStreamError("Film status is not 'must  be streamed'");
            return false;
        }

        $orgSrc = $this->getOrgSrcPath();

        if (!is_file($orgSrc)) {
            $this->logStreamError("Film has not Org src: " . $orgSrc);
//            $this->logStreamError($orgSrc);
//            $this->logStreamError();
            return false;
        }
        //delete old streamed files if exist
        $this->deleteStreamedVideo();

        $this->stream_status_id = Video::IS_STREAMING;
        $this->stream_status_comment = 'Stream started at ' . date('Y-m-d H:i:s');
        $startedTime = time();
        $this->save(false, ['stream_status_id', 'stream_status_comment']);

        if (Yii::$app->params['is_linux']) {
            $config = [
                'ffmpeg.binaries' => Yii::$app->params['ffmpegBin'],
                'ffprobe.binaries' => Yii::$app->params['ffprobeBin'],
                'timeout' => 7200, // The timeout for the underlying process
                'ffmpeg.threads' => 24,  // The number of threads that FFMpeg should use
            ];
        } else {
            $config = [
                'ffmpeg.binaries' => Yii::getAlias('@common/lib/FFmpeg/bin/ffmpeg.exe'),
                'ffprobe.binaries' => Yii::getAlias('@common/lib/FFmpeg/bin/ffprobe.exe'),
                'timeout' => 7200, // The timeout for the underlying process
                'ffmpeg.threads' => 24,  // The number of threads that FFMpeg should use
            ];
        }


        $log = new Logger('FFmpeg_Streaming');

        $ffmpeg = FFMpeg::create($config, $log);
        $video = $ffmpeg->open($orgSrc);
        $hls = $video->hls();

        $format = new X264();
        $format->on('progress', function ($video, $format, $percentage) {

            $this->logStreamProccess($percentage);
            self::$defPer = $percentage;

        });

        $generatedUrl = $this->generateStreamPath();
        $streamUrl = $generatedUrl['streamUrl'];
        $directory = $generatedUrl['streamDirectory'];
        $streamFileName = FileHelper::generateRandomName();
        $streamFileUrl = $streamUrl . '/' . $streamFileName;

        TelegramService::log('🔄Videoni qayta ishlash boshlandi. ' . PHP_EOL . '🎞' . $this->fullName);

        try {

            $hls->setFormat($format)
                ->autoGenerateRepresentations(self::REPRESENTATIONS)
                ->save($directory . '/' . $streamFileName);

        } catch (Exception $e) {
            $this->logStreamError($e->getMessage());
            return false;
        }

        self::$defPer = 0;

        $metadata = $hls->metadata();

        /** @var \Streaming\Representation[] $streams */
        $streams = $hls->getRepresentations()->all();

        $representations = [];

        foreach ($streams as $stream) {
            $representations[] = $stream->getHeight();
        }

        $this->media_duration = intval($metadata->getFormat()->get('duration'));
        $this->stream_src = $streamFileUrl;
        $this->stream_status_id = self::STREAM_FINISHED;
        $this->representations = implode(',', $representations);
        $this->has_streamed_src = true;
        $this->queue_id = null;
        $this->stream_percentage = 100;
        $this->stream_status_comment = 'Stream finished at ' . date('Y-m-d H:i:s');

        $saveAttributes = ['media_duration', 'stream_src', 'stream_status_id', 'representations', 'has_streamed_src', 'queue_id', 'stream_percentage', 'stream_status_comment'];

        try {
            $this->save(false, $saveAttributes);
        } catch (Exception $e) {

            $message = "Stream finished, but stream data was not saved: " . $e->getMessage();
            $this->logStreamError($message);
            return false;
        }

        try {
            $this->deleteFullOrgSrc();
            $this->logStreamSuccess("Stream finished successfully.", $startedTime);
        } catch (Exception $e) {
            $message = "Stream finished, but original video was not deleted!. " . $e->getMessage() . ".";
            $this->logStreamSuccess($message, $startedTime);
        }

        return true;
    }

    /**
     * Mediani stream qilish uchun url generatsiya qilish va shu url bo'yicha papka hosil qilish
     * @return string[]
     * @throws yii\base\Exception
     */
    public function generateStreamPath()
    {

        $innerFolderName = FileHelper::generateRandomName();
        $streamUrl = $this->getBaseStreamUrl() . '/' . $innerFolderName;
        $directory = Yii::getAlias('@frontend/web') . $streamUrl;

        if (!is_dir($directory)) {
            FileHelper::createDirectory($directory, 0777, true);
        }
        return [
            'streamUrl' => $streamUrl,
            'streamDirectory' => $directory,
        ];
    }

    //<editor-fold desc="LOG" defaultstate="collapsed">

    /**
     * @param string $message
     */
    public function logStreamError($message = "Stream Error")
    {
        $this->stream_status_id = self::STREAM_ERROR;
        $this->stream_status_comment = $message;
        $msg = '❌ Videoni stream qilishda xatolik yuz berdi!!!. ' . PHP_EOL . '🎞' . $this->fullName . PHP_EOL . $message;
        TelegramService::log($msg);
        $this->save(false, ['stream_status_id', 'stream_status_comment']);
        echo $message . PHP_EOL;

    }

    /**
     * @param string $message
     */
    public function logStreamSuccess($message = "Success", $startedAt = null)
    {
        $startMessage = '';
        if ($startedAt) {
            $startMessage = 'Started at: ' . date('Y-m-d H:i:s', $startedAt) . '.';
        }
        $finishedAt = time();
        $finishMessage = 'Finished at: ' . date('Y-m-d H:i:s', $finishedAt) . '.';

        $durationMessage = '';
        if ($startedAt) {
            $duration = $finishedAt - $startedAt;
            $durationMessage = 'Duration: ' . Yii::$app->formatter->asGmtime($duration) . "\n";
        }

        $this->stream_status_id = self::STREAM_FINISHED;
        $message = $message . PHP_EOL . $startMessage . PHP_EOL . $finishMessage . PHP_EOL . $durationMessage;
        $this->stream_status_comment = $message;

        $msg = '✅ Videoni stream qilish muvaffaqiyatli yakunlandi!!!. ' . PHP_EOL . '🎞' . $this->fullName . PHP_EOL . $message;
        TelegramService::log($msg);
        $this->save(false, ['stream_status_id', 'stream_status_comment']);
        echo $message . PHP_EOL;
    }

    /**
     * @param int|float $percentage
     */
    public function logStreamProccess($percentage)
    {

        if ($percentage != self::$defPer) {

            echo $this->getFullName() . " is streaming: " . $percentage . "% . " . date('Y-m-d H:i:s') . PHP_EOL;

            if ($percentage % 3 == 0 || $percentage == 1 || $percentage == 100) {
                $this->stream_percentage = $percentage;
                try {
                    $this->save(false, ['stream_percentage']);
                } catch (Exception $e) {
                    echo $e->getMessage() . PHP_EOL;
                }
            }
        }

    }

    //</editor-fold>

}
