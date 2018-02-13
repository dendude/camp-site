<?php
namespace app\components;

use app\models\BaseItems;
use app\models\BasePeriods;
use app\models\BasePlacements;
use app\models\CampsContract;
use Yii;
use yii\base\Component;
use yii\base\Model;
use yii\widgets\ActiveForm;

class CampValidate extends Component {
    
    // простая валидация
    public static function stepSimple($model, &$result)
    {
        $result = array_merge($result, ActiveForm::validate($model));
    }
    
    // мульти-валидация способов размещения
    public static function stepPlacements(&$result, $camp_id = null)
    {
        $items = Yii::$app->request->post('BasePlacements', []);
        if (is_array($items) && count($items)) {
            $models = [];
            foreach ($items AS $k => $v) {
                if ($camp_id && $m = BasePlacements::find()->byCamp($camp_id)->andWhere(['id' => $v['id']])->one()) {
                    // редактирование
                    $models[$k] = $m;
                } else {
                    // добавление
                    $models[$k] = new BasePlacements();
                }
            }
        } else {
            $models = [new BasePlacements()];
        }
        Model::loadMultiple($models, Yii::$app->request->post());
        $result = array_merge($result, ActiveForm::validateMultiple($models));
        
        return $models;
    }
    
    // мульти-валидация периодов сезонности
    public static function stepPeriods(&$result, $camp_id = null)
    {
        $items = Yii::$app->request->post('BasePeriods', []);
        if (is_array($items) && count($items)) {
            $models = [];
            foreach ($items AS $k => $v) {
                if ($camp_id && $m = BasePeriods::find()->byCamp($camp_id)->andWhere(['id' => $v['id']])->one()) {
                    $models[$k] = $m;
                } else {
                    $models[$k] = new BasePeriods();
                }
            }
        } else {
            $models = [new BasePeriods()];
        }
        Model::loadMultiple($models, Yii::$app->request->post());
        $result = array_merge($result, ActiveForm::validateMultiple($models));
        
        return $models;
    }
    
    // мульти-валидация смен
    public static function stepItems(&$result, $camp_id = null, $scenario)
    {
        $items = Yii::$app->request->post('BaseItems', []);
        $contract = Yii::$app->request->post('CampsContract');
    
        $comission_type = $contract['contract_comission_type'];
        $comission_value = (int)$contract['contract_comission'];
        
        /** @var $models BaseItems[] */
        
        if (is_array($items) && count($items)) {
            $models = [];
            foreach ($items AS $k => $v) {
                if ($camp_id && $m = BaseItems::find()->byCamp($camp_id)->andWhere(['id' => $v['id']])->one()) {
                    // редактирование
                    $models[$k] = $m;
                } else {
                    // добавление
                    $models[$k] = new BaseItems();
                }
            }
        } else {
            $models = [new BaseItems()];
        }
        
        foreach ($models AS $m) $m->setScenario($scenario);
        Model::loadMultiple($models, Yii::$app->request->post());
        
        foreach ($models AS $m) {
            if (empty($m->comission_type)) $m->comission_type = $comission_type;
            if (empty($m->comission_value)) $m->comission_value = $comission_value;
    
            if (empty($m->discount_type)) $m->discount_type = $comission_type;
            if (empty($m->discount_value)) $m->discount_value = 0;
            
            $m->compare_comission = ($m->comission_type == CampsContract::COMISSION_PERCENT)
                                  ? 100 // процент от суммы партнера
                                  : $m->partner_price; // непосредственно сумма комиссии
    
            $m->compare_discount = ($m->discount_type == CampsContract::COMISSION_PERCENT)
                                 ? 100 // процент от суммы партнера
                                 : ($m->comission_type == CampsContract::COMISSION_PERCENT
                                    ? $m->partner_price * $m->comission_value / 100 // не более процента комиссии
                                    : $m->comission_value); // не более суммы комиссии
        }
        
        $result = array_merge($result, ActiveForm::validateMultiple($models));
        
        return $models;
    }
}
