<?php

namespace app\models\search;

use app\models\EmailMass;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class EmailMassSearch extends EmailMass  {

    public function rules()
    {
        return [
            [['id', 'manager_id', 'created', 'modified', 'status', 'count_total', 'count_sent'], 'integer'],
            [['title', 'comment'], 'string'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = self::find()->usage();

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
        $query->andFilterWhere(['manager_id' => $this->manager_id]);
        $query->andFilterWhere(['count_total' => $this->count_total]);
        $query->andFilterWhere(['count_sent' => $this->count_sent]);
        $query->andFilterWhere(['status' => $this->status]);
        
        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}