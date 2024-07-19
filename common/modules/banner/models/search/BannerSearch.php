<?php

namespace common\modules\banner\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\banner\models\Banner;

class BannerSearch extends Banner
{

    public function rules()
    {
        return [
            [['id', 'count', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at','type'], 'integer'],
            [['image', 'button_url','title'], 'safe'],
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
            $query = Banner::find()->joinWith('translation');
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
            'type' => $this->type,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'button_url', $this->button_url])
            ->andFilterWhere(['like', 'count', $this->count])
            ->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
