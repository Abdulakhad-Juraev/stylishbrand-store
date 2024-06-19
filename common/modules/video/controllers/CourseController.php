<?php
/**
 * @author uluGbek <muhammadjonovulugbek98@gmail.com>
 * @link https://t.me/U_Muhammadjonov
 * @date 29-Mar-24, 11:55
 */

namespace common\modules\video\controllers;

use common\modules\video\actions\DeleteAudioAction;
use common\modules\video\actions\DeleteVideoAction;
use common\modules\video\actions\UploadAudioAction;
use common\modules\video\actions\UploadCourseVideoAction;
use common\modules\video\models\search\BeforeAfterCourseSearch;
use common\modules\video\models\search\HomeworkSearch;
use common\modules\video\models\search\VideoAdditionalOptionSearch;
use common\modules\video\models\search\VideoCommentSearch;
use common\modules\video\models\search\VideoOptionSearch;
use common\modules\video\models\search\VideoSearch;
use common\modules\video\models\search\VideoSeasonSearch;
use common\modules\video\models\Video;
use Exception;
use soft\helpers\ArrayHelper;
use soft\web\SoftController;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class CourseController extends SoftController
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
                    'delete-video' => ['POST'],
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
     * @return array
     */
    public function actions()
    {
        return [
            'upload-video' => [
                'class' => UploadCourseVideoAction::class
            ],
            'delete-video' => [
                'class' => DeleteVideoAction::class,
            ],
            'upload-audio' => [
                'class' => UploadAudioAction::class
            ],
            'delete-audio' => [
                'class' => DeleteAudioAction::class,
            ],
        ];
    }

    //<editor-fold desc="CRUD" defaultstate="collapsed">

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VideoSearch();
        $query = Video::find()
            ->andWhere(['serial_type_id' => Video::SERIAL_TYPE_SERIAL])
            ->nonPartial()
//            ->with(['category'])
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
        $model = Video::findModel($id);
        return $this->render('view', ['model' => $model]);
    }

    /**
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Video([
            'stream_status_id' => Video::NO_STREAM,
            'serial_type_id' => Video::SERIAL_TYPE_SERIAL,
        ]);

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', ['model' => $model]);
    }

    /**
     * @param integer $id
     * @return string|Response
     * @throws \Yii\web\NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionUpdate($id)
    {
        $model = Video::findModel($id);

        if ($model->getIsPartial()) {
            return $this->redirect(['update-part', 'id' => $model->id]);
        }


        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $transaction = Yii::$app->db->beginTransaction();

            if ($model->save(false)) {
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            }

            $transaction->rollBack();
        }

        return $this->render('update', ['model' => $model]);
    }

    /**
     * @param integer $id
     * @return mixed
     * @throws Throwable
     * @throws \Yii\web\NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = Video::findModel($id);

        if ($model->getIsSerial() && $model->partsCount > 0) {
            forbidden("Diqqat!. Ushbu serial ichida qismlar borligi uchun o'chirishga ruxsat berilmaydi!. 
            Serialni o'chriish uchun avval ichidagi qismlarni o'chriib chiqing");
        }

        if ($model->isStreaming()) {

            forbidden("Diqqat!. Hozirda ushbu video qayta ishlanmoqda. Shu sababli filmni o'chirishga ruxsat berilmaydi!
            <br>Birozdan so'ng qayta urinib ko'ring! ");
        }


        return $this->ajaxCrud($model)->deleteAction();
    }

    //</editor-fold>

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


    //</editor-fold>

    //<editor-fold desc="Fasllar" defaultstate="collapsed">
    /**
     * @param $id int
     * @return string
     * @throws \Yii\web\NotFoundHttpException
     */
    public function actionSeasons($id)
    {
        $model = Video::findModel($id);
        $searchModel = new VideoSeasonSearch();
        $query = $model->getSeasons()->withPartsCount()->withActivePartsCount();
        $dataProvider = $searchModel->search($query);
        return $this->render('seasons', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //</editor-fold>

    //<editor-fold desc="Parts - Serial Qismlari" defaultstate="collapsed">


    /**
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionParts($id)
    {
        $model = Video::findModel($id);
        $query = Video::find()
            ->andWhere(['parent_id' => $id])
            ->with(['season'])
            ->orderBy(['sort_order' => SORT_DESC]);

        $searchModel = new VideoSearch();

        $dataProvider = $searchModel->search($query);

        return $this->render('parts', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionCreatePart($id)
    {
        $parentModel = Video::findModel($id);

        if (!$parentModel->getIsSerial()) {
            forbidden();
        }

        $model = new Video([
            'parent_id' => $id,
            'serial_type_id' => Video::SERIAL_TYPE_PART,
//            'scenario' => Film::SCENARIO_PART
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->createVideoWeekDayAssigns();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create-part', ['model' => $model, 'parentModel' => $parentModel]);
    }

    /**
     * @param $id
     * @return string|Response
     * @throws \Yii\web\NotFoundHttpException
     */
    public function actionUpdatePart($id)
    {
        $model = Video::findModel($id);

        $model->week_days = ArrayHelper::getColumn($model->weekDays, 'week_id');

        if (!$model->getIsPartial()) {
            return $this->redirect(['update', 'id' => $model->id]);
        }

        if ($model->loadSave()) {
            $model->updateVideoWeekDayAssigns();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update-part', ['model' => $model]);
    }

    //</editor-fold>


    //<editor-fold desc="Comments">


    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionViews($id)
    {
        $model = Film::findModel($id);

        $query = LastSeenFilm::find()
            ->andWhere(['film_id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 50,
            ],
        ]);

        return $this->render('views', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return array|void|Response
     * @throws \Yii\web\NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionAddToQueue($id)
    {
        $model = Video::findModel($id);


        if (!$model->issetOrgVideo()) {
            forbidden("Ushbu videoda original mavjud emas!");
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

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionOptions($id)
    {
        $model = Video::findModel($id);

        $searchModel = new VideoOptionSearch();
        $dataProvider = $searchModel->search($model->getOptions());

        return $this->render('options', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionBeforeAfters($id)
    {
        $model = Video::findModel($id);

        $searchModel = new BeforeAfterCourseSearch();
        $dataProvider = $searchModel->search($model->getBeforeAfters());

        return $this->render('before-afters', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionVideoAdditionalOption($id)
    {
        $model = Video::findModel($id);

        $searchModel = new VideoAdditionalOptionSearch();
        $dataProvider = $searchModel->search($model->getVideoAdditionalOptions());

        return $this->render('video-additional-option', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionVideoComment($id)
    {
        $model = Video::findModel($id);

        $searchModel = new VideoCommentSearch();
        $dataProvider = $searchModel->search($model->getVideoComments());

        return $this->render('comments', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    public function actionHomework($id)
    {
        $model = Video::findModel($id);
        $searchModel = new HomeworkSearch();
        $dataProvider = $searchModel->search($model->getHomeworks());
        return $this->render('homework', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }


}