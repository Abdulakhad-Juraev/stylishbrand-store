<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 23-Mar-24, 21:05
 */

namespace common\modules\video\actions;

use common\modules\book\models\BookPart;
use common\modules\podcast\models\Podcast;
use common\modules\video\models\Video;
use Exception;
use soft\helpers\FileHelper;
use Yii;
use yii\base\Action;
use yii\web\Response;
use yii\web\UploadedFile;

class UploadAudioAction extends Action
{
    /**
     * @var string
     */
    public $modelClass = 'common\modules\video\models\Video';

    /**
     * @var string
     */
    public $view = '@common/modules/video/views/course/upload-audio';

    /**
     * @var Video
     */
    public $model;

    /**
     * @throws Exception
     */
    public function run($id)
    {

        $this->model = Video::findModel($id);

        $request = Yii::$app->request;

        if ($request->isPost && $request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;
            return $this->uploadAudioMedia();

        }

        return $this->controller->render($this->view, [
            'model' => $this->model,
        ]);

    }


    /**
     * @throws Exception
     */
    public function uploadAudioMedia()
    {

        $model = $this->model;
        $mediaFile = UploadedFile::getInstance($model, 'audio_org_src');
        if (!$mediaFile) {
            return $this->uploadError("Yuklash uchun media fayl topilmadi!");
        }

        $generatedUrl = $this->generateUrlForOrgVideo();
        $mediaUrl = $generatedUrl['mediaUrl'];
        $directory = $generatedUrl['directory'];

        if ($mediaFile->size > Video::maxAudioSize()) {
            return $this->uploadError("Fayl hajmi " . Yii::$app->formatter->asFileSize(Video::maxAudioSize()) . " dan katta bo'lmasligi kerak!");
        }

        $allowedExtensions = Video::allowedAudioExtensions();
        if (!in_array($mediaFile->extension, $allowedExtensions)) {
            return $this->uploadError("Faqat quyidagi kengaytmali fayllarni yuklashga ruxsat berilgan: " . implode(',', $allowedExtensions));
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

        $model->audio_org_src = $path;
        $model->audio_media_size = $mediaFile->size;
        $model->audio_has_org_src = true;

        if (!$model->save(false)) {
            return $this->uploadError("Media ma'lumtolarini saqlashda xatolik yuz berdi!!!");
        }

        $successMessage = "Audio muvaffaqiyatli yuklandi";
        Yii::$app->session->setFlash('success', $successMessage);
        return [
            'redirect' => to(['/video-manager/course/view', 'id' => $model->id]),
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
     * @throws Exception
     */
    public function generateUrlForOrgVideo(): array
    {

        $innerFolderName = FileHelper::generateRandomName();
        $mediaUrl = $this->model->getBaseAudioOrgSrcUrl() . '/' . $innerFolderName;
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