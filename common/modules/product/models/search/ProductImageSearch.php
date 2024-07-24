<?php

namespace common\modules\product\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\product\models\ProductImage;

class ProductImageSearch extends ProductImage
{

    public function rules()
    {
        return [
            [['id', 'product_id', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['image','color_id'], 'safe'],
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
            $query = ProductImage::find();
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
            'product_id' => $this->product_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        $query->joinWith('color.translations');
        $query->andFilterWhere(['like', 'product_color_lang.name', $this->color_id]);

        return $dataProvider;
    }
}
