<?php

namespace app\models\search;

use app\helpers\LanguageParams;
use app\helpers\Statuses;
use app\models\Menu;
use app\models\Orders;
use app\models\Pages;
use app\models\Reviews;
use app\models\ReviewsItems;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ReviewsItemsSearch extends ReviewsItems {
    
    public function rules()
    {
        return [
            [['id', 'manager_id', 'status', 'ordering'], 'integer'],
            [['title', 'about'], 'string'],
        ];
    }
    
    public function search($params)
    {
        $query = self::find()->using();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['ordering' => SORT_ASC, 'id' => SORT_DESC],
            ],
            'pagination' => [
                'pageSize' => Yii::$app->params['adminItemsPerPage'],
            ]
        ]);

        if (!$this->load($params)) return $dataProvider;

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['manager_id' => $this->manager_id]);
        $query->andFilterWhere(['ordering' => $this->ordering]);
        $query->andFilterWhere(['status' => $this->status]);

        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'about', $this->about]);

        return $dataProvider;
    }
}