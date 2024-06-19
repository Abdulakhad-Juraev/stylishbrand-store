<?php

namespace common\modules\video\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\video\models\Homework;

class HomeworkSearch extends Homework
{

    public function rules()
    {
        return [
            [['id', 'video_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['title', 'file_url'], 'safe'],
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
            $query = Homework::find();
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
            'video_id' => $this->video_id,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'file_url', $this->file_url]);

        return $dataProvider;
    }
}
