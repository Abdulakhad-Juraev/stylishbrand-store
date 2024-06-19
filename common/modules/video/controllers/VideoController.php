<?php

namespace common\modules\video\controllers;

use common\modules\video\actions\DeleteVideoAction;
use common\modules\video\actions\UploadVideoAction;
use Exception;
use Yii;
use common\modules\video\models\Video;
use common\modules\video\models\search\VideoSearch;
use soft\web\SoftController;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

class VideoController extends SoftController
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'bulk-delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ]
        ];
    }

    /**
     * @return array[]
     */
    public function actions()
    {
        return [
            'upload-video' => [
                'class' => UploadVideoAction::class
            ],
            'delete-video' => [
                'class' => DeleteVideoAction::class,
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VideoSearch();

        $query = Video::find()
            ->andWhere(['serial_type_id' => Video::SERIAL_TYPE_SINGLE])
            ->nonPartial()
            ->orderBy(['sort_order' => SORT_ASC, 'created_at' => SORT_DESC])
            ->with(['category'])
            ->localized();

        $dataProvider = $searchModel->search($query);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param integer $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->ajaxCrud($model)->viewAction();
    }

    /**
     * @return string
     */
    public function actionCreate()
    {
        $model = new Video([
            'serial_type_id' => Video::SERIAL_TYPE_SINGLE,
        ]);

        return $this->ajaxCrud($model)->createAction();
    }

    /**
     * @param integer $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (!$model->getIsUpdatable()) {
            forbidden();
        }
        return $this->ajaxCrud($model)->updateAction();
    }

    /**
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {

        $model = Video::findModel($id);

        if ($model->getIsSerial()) {
            forbidden("Diqqat!. Ushbu serial ichida qismlar borligi uchun o'chirishga ruxsat berilmaydi!. 
            Serialni o'chriish uchun avval ichidagi qismlarni o'chriib chiqing");
        }

        if ($model->isStreaming()) {
            forbidden("Diqqat!. Hozirda ushbu video qayta ishlanmoqda. Shu sababli filmni o'chirishga ruxsat berilmaydi!
            <br>Birozdan so'ng qayta urinib ko'ring! ");
        }

        if (!$model->getIsDeletable()) {
            forbidden();
        }

        $model->delete();

        return $this->ajaxCrud($model)->deleteAction();
    }

    /**
     * @param $id
     * @return Video
     * @throws yii\web\NotFoundHttpException
     */
    public function findModel($id)
    {
        $model = Video::find()->andWhere(['id' => $id])->one();
        if ($model == null) {
            not_found();
        }
        return $model;
    }

    //<editor-fold desc="Queue" defaultstate="collapsed">
    public function actionQueue()
    {
        $searchModel = new VideoSearch();
        $query = Video::find()
            ->andWhere(['stream_status_id' => [Video::IN_QUEUE, Video::IS_STREAMING]])
            ->orderBy(['stream_status_id' => SORT_DESC, 'id' => SORT_ASC]);

        $dataProvider = $searchModel->search($query);

        return $this->render('queue', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return array|Response
     * @throws \Yii\web\NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionAddToQueue($id)
    {
        $model = Video::findModel($id);


        if (!$model->issetOrgVideo()) {
            forbidden("UShbu videoda original mavjud emas!");
        }

        try {
            $model->deleteQueue();
            $model->pushToQueue();
        } catch (Exception $e) {
            return not_found($e->getMessage());
        }


        if ($this->isAjax) {
            $this->formatJson();
            $ajaxCrud = $this->ajaxCrud;
//            $ajaxCrud->forceReload = false;
            return $ajaxCrud->closeModal();
        }

        return $this->back();

    }
    //</editor-fold>
}
