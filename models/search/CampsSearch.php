<?php

namespace app\models\search;

use app\models\CampsAbout;
use Yii;
use yii\data\ActiveDataProvider;
use app\models\Camps;

class CampsSearch extends Camps {
    
    const SCENARIO_PARTNER = 'partner';
    
    public $about_loc_country;
    public $about_loc_region;
    public $about_loc_city;
    
    public $about_name_short;
    public $about_name_full;
    
    public $contacts_boss;
    public $contacts_worker;
    
    public function rules()
    {
        return [
            [['id', 'manager_id', 'status', 'ordering',
              'about_loc_country', 'about_loc_region', 'about_loc_city'], 'integer'],
            
            [['about_name_full', 'about_name_short',
              'contacts_boss', 'contacts_worker'], 'string'],
        ];
    }
    
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_PARTNER] = [
            'id', 'status',
            'about_loc_country', 'about_loc_region', 'about_loc_city',
            'about_name_full', 'about_name_short',
            'contacts_boss', 'contacts_worker',
        ];
        
        return $scenarios;
    }

    public function search($params)
    {
        $query = self::find()->using();
        $query->joinWith(['about', 'contacts']);
        
        if ($this->scenario == self::SCENARIO_PARTNER) {
            $query->byPartner(Yii::$app->user->id);
            $pageSize = Yii::$app->params['officeItemsPerPage'];
        } else {
            $pageSize = Yii::$app->params['adminItemsPerPage'];
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);
    
        $dataProvider->sort->attributes['about_loc_country'] = [
            'asc' => ['camp_camps_about.loc_country' => SORT_ASC],
            'desc' => ['camp_camps_about.loc_country' => SORT_DESC],
        ];
    
        $dataProvider->sort->attributes['about_loc_region'] = [
            'asc' => ['camp_camps_about.loc_region' => SORT_ASC],
            'desc' => ['camp_camps_about.loc_region' => SORT_DESC],
        ];
    
        $dataProvider->sort->attributes['about_loc_city'] = [
            'asc' => ['camp_camps_about.loc_city' => SORT_ASC],
            'desc' => ['camp_camps_about.loc_city' => SORT_DESC],
        ];
    
        $dataProvider->sort->attributes['about_name_short'] = [
            'asc' => ['camp_camps_about.name_short' => SORT_ASC],
            'desc' => ['camp_camps_about.name_short' => SORT_DESC],
        ];
    
        $dataProvider->sort->attributes['about_name_full'] = [
            'asc' => ['camp_camps_about.name_full' => SORT_ASC],
            'desc' => ['camp_camps_about.name_full' => SORT_DESC],
        ];
    
        $dataProvider->sort->attributes['contacts_boss'] = [
            'asc' => ['camp_camps_contacts.boss_fio' => SORT_ASC],
            'desc' => ['camp_camps_contacts.boss_fio' => SORT_DESC],
        ];
    
        $dataProvider->sort->attributes['contacts_worker'] = [
            'asc' => ['camp_camps_contacts.worker_fio' => SORT_ASC],
            'desc' => ['camp_camps_contacts.worker_fio' => SORT_DESC],
        ];

        if (!$this->load($params)) return $dataProvider;

        $query->andFilterWhere(['camp_camps.id' => $this->id]);
        $query->andFilterWhere(['camp_camps.manager_id' => $this->manager_id]);
        $query->andFilterWhere(['camp_camps.ordering' => $this->ordering]);
        $query->andFilterWhere(['camp_camps.status' => $this->status]);
    
        $query->andFilterWhere(['camp_camps_about.loc_country' => $this->about_loc_country]);
        $query->andFilterWhere(['camp_camps_about.loc_region' => $this->about_loc_region]);
        $query->andFilterWhere(['camp_camps_about.loc_city' => $this->about_loc_city]);

        $query->andFilterWhere(['like', 'CONCAT(camp_camps_about.name_short, camp_camps_about.name_full)', $this->about_name_short]);
        //$query->andFilterWhere(['like', 'camp_camps_about.name_full', $this->about_name_full]);
        
        $query->andFilterWhere(['like',
            'CONCAT(camp_camps_contacts.boss_fio, camp_camps_contacts.boss_phone, camp_camps_contacts.boss_email)',
            $this->contacts_boss
        ]);
    
        $query->andFilterWhere(['like',
            'CONCAT(camp_camps_contacts.worker_fio, camp_camps_contacts.worker_phone, camp_camps_contacts.worker_email)',
            $this->contacts_worker
        ]);

        return $dataProvider;
    }
    
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'about_loc_country' => 'Страна',
            'about_loc_region' => 'Регион',
            'about_loc_city' => 'Ближайший город',

            'about_name_short' => 'Название лагеря',
            'about_name_full' => 'Полное название лагеря',

            'contacts_boss' => 'Контакты директора',
            'contacts_worker' => 'Контакты сотрудника',
        ]);
    }
    
    
}