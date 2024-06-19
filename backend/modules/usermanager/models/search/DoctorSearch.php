<?php


namespace backend\modules\usermanager\models\search;


use backend\modules\usermanager\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class DoctorSearch extends UserSearch
{

    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at', 'category_id'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'full_name', 'user_type'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($query = null, $defaultPageSize = 20, $params = null)
    {

        if ($params == null) {

            $params = Yii::$app->request->queryParams;
        }
        if ($query == null) {

            $query = User::find()->andWhere(['user_type' => \common\models\User::USER_DOCTOR])
                ->orWhere(['user_type' => User::USER_LABARANT]);
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
            'status' => $this->status,
            'category_id' => $this->category_id,
            'user_type' => $this->user_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ]);
        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'full_name', $this->full_name]);

        return $dataProvider;
    }

}