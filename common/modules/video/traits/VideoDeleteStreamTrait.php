<?php

namespace common\modules\video\traits;

use soft\helpers\SiteHelper;
use Yii;
use soft\helpers\FileHelper;
use yii\base\ErrorException;

trait VideoDeleteStreamTrait
{

    //<editor-fold desc="Delete Stream" defaultstate="collapsed">

    /**
     * @return bool
     */
    public function canDeleteStream()
    {
        return true;
//        return !$this->isStreaming();
    }

    /**
     * @return bool
     */
    public function deleteFullStream()
    {
        if (!$this->canDeleteStream()) {
            return false;
        }
        return $this->deleteStreamedVideo() && $this->clearStreamData();
    }

    /**
     * Medianing  stream qilingan videosini va shu video joylashgan papkalarni (agar shu papkalar bo'sh bo'lsa )
     * o'chirib tashlaydi
     * @return bool
     */
    public function deleteStreamedVideo(): bool
    {

        if (!$this->canDeleteStream()) {
            return false;
        }

        if (!$this->canDeleteOrgSrc()) {
            return false;
        }

        $directory = $this->getBaseStreamFolder();


        if (is_dir($directory)) {
            try {
                FileHelper::removeDirectory($directory);
                return true;
            } catch (ErrorException $e) {
                $message = 'Stream video o\'chirishda xatolik yuz berdi' . PHP_EOL . $e->getMessage();
                SiteHelper::flashError($message);
                return false;
            }
        }

        // in case base orginal video folder is changed
        $file = $this->getStreamPath();

        if (is_file($file)) {
            $dir = dirname($file);
            if (is_dir($dir)) {
                try {
                    FileHelper::removeDirectory($dir);
                } catch (ErrorException $e) {
                    $message = 'Stream video o\'chirishda xatolik yuz berdi' . PHP_EOL . $e->getMessage();
                    SiteHelper::flashError($message);
                    return false;
                }
            }
        }

        return true;

    }

    /**
     * @return bool
     */
    public function clearStreamData(): bool
    {
        if (!$this->canDeleteStream()) {
            return false;
        }
        $this->stream_src = null;
        $this->has_streamed_src = false;
        $this->stream_status_id = null;
        $this->stream_percentage = null;
        $this->stream_status_comment = null;
        $this->media_duration = null;
        $this->media_size = null;
        $this->representations = null;
        return $this->save(false, ['stream_src', 'has_streamed_src', 'stream_status_id', 'stream_percentage', 'stream_status_comment', 'media_duration', 'media_size', 'representations']);
    }

    //</editor-fold>

}
