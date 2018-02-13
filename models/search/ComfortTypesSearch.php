<?php
namespace app\models\search;

use Yii;
use app\models\ComfortTypes;
use yii\data\ActiveDataProvider;

class ComfortTypesSearch extends ComfortTypes {
    
    public function rules()
    {
        return [
            [['id', 'manager_id', 'status', 'ordering'], 'integer'],
            [['title', 'content'], 'string'],
        ];
    }
    
    public function search($params)
    {
        $query = self::find()->using();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['ordering' => SORT_ASC, 'title' => SORT_ASC],
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

        return $dataProvider;
    }
}