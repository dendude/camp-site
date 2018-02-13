<?php

namespace app\models\search;

use app\helpers\LanguageParams;
use app\models\Menu;
use app\models\News;
use app\models\Pages;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class NewsSearch extends News {

    public function rules()
    {
        return [
            [['id', 'manager_id', 'created', 'modified', 'status', 'views'], 'integer'],
            [['photo', 'title', 'alias', 'meta_d', 'meta_k', 'meta_t'], 'string'],
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
                    'ordering' => SORT_ASC,
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
        $query->andFilterWhere(['status' => $this->status]);
        $query->andFilterWhere(['views' => $this->views]);
        
        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'alias', $this->alias]);

        return $dataProvider;
    }
}