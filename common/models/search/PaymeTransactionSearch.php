<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PaymeTransaction;

class PaymeTransactionSearch extends PaymeTransaction
{

    public function rules()
    {
        return [
            [['id', 'paycom_time_datetime', 'create_time', 'perform_time', 'cancel_time', 'amount', 'state', 'reason', 'order_id', 'time'], 'integer'],
            [['paycom_transaction_id', 'paycom_time', 'receivers'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($query=null, $defaultPageSize = 20, $params=null)
    {

        if($params === null){
            $params = Yii::$app->request->queryParams;
        }
        if($query == null){
            $query = PaymeTransaction::find();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => $defaultPageSize,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'paycom_time_datetime' => $this->paycom_time_datetime,
            'create_time' => $this->create_time,
            'perform_time' => $this->perform_time,
            'cancel_time' => $this->cancel_time,
            'amount' => $this->amount,
            'state' => $this->state,
            'reason' => $this->reason,
            'order_id' => $this->order_id,
            'time' => $this->time,
        ]);

        $query->andFilterWhere(['like', 'paycom_transaction_id', $this->paycom_transaction_id])
            ->andFilterWhere(['like', 'paycom_time', $this->paycom_time])
            ->andFilterWhere(['like', 'receivers', $this->receivers]);

        return $dataProvider;
    }
}
