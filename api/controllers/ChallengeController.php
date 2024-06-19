<?php

namespace api\controllers;


use api\models\Challenge;
use Yii;
use yii\data\ActiveDataProvider;

class ChallengeController extends ApiBaseController
{

    /**
     * @var string[]
     */
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * @var bool
     */
    public $authRequired = true;

    /**
     * @return array
     */
    public function actionIndex()
    {

        if (!Yii::$app->user->identity->hasActiveTariff) {

            return $this->error("Sizda faol obuna mavjud emas!");
        }

        $challenge = Challenge::find()
            ->active()
            ->orderBy(['id' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $challenge,
        ]);

        return $this->success($dataProvider);
    }
}