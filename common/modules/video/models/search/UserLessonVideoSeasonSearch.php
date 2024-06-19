<?php

namespace common\modules\video\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\video\models\UserLessonVideoSeason;

class UserLessonVideoSeasonSearch extends UserLessonVideoSeason
{

    public function rules()
    {
        return [
            [['id', 'parent_video_id', 'user_id', 'season_id', 'copmleted_count', 'lesson_count', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['completed_percent'], 'number'],
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
            $query = UserLessonVideoSeason::find();
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
            'parent_video_id' => $this->parent_video_id,
            'user_id' => $this->user_id,
            'season_id' => $this->season_id,
            'copmleted_count' => $this->copmleted_count,
            'lesson_count' => $this->lesson_count,
            'completed_percent' => $this->completed_percent,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
