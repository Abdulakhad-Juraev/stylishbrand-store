<?php

namespace common\modules\uzum\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\uzum\models\UzumTransaction;

class UzumTransactionSearch extends UzumTransaction
{

    public function rules()
    {
        return [
            [['id', 'order_id', 'amount', 'serviceId', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['timestamp', 'transId', 'status', 'transTime', 'confirmTime', 'reverseTime'], 'safe'],
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
            $query = UzumTransaction::find();
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
            'order_id' => $this->order_id,
            'amount' => $this->amount,
            'serviceId' => $this->serviceId,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'timestamp', $this->timestamp])
            ->andFilterWhere(['like', 'transId', $this->transId])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'transTime', $this->transTime])
            ->andFilterWhere(['like', 'confirmTime', $this->confirmTime])
            ->andFilterWhere(['like', 'reverseTime', $this->reverseTime]);

        return $dataProvider;
    }
}
