<?php

namespace common\modules\video\traits;

use common\modules\video\components\VideoQueueJob;
use common\modules\video\models\Video;
use common\services\TelegramService;
use soft\helpers\SiteHelper;
use Yii;
use yii\base\ErrorException;
use yii\helpers\FileHelper;

trait OriginalVideoTrait
{

    /**
     * @return float|int
     */
    public static function maxVideoSize()
    {
        return 10 * 1024 * 1024 * 1024;
    }

    /**
     * @return array
     */
    public static function allowedExtensions(): array
    {
        return ['mp4', '3gp', 'avi', 'flv', 'wmv', 'mov', 'mpeg', 'mpg', 'm4v', 'mkv', 'webm', 'ogv'];
    }

    /**
     * @return bool
     */
    public function isVideoUploadable(): bool
    {
        return !$this->isStreaming();
    }

    /**
     * Original videoni yuklash uchun base papka manzili
     * @return false|string
     */
    public function getBaseOrgSrcUrl()
    {
        return self::BASE_ORIGINAL_URL . '/' . $this->id;
    }

    /**
     * Original videoni yuklash uchun base papka manzili
     * @return false|string
     */
    public function getBaseOrgSrcFolder()
    {
        return Yii::getAlias('@frontend/web') . $this->getBaseOrgSrcUrl();
    }

    /**
     * Filmning original videosining diskdagi joylashgan manzilini topish
     * @return string original videoning diskdagi manzili
     */
    public function getOrgSrcPath()
    {

        if (empty($this->org_src)) {
            return false;
        }
        return Yii::getAlias('@frontend/web') . $this->org_src;
    }

    /**
     * @return bool
     */
    public function canDeleteOrgSrc(): bool
    {
        return true;
//        return !$this->isStreaming();
    }

    /**
     * Medianing original videosini va shu video joylashgan papkani o'chirib tashlaydi.
     * Shuningdel org video bilan bog'liq ma'lumotlarni o'chirib tashlaydi.
     * @return bool
     * @throws \yii\db\Exception
     */
    public function deleteFullOrgSrc()
    {
        if (!$this->canDeleteOrgSrc()) {
            return false;
        }
        return $this->deleteOrgVideo() && $this->clearOrgSrcData();

    }

    /**
     * Original videoni va shu video joylashgan papkani o'chirib tashlaydi.
     * @return bool
     */
    public function deleteOrgVideo()
    {

        if (!$this->canDeleteOrgSrc()) {
            return false;
        }

        $directory = $this->getBaseOrgSrcFolder();


        if (is_dir($directory)) {
            try {
                FileHelper::removeDirectory($directory);
            } catch (ErrorException $e) {

                $message = "Original video o'chirishda xatolik yuz berdi" . PHP_EOL . $e->getMessage();
                SiteHelper::flashError($message);
                return false;
            }
        }

        // in case base orginal video folder is changed
        $file = $this->getOrgSrcPath();

        if (is_file($file)) {
            $dir = dirname($file);
            if (is_dir($dir)) {
                try {
                    FileHelper::removeDirectory($dir);
                } catch (ErrorException $e) {
                    $message = "Original video ochirishda xatolik yuz berdi" . PHP_EOL . $e->getMessage();
                    SiteHelper::flashError($message);
                    return false;
                }
            }
        }

        return true;

    }

    /**
     * Original video bilan bog'liq ma'lumotlarni o'chirib tashlaydi.
     * @return bool
     * @throws \yii\db\Exception
     */
    public function clearOrgSrcData()
    {

        if (!$this->canDeleteOrgSrc()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        if ($this->deleteQueue() === false) {
            $transaction->rollBack();
            return false;
        }

        $this->has_org_src = false;
        $this->org_src = null;
        $this->queue_id = null;

        if (!$this->save(false, ['has_org_src', 'org_src', 'queue_id'])) {
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();
        return true;

    }

    /**
     * @return int
     */
    public function deleteQueue()
    {
        if (!$this->queue_id) {
            return true;
        }
        Yii::$app->queue->remove($this->queue_id);
    }

    /**
     * @return bool
     */
    public function issetOrgVideo()
    {
        return is_file($this->getOrgSrcPath());
    }

    /**
     * Stream qilish uchun navbatga qo'yish
     * @return bool
     */
    public function pushToQueue()
    {

        $result = Yii::$app->queue->push(new VideoQueueJob(['videoId' => $this->id]));

        if ($result) {
            $this->stream_status_id = Video::IN_QUEUE;
            $this->queue_id = $result;
            return $this->save(false, ['stream_status_id', 'queue_id']);
        }
        return false;
    }

}
