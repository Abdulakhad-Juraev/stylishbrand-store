<?php

namespace backend\controllers;

use backend\models\Report;
use Yii;
use soft\web\SoftController;
use backend\models\LoginForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class SiteController extends SoftController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'error', 'index','cache-flush','chart'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return \yii\web\Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $this->layout = 'blank';
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
                return $this->redirect(['site/index']);
        }
        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string|\yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionCacheFlush()
    {
        Yii::$app->cache->flush();
        Yii::$app->session->setFlash('success', 'Cache has been successfully cleared');
        return $this->back();
    }

    /**
     * @return string
     */
    public function actionChart()
    {
        return $this->render('chart_info');
    }

    public function actionTest()
    {
        echo $_SERVER['REMOTE_ADDR'];
        die();
        return $this->render('test');
    }

    public function actionReport()
    {
        $model = new Report();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $month = $model->month ?? date('m');
            $year = $model->year ?? date('Y');
            $dates = [
                'month' => $month,
                'year' => $year
            ];
            return $this->render('report', [
                'model' => $model,
                'dates' => $dates
            ]);

        } else {
            $month = $model->month ?? date('m');
            $year = $model->year ?? date('Y');

            $dates = [
                'month' => $month,
                'year' => $year
            ];
        }

        return $this->render('report', [
            'model' => $model,
            'dates' => $dates
        ]);
    }

    /**
     * @return string
     */
    public function actionAnalytics()
    {

        return $this->render('analytics');
    }

    public function actionKick()
    {
//        $this->bot('kickChatMember', [
//            'chat_id' => '-1001653680126',
//            'user_id' => 1519004012,
//        ]);
//
//
//        $this->bot('unbanChatMember', [
//            'chat_id' => '-1001653680126',
//            'user_id' => '1519004012',
//        ]);
//
//        $channelResult1 = $this->bot('GetChatMember', [
//            'chat_id' => '-1001653680126',
//            'user_id' => '1519004012',
//        ])->result->status;
//
//
//
//        $this->bot('sendMessage', [
//            'chat_id' => 1519004012,
//            'text' => json_encode($channelResult1),
//        ]);
    }

    /**
     * @param $method
     * @param $data
     * @param $token
     * @return mixed
     */
    public function bot($method, $data = [], $token = '5180280407:AAFod0icZq1cuRfZr_gd0sqcmjqk4rFqlQU')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot' . $token . '/' . $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        return json_decode($res);

    }
}
