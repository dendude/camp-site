<?php

namespace app\models\search;

use app\helpers\LanguageParams;
use app\models\Faq;
use app\models\Menu;
use app\models\Orders;
use app\models\Pages;
use app\models\Reviews;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class FaqSearch extends Faq {

    public function rules()
    {
        return [
            [['id', 'manager_id', 'created', 'modified', 'status', 'ordering'], 'integer'],
            [['title', 'question', 'answer', 'meta_t', 'meta_d', 'meta_k'], 'string'],
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
        $query->andFilterWhere(['ordering' => $this->ordering]);

        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'question', $this->question]);
        $query->andFilterWhere(['like', 'answer', $this->answer]);
        $query->andFilterWhere(['like', 'meta_t', $this->meta_t]);
        $query->andFilterWhere(['like', 'meta_d', $this->meta_d]);
        $query->andFilterWhere(['like', 'meta_k', $this->meta_k]);

        return $dataProvider;
    }
}