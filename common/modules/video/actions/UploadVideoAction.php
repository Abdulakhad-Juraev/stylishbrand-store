<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 28-Mar-24, 16:33
 */

namespace common\modules\video\actions;

use common\modules\video\models\Video;
use soft\helpers\FileHelper;
use Yii;
use yii\base\Action;
use yii\web\Response;
use yii\web\UploadedFile;

class UploadVideoAction extends Action
{
    public $modelClass = 'common\modules\video\models\Video';

    public $view = '@common/modules/video/views/video/upload-video';

    /**
     * @var Video
     */
    public $model;

    /**
     * @throws \yii\base\Exception
     */
    public function run($id)
    {

        $this->model = Video::findModel($id);

//        if (!$this->model->isVideoUploadable()) {
//            forbidden("Ushbu film uchun video yuklab bo'lmaydi");
//        }

        $this->model->scenario = Video::SCENARIO_UPLOAD_VIDEO;

        $request = Yii::$app->request;

        if ($request->isPost && $request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;
            return $this->uploadMedia();

        }

        return $this->controller->render($this->view, [
            'model' => $this->model,
        ]);

    }


    /**
     * @throws \yii\base\Exception
     */
    public function uploadMedia()
    {

        $model = $this->model;
        $mediaFile = UploadedFile::getInstance($model, 'org_src');
        if (!$mediaFile) {
            return $this->uploadError("Yuklash uchun media fal topilmadi!");
        }

        $generatedUrl = $this->generateUrlForOrgVideo();
        $mediaUrl = $generatedUrl['mediaUrl'];
        $directory = $generatedUrl['directory'];

        if ($mediaFile->size > Video::maxVideoSize()) {
            return $this->uploadError("Fayl hajmi " . Yii::$app->formatter->asFileSize(Video::maxVideoSize()) . " dan katta bo'lmasligi kerak!");
        }

        $allowedExtensions = Video::allowedExtensions();
        if (!in_array($mediaFile->extension, $allowedExtensions)) {
            return $this->uploadError("Faqat quyidagi kengaytmali fayllarni yuklashga ruxsat berilgan: " . implode(',', $allowedExtensions));
        }


        if (!$model->deleteFullOrgSrc()) {
            return $this->uploadError("Original videoni o'chirib tashlashda xatolik yuz berdi!");
        }

        if (!$model->deleteFullStream()) {
            return $this->uploadError("Stream videoni o'chirib tashlashda xatolik yuz berdi!");
        }

        $fileName = FileHelper::generateRandomName() . '.' . $mediaFile->extension;
        $filePath = $directory . '/' . $fileName;

        if (!is_dir($directory)) {
            FileHelper::createDirectory($directory, 0777);
        }

        if (!$mediaFile->saveAs($filePath)) {
            return $this->uploadError($this->uploadErrorMessage($mediaFile->error));
        }

        $path = $mediaUrl . DIRECTORY_SEPARATOR . $fileName;

        $model->org_src = $path;
        $model->media_size = $mediaFile->size;
        $model->stream_status_id = Video::IN_QUEUE;
        $model->has_org_src = true;

        if (!$model->save(false)) {
            return $this->uploadError("Media ma'lumtolarini saqlashda xatolik yuz berdi!!!");
        }

        if (!$model->pushToQueue()) {
            return $this->uploadError("Videoni navbatga qo'yishda xatolik yuz berdi!!!");
        }

        $successMessage = "Video muvaffaqiyatli yuklandi! Yuklangan videoni birozdan so'ng ko'rishingiz mumkin";
        Yii::$app->session->setFlash('success', $successMessage);
        return [
            'redirect' => to(['video/view', 'id' => $model->id]),
            'status' => 200,
        ];

    }

    /**
     * @param $message string
     * @param $status int
     * @return array
     */
    public function uploadError(string $message = "Xatolik yuz berdi", int $status = 500): array
    {
        Yii::$app->response->statusCode = $status;
        return [
            'status' => $status,
            'message' => $message,
        ];
    }

    /**
     * @param $error_code int|string
     * @return string
     */
    public function uploadErrorMessage($error_code): string
    {
        $errors = [
            0 => 'There is no error, the file uploaded with success',
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'A PHP extension stopped the file upload.',
        ];

        return $errors[$error_code] ?? 'Unknown error';

    }

    /**
     * Yuklanayotgan video uchun random url generatsiya qilish va shu url bo'yicha papka hosil qilish
     * @return string[]
     * @throws \yii\base\Exception
     */
    public function generateUrlForOrgVideo(): array
    {

        $innerFolderName = FileHelper::generateRandomName();
        $mediaUrl = $this->model->getBaseOrgSrcUrl() . '/' . $innerFolderName;
        $directory = Yii::getAlias('@frontend/web') . $mediaUrl;
        if (!is_dir($directory)) {
            FileHelper::createDirectory($directory, 0777);
        }
        return [
            'mediaUrl' => $mediaUrl,
            'directory' => $directory,
        ];
    }
}