<?php

namespace common\modules\video\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\video\models\Video;

class VideoSearch extends Video
{

    public function rules()
    {
        return [
            [['id', 'sort_order', 'category_id', 'serial_type_id', 'parent_id', 'season_id',
                'price_type_id', 'stream_status_id', 'stream_percentage', 'media_size', 'in_process',
                'media_duration', 'has_org_src', 'has_streamed_src', 'queue_id', 'published_at', 'duration_number',
                'status', 'created_by', 'updated_by', 'created_at', 'updated_at',
                'price', 'price_for_subscribers', 'media_type_id', 'is_free'], 'integer'],
            [['slug', 'image', 'org_src', 'stream_src', 'representations', 'stream_status_comment', 'duration_text'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($query = null, $defaultPageSize = 20, $params = null)
    {

        if ($params === null) {
            $params = Yii::$app->request->queryParams;
        }
        if ($query == null) {
            $query = Video::find();
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
            'sort_order' => $this->sort_order,
            'category_id' => $this->category_id,
            'serial_type_id' => $this->serial_type_id,
            'parent_id' => $this->parent_id,
            'season_id' => $this->season_id,
            'price_type_id' => $this->price_type_id,
            'stream_status_id' => $this->stream_status_id,
            'stream_percentage' => $this->stream_percentage,
            'media_size' => $this->media_size,
            'media_duration' => $this->media_duration,
            'has_org_src' => $this->has_org_src,
            'has_streamed_src' => $this->has_streamed_src,
            'queue_id' => $this->queue_id,
            'published_at' => $this->published_at,
            'duration_number' => $this->duration_number,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'price_for_subscribers' => $this->price_for_subscribers,
            'price' => $this->price,
            'media_type_id' => $this->media_type_id,
            'in_process' => $this->in_process,
            'is_free' => $this->is_free,
        ]);

        $query->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'org_src', $this->org_src])
            ->andFilterWhere(['like', 'stream_src', $this->stream_src])
            ->andFilterWhere(['like', 'representations', $this->representations])
            ->andFilterWhere(['like', 'stream_status_comment', $this->stream_status_comment])
            ->andFilterWhere(['like', 'duration_text', $this->duration_text]);

        return $dataProvider;
    }
}
