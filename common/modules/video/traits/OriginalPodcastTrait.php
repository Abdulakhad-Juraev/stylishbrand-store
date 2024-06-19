<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 20-Apr-24, 09:57
 */

namespace common\modules\video\traits;

use ErrorException;
use soft\helpers\FileHelper;
use soft\helpers\SiteHelper;
use soft\helpers\Url;
use Yii;
use yii\db\Exception;

trait OriginalPodcastTrait
{
    /**
     * @return float|int
     */
    public static function maxAudioSize()
    {
        return 10 * 1024 * 1024 * 1024;
    }

    /**
     * @return array
     */
    public static function allowedAudioExtensions(): array
    {
        return ['mp4', 'mp3'];
    }


    /**
     * Original audio yuklash uchun base papka manzili
     * @return false|string
     */
    public function getBaseAudioOrgSrcUrl()
    {
        return self::BASE_ORIGINAL_AUDIO_URL . '/' . $this->id;
    }

    /**
     * Original audio yuklash uchun base papka manzili
     * @return false|string
     */
    public function getBaseAudioOrgSrcFolder()
    {
        return Yii::getAlias('@frontend/web') . $this->getBaseAudioOrgSrcUrl();
    }

    /**
     * Podkastning original audiosining diskdagi joylashgan manzilini topish
     * @return string original audioning diskdagi manzili
     */
    public function getOrgSrcAudioPath()
    {

        if (empty($this->audio_org_src)) {
            return false;
        }
        return Yii::getAlias('@frontend/web') . $this->audio_org_src;
    }

    /**
     * @return bool
     */
    public function canDeleteOrgAudioSrc(): bool
    {
        return true;
//        return !$this->isStreaming();
    }

    /**
     * Medianing original audiosini va shu audio joylashgan papkani o'chirib tashlaydi.
     * Shuningdel org audio bilan bog'liq ma'lumotlarni o'chirib tashlaydi.
     * @return bool
     * @throws Exception
     */
    public function deleteFullOrgAudioSrc()
    {
        if (!$this->canDeleteOrgAudioSrc()) {
            return false;
        }
        return $this->deleteOrgAudio() && $this->clearOrgAudioSrcData();

    }

    /**
     * Original audioni va shu audio joylashgan papkani o'chirib tashlaydi.
     * @return bool
     */
    public function deleteOrgAudio()
    {

        if (!$this->canDeleteOrgAudioSrc()) {
            return false;
        }

        $directory = $this->getBaseAudioOrgSrcFolder();


        if (is_dir($directory)) {
            try {
                FileHelper::removeDirectory($directory);
            } catch (ErrorException $e) {

                $message = "Original audio o'chirishda xatolik yuz berdi" . PHP_EOL . $e->getMessage();
                SiteHelper::flashError($message);
                return false;
            }
        }

        // in case base original audio folder is changed
        $file = $this->getOrgSrcAudioPath();

        if (is_file($file)) {
            $dir = dirname($file);
            if (is_dir($dir)) {
                try {
                    FileHelper::removeDirectory($dir);
                } catch (ErrorException $e) {
                    $message = "Original audio ochirishda xatolik yuz berdi" . PHP_EOL . $e->getMessage();
                    SiteHelper::flashError($message);
                    return false;
                }
            }
        }

        return true;

    }

    /**
     * Original audio bilan bog'liq ma'lumotlarni o'chirib tashlaydi.
     * @return bool
     * @throws Exception
     */
    public function clearOrgAudioSrcData()
    {

        if (!$this->canDeleteOrgAudioSrc()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();


        $this->audio_has_org_src = false;
        $this->audio_org_src = null;
        $this->audio_media_size = null;

        if (!$this->save(false, ['audio_has_org_src', 'audio_org_src','audio_media_size'])) {
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();
        return true;

    }

    /**
     * @return bool
     */
    public function issetOrgAudio()
    {
        return is_file($this->getOrgSrcAudioPath());
    }

    /**
     * @return string|null
     */
    public function getAudioSource()
    {
        return $this->audio_has_org_src ? Url::withHostInfo($this->audio_org_src) : '';
    }

    /**
     * @return string
     */
    function getFormatSizeUnits()
    {
        $bytes = $this->audio_media_size;

        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}