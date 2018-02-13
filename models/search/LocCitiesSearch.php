<?php

namespace app\models\search;

use app\models\LocCities;
use Yii;
use yii\data\ActiveDataProvider;

class LocCitiesSearch extends LocCities {

    public function rules()
    {
        return [
            [['id', 'country_id', 'region_id', 'created', 'modified', 'status', 'ordering', 'manager_id'], 'integer'],
            [['name', 'alias'], 'string'],
        ];
    }

    public function search($params)
    {
        $query = self::find()->usage();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'country_id' => SORT_ASC,
                    'region_id' => SORT_ASC,
                    'ordering' => SORT_ASC,
                    'name' => SORT_ASC,
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
        $query->andFilterWhere(['country_id' => $this->country_id]);
        $query->andFilterWhere(['region_id' => $this->region_id]);
        $query->andFilterWhere(['ordering' => $this->ordering]);
        $query->andFilterWhere(['status' => $this->status]);
        
        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'alias', $this->alias]);

        return $dataProvider;
    }
}