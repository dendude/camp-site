<?php

namespace app\models\search;

use app\helpers\LanguageParams;
use app\models\Menu;
use app\models\Orders;
use app\models\Pages;
use app\models\Reviews;
use app\models\Users;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class UsersSearch extends Users {

    public function rules()
    {
        return [
            [['id', 'created', 'modified', 'status', 'last_active'], 'integer'],
            [['role', 'first_name', 'last_name', 'sur_name', 'email', 'pass', 'photo', 'act_code'], 'string'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = self::find()->using();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => Yii::$app->params['adminItemsPerPage'],
            ]
        ]);

        // load the seach form data and validate
        if (!$this->load($params)) {
            return $dataProvider;
        }

        // adjust the query by adding the filters
        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['status' => $this->status]);

        $query->andFilterWhere(['like', 'CONCAT(last_name,first_name,sur_name)', $this->first_name]);
        $query->andFilterWhere(['like', 'last_name', $this->last_name]);
        $query->andFilterWhere(['like', 'sur_name', $this->sur_name]);
        $query->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}