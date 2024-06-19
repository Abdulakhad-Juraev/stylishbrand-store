<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use common\models\About;
use yii\data\ActiveDataProvider;

class AboutSearch extends About
{

    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['image', 'phone_number', 'telegram_link', 'email','description'], 'safe'],
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
            $query = About::find()->joinWith('translation');
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
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'telegram_link', $this->telegram_link])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
