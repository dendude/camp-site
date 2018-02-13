<?php

namespace app\models\search;

use app\helpers\LanguageParams;
use app\helpers\Statuses;
use app\models\Menu;
use app\models\Orders;
use app\models\Pages;
use app\models\Reviews;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ReviewsSearch extends Reviews {
    
    const SCENARIO_OFFICE = 'office';
    const SCENARIO_PARTNER = 'partner';
    
    public function rules()
    {
        return [
            [['id', 'manager_id', 'base_id', 'user_id', 'stars', 'likes', 'created', 'modified', 'status', 'ordering'], 'integer'],
            [['user_name', 'user_email', 'comment_positive', 'comment_negative', 'comment_manager'], 'string'],
        ];
    }
    
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_OFFICE] = ['id', 'base_id', 'stars', 'likes', 'created', 'status'];
        $scenarios[self::SCENARIO_PARTNER] = ['id', 'base_id', 'stars', 'likes', 'created', 'status', 'user_name', 'user_email'];
        
        return $scenarios;
    }

    public function search($params)
    {
        $query = self::find()->using();
    
        if ($this->scenario == self::SCENARIO_OFFICE) {
            $query->byUser(Yii::$app->user->id);
            $default_order = ['id' => SORT_DESC];
            $pageSize = Yii::$app->params['officeItemsPerPage'];
        } elseif ($this->scenario == self::SCENARIO_PARTNER) {
            $query->byPartner(Yii::$app->user->id);
            $default_order = ['id' => SORT_DESC];
            $pageSize = Yii::$app->params['officeItemsPerPage'];
        } else {
            $default_order = ['ordering' => SORT_ASC, 'id' => SORT_DESC];
            $pageSize = Yii::$app->params['adminItemsPerPage'];
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => $default_order,
            ],
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);

        if (!$this->load($params)) return $dataProvider;

        // adjust the query by adding the filters
        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['manager_id' => $this->manager_id]);
        $query->andFilterWhere(['user_id' => $this->user_id]);
        $query->andFilterWhere(['base_id' => $this->base_id]);
        $query->andFilterWhere(['stars' => $this->stars]);
        $query->andFilterWhere(['likes' => $this->likes]);
        $query->andFilterWhere(['status' => $this->status]);

        $query->andFilterWhere(['like', 'user_name', $this->user_name]);
        $query->andFilterWhere(['like', 'user_email', $this->user_email]);
        $query->andFilterWhere(['like', 'comment_positive', $this->comment_positive]);
        $query->andFilterWhere(['like', 'comment_negative', $this->comment_negative]);
        $query->andFilterWhere(['like', 'comment_manager', $this->comment_manager]);

        return $dataProvider;
    }
}