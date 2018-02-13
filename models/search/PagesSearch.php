<?php

namespace app\models\search;

use app\helpers\LanguageParams;
use app\models\Menu;
use app\models\Pages;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class PagesSearch extends Pages {

    public function rules()
    {
        return [
            [['id', 'manager_id', 'created', 'modified', 'is_sitemap'], 'integer'],
            [['alias', 'title', 'meta_t', 'meta_k', 'meta_d'], 'string'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Pages::find()->using();

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

        if (!$this->load($params)) return $dataProvider;

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['manager_id' => $this->manager_id]);
        $query->andFilterWhere(['is_sitemap' => $this->is_sitemap]);

        $query->andFilterWhere(['like', 'alias', $this->alias]);
        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}