<?php

namespace app\models\search;

use app\helpers\LanguageParams;
use app\models\Menu;
use app\models\Pages;
use app\models\Selections;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class SelectionsSearch extends Selections {

    public function rules()
    {
        return [
            [['id', 'manager_id', 'type_id', 'ordering', 'status'], 'integer'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = parent::find()->usage();

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

        if (!$this->load($params)) return $dataProvider;

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['manager_id' => $this->manager_id]);
        $query->andFilterWhere(['type_id' => $this->type_id]);
        $query->andFilterWhere(['status' => $this->status]);
        $query->andFilterWhere(['ordering' => $this->ordering]);

        return $dataProvider;
    }
}