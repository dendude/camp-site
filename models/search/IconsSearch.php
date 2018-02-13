<?php
namespace app\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\Icons;

class IconsSearch extends Icons {

    public function rules()
    {
        return [
            [['id', 'manager_id', 'created'], 'integer'],
            [['icon_name'], 'string'],
        ];
    }

    public function search($params)
    {
        $query = parent::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                ],
            ],
            'pagination' => [
                'pageSize' => Yii::$app->params['adminItemsPerPage'],
            ]
        ]);

        if (!$this->load($params)) return $dataProvider;

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['manager_id' => $this->manager_id]);
        $query->andFilterWhere(['like', 'icon_name', $this->icon_name]);

        return $dataProvider;
    }
}