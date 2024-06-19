<?php

namespace common\modules\user\controllers;

use common\modules\book\models\search\BookPromoCodeSearch;
use common\modules\order\models\search\OrderSearch;
use common\modules\user\models\search\EnrollSearch;
use common\modules\user\models\search\UserDeviceSearch;
use common\modules\user\models\search\UserPaymentSearch;
use common\modules\user\models\search\UserSearch;
use common\modules\user\models\User;
use common\modules\user\models\UserPayment;
use common\modules\userBalance\models\search\UserTariffSearch;
use Exception;
use soft\web\SoftController;
use Throwable;
use Yii;
use Yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\JsonExpression;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\JsExpression;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class UserController extends SoftController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'fill-balance' => [
                'class' => CreateUserPaymentAction::class,
            ]
        ];
    }

    //<editor-fold desc="CRUD" defaultstate="collapsed">

    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $query = User::find()
            ->andWhere(['!=', 'id', 1])
            ->withDevicesCount()
            ->withActiveDevicesCount();

        $dataProvider = $searchModel->search($query);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @throws \yii\base\Exception
     */
    public function actionCreate()
    {
        $model = new User();
        $model->scenario = User::SCENARIO_REGISTER;
        $model->status = User::STATUS_ACTIVE;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->password_hash = Yii::$app->security->generatePasswordHash($model->password);
            $model->auth_key = Yii::$app->security->generateRandomString();
            if ($model->save(false)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * @throws \yii\base\Exception
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->can('admin')) {
            forbidden();
        }

        if ($model->getIsDeleted()) {
            Yii::$app->session->setFlash('error', "O'chirilgan userni qayta tiklash imkoniyati mavjud emas!");
            return $this->redirect(Yii::$app->request->referrer);
        }

        $model->status = User::STATUS_ACTIVE;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            if (!empty($model->password)) {
                $model->password_hash = Yii::$app->security->generatePasswordHash($model->password);
            }

            if ($model->save(false)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    /**
     * @param $id
     * @return array|string
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->ajaxCrud($model)->viewAction();
    }

    /**
     * @param $id
     * @return array|Response
     * @throws Throwable
     * @throws InvalidConfigException
     * @throws StaleObjectException
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->can('admin') || $model->can('system-user')) {
            forbidden();
        }

        if ($model->getIsDeleted()) {
            Yii::$app->session->setFlash('error', "User oldin o'chirilgan!");
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->ajaxCrud($model)->deleteAction();
    }

    /**
     * @return User
     * @throws NotFoundHttpException
     */
    private function findModel($id)
    {
        /** @var User $model */
        $model = User::findOne(['id' => $id]);
        if ($model == null) {
            not_found();
        }
        return $model;
    }

    //</editor-fold>

    /**
     * @param $id mixed User id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionDevices($id)
    {

        $model = $this->findModel($id);
        $searchModel = new UserDeviceSearch();
        $query = $model->getDevices();
        $dataProvider = $searchModel->search($query);

        return $this->render('devices', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    /**
     * @param $id mixed User id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionPayments($id)
    {

        $model = $this->findModel($id);
        $searchModel = new UserPaymentSearch();
        $query = $model->getUserPayments()
            ->orderBy(['created_at' => SORT_DESC]);

        $dataProvider = $searchModel->search($query);

        return $this->render('payments', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    /**
     * @param $id mixed User id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionTariffs($id)
    {
        $model = $this->findModel($id);

        $searchModel = new UserTariffSearch();
        $query = $model->getUserTariffs();
        $dataProvider = $searchModel->search($query);

        return $this->render('tariffs', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    /**
     * @param $id mixed User id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionFavorites($id)
    {

        $model = $this->findModel($id);
        $dataProvider = new ActiveDataProvider([
            'query' => $model->getFavoriteFilms(),
        ]);
        return $this->render('favorites', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    /**
     * @return string
     */
    public function actionActiveBalance()
    {
        $minTariffSum = Tariff::find()
            ->min('price');

        $searchModel = new UserSearch();
        $query = User::find()
            ->alias('user')
            ->select([
                'user.id', 'user.username', 'user.firstname', 'user.lastname', 'user.status', 'user.created_at',
                'uPayment.amount as paymentSum',
                'uTariff.price as tariffSum',
                '(uPayment.amount -uTariff.price) as diff'
            ])
            ->leftJoin(['uTariff' => UserTariff::find()
                ->alias('t')
                ->select([
                    'IFNULL(sum(t.price),0) as price',
                    't.user_id'
                ])
//                ->andWhere(['<', 't.expired_at', time()])
                ->groupBy(['t.user_id'])
            ], 'uTariff.user_id=user.id')
            ->leftJoin(['uPayment' => UserPayment::find()
                ->alias('p')
                ->select([
                    'IFNULL(sum(p.amount), 0) as amount',
                    'p.user_id'
                ])->groupBy(['p.user_id'])
            ], 'uPayment.user_id=user.id')
            ->andWhere("(uPayment.amount -uTariff.price) >= {$minTariffSum}");

        $dataProvider = $searchModel->search($query);

        return $this->render('active-balance', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionBalanceActiveButTariffExpired(): string
    {
        $minTariffSum = Tariff::find()
            ->min('price');

        $searchModel = new UserSearch();
        $query = User::find()
            ->alias('user')
            ->select([
                'user.id',
                'user.status',
                'user.username',
                'user.lastname',
                'user.firstname',
                'user.created_at',
                'IFNULL(MIN(uTariff.price), 0) as tarifSum',
                'IFNULL(SUM(uPayment.amount),0) AS paymentSum',
            ])
            ->leftJoin(['uTariff' => UserTariff::find()
                ->alias('t')
                ->select([
                    'IFNULL(sum(t.price),0) as price',
                    't.user_id'
                ])
                ->groupBy(['t.user_id'])
            ], 'uTariff.user_id=user.id')
            ->leftJoin(['uPayment' => UserPayment::tableName()], 'uPayment.user_id=user.id')
            ->where("IFNULL(uTariff.price, 0) = 0")
            ->andWhere(new JsExpression('(SELECT sum(p.amount) as summa FROM user_payment as p where p.user_id = user.id group by p.user_id)  > 0'))
            ->groupBy([
                'user.id',
                'uTariff.user_id',
                'uPayment.user_id',
            ]);
        $dataProvider = $searchModel->search($query);

        return $this->render('_balance_active_but_tariff_expired', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionActiveBalanceButTariffExpired()
    {
        $minTariffSum = Tariff::find()
            ->min('price');
        $now = strtotime('now');
        $searchModel = new UserSearch();
        $query = User::find()
            ->alias('user')
            ->select([
                'user.id', 'user.username', 'user.firstname', 'user.lastname', 'user.status', 'user.created_at',
                'uPayment.amount as paymentSum',
                'uTariff.price as tariffSum',
                '(uPayment.amount -uTariff.price) as diff'
            ])
            ->leftJoin(['uTariff' => UserTariff::find()
                ->alias('t')
                ->select([
                    'IFNULL(sum(t.price),0) as price',
                    't.user_id',
                    'MAX(t.expired_at) as date'
                ])
                ->groupBy(['t.user_id'])
            ], 'uTariff.user_id=user.id')
            ->leftJoin(['uPayment' => UserPayment::find()
                ->alias('p')
                ->select([
                    'IFNULL(sum(p.amount), 0) as amount',
                    'p.user_id'
                ])->groupBy(['p.user_id'])
            ], 'uPayment.user_id=user.id')
            ->where("uTariff.date <= {$now}")
            ->andWhere("(uPayment.amount -uTariff.price) >= {$minTariffSum}");

        $dataProvider = $searchModel->search($query);

        return $this->render('_tariff_expired', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return void|Response
     * @throws NotFoundHttpException
     */
    public function actionBlock($id)
    {
        $model = $this->findModel($id);

        if ($model->is_blocked == User::$is_blocked_no) {
            $model->is_blocked = User::$is_blocked_yes;
            $model->save(false);
            return $this->redirect(Yii::$app->request->referrer);
        }
        if ($model->is_blocked == User::$is_blocked_yes) {
            $model->is_blocked = User::$is_blocked_no;
            $model->save(false);
            return $this->redirect(Yii::$app->request->referrer);
        }

    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionComments($id)
    {
        $model = $this->findModel($id);

        $searchModel = new FilmCommentSearch();
        $query = FilmComment::find()
            ->andWhere(['user_id' => $id]);
        $dataProvider = $searchModel->search($query);

        return $this->render('comments', [
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
    public function actionOrder($id)
    {
        $model = $this->findModel($id);
        $query = Order::find()
            ->andWhere(['user_id' => $id]);

        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search($query);

        return $this->render('user-order', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionLikes($id)
    {
        $model = $this->findModel($id);
        $query = LikeDislike::find()
            ->andWhere(['user_id' => $id])
            ->andWhere(['type_id' => LikeDislike::TYPE_LIKE]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC
                ]
            ]
        ]);

        return $this->render('like', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionDislikes($id)
    {
        $model = $this->findModel($id);
        $query = LikeDislike::find()
            ->andWhere(['user_id' => $id])
            ->andWhere(['type_id' => LikeDislike::TYPE_DISLIKE]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC
                ]
            ]
        ]);

        return $this->render('dislike', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionViews($id)
    {
        $model = $this->findModel($id);
        $query = LastSeenFilm::find()
            ->andWhere(['user_id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 50,
            ],
        ]);

        return $this->render('film-views', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionPromoCode($id)
    {
        $model = $this->findModel($id);
        $searchModel = new BookPromoCodeSearch();
        $query = $model->getPromoCodeBooks();
        $dataProvider = $searchModel->search($query);

        return $this->render('promo-code', [
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
    public function actionEnroll($id)
    {
        $model = $this->findModel($id);
        $searchModel = new EnrollSearch();
        $query = $model->getEnrolls();
        $dataProvider = $searchModel->search($query);

        return $this->render('enroll', [
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
    public function actionOrderBook($id)
    {
        $model = $this->findModel($id);
        $searchModel = new OrderSearch();
        $query = $model->getBookPayedOrders();
        $dataProvider = $searchModel->search($query);

        return $this->render('order-book', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

}
