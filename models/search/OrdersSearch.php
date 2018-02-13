<?php

namespace app\models\search;

use app\helpers\LanguageParams;
use app\models\Menu;
use app\models\Orders;
use app\models\Pages;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class OrdersSearch extends Orders {

    const SCENARIO_OFFICE = 'office';
    const SCENARIO_PARTNER = 'partner';
    
    const DOC_PDF = 'pdf';
    const DOC_WORD = 'word';
    const DOC_EXCEL = 'excel';
    
    const PROC_DOWNLOAD = 'download';
    const PROC_SENDMAIL = 'sendmail';
    
    protected $query;
    
    public $date_from;
    public $date_to;
    
    public $doc_type;
    public $proc_type;
    public $sort;
    
    public function rules()
    {
        return [
            [['id', 'manager_id', 'user_id', 'item_id', 'price_partner', 'price_user', 'price_payed', 'status'], 'integer'],
            [['details', 'camp_id', 'currency'], 'string'],
            
            [['doc_type'], 'in', 'range' => array_keys(self::getDocTypes())],
            [['proc_type'], 'in', 'range' => array_keys(self::getProcTypes())],
            [['date_from', 'date_to'], 'date', 'format' => 'dd.MM.yyyy'],
            ['sort', 'string'],
        ];
    }
    
    public static function getDocTypes() {
        return [
            self::DOC_PDF => 'PDF',
            self::DOC_WORD => 'Word',
            self::DOC_EXCEL => 'Excel',
        ];
    }
    
    public static function getProcTypes() {
        return [
            self::PROC_DOWNLOAD => 'Скачать',
            self::PROC_SENDMAIL => 'Отправить на почту',
        ];
    }
    
    public static function getProcTypeName($proc_type) {
        $list = self::getProcTypes();
        return isset($list[$proc_type]) ? $list[$proc_type] : null;
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_OFFICE] = ['id', 'camp_id', 'item_id', 'price_user', 'price_payed', 'status', 'details', 'currency'];
        $scenarios[self::SCENARIO_PARTNER] = ['id', 'camp_id', 'item_id', 'price_user', 'price_payed', 'status', 'details', 'currency'];
        
        return $scenarios;
    }
    
    /**
     * @return Orders[]
     */
    public function getList() {
    
        $query = $this->query;
        
        if ($this->date_from) {
            $query->andFilterWhere([ '>=', 'created', strtotime($this->date_from . ' 00:00:00') ]);
        }
        if ($this->date_to) {
            $query->andFilterWhere([ '<=', 'created', strtotime($this->date_to . ' 23:59:59') ]);
        }
        
        return $query->all();
    }

    public function search($params)
    {
        $query = self::find()->using();
        
        if ($this->scenario == self::SCENARIO_OFFICE) {
            $query->byUser(Yii::$app->user->id);
            $pageSize = Yii::$app->params['officeItemsPerPage'];
        } elseif ($this->scenario == self::SCENARIO_PARTNER) {
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

        // load the seach form data and validate
        if (!$this->load($params)) return $dataProvider;
        
        if ($this->camp_id) {
            $query->joinWith(['camp', 'camp.about']);
            $query->andFilterWhere(['like', 'camp_camps_about.name_full', $this->camp_id]);
        }
        
        // adjust the query by adding the filters
        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['manager_id' => $this->manager_id]);
        $query->andFilterWhere(['user_id' => $this->user_id]);
        $query->andFilterWhere(['item_id' => $this->item_id]);
        $query->andFilterWhere(['currency' => $this->currency]);
        $query->andFilterWhere(['status' => $this->status]);

        $query->andFilterWhere(['like', 'price_partner', $this->price_partner]);
        $query->andFilterWhere(['like', 'price_user', $this->price_user]);
        $query->andFilterWhere(['like', 'price_payed', $this->price_payed]);
        $query->andFilterWhere(['like', 'details', $this->details]);
        
        $this->query = clone $query;

        return $dataProvider;
    }
}