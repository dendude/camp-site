<?php

namespace app\models\search;

use app\helpers\LanguageParams;
use app\models\Bonuses;
use app\models\Menu;
use app\models\News;
use app\models\Pages;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class BonusesSearch extends Bonuses {

    public function rules()
    {
        return [
            [['id', 'manager_id', 'created', 'modified', 'status', 'ordering', 'bonuses'], 'integer'],
            [['sys_name', 'site_name'], 'string'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'ordering' => SORT_ASC,
                ],
            ],
            'pagination' => [
                'pageSize' => Yii::$app->params['adminItemsPerPage'],
            ]
        ]);

        // load the seach form data and validate
        if (!$this->load($params)) return $dataProvider;

        // adjust the query by adding the filters
        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['manager_id' => $this->manager_id]);
        $query->andFilterWhere(['bonuses' => $this->bonuses]);
        $query->andFilterWhere(['ordering' => $this->ordering]);
        $query->andFilterWhere(['status' => $this->status]);
        
        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'alias', $this->alias]);

        return $dataProvider;
    }
}