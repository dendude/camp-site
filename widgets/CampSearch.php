<?php

namespace app\widgets;

use app\models\Camps;
use app\models\ComfortTypes;
use app\models\LocCities;
use app\models\LocCountries;
use app\models\LocRegions;
use app\models\TagsTypes;
use Yii;
use app\models\forms\SearchForm;
use yii\base\Widget;

class CampSearch extends Widget {
    
    const TYPE_COLUMN = 'column';
    
    public $type;
        
	public function run() {
        $search = new SearchForm();

        $alias = Yii::$app->request->get('alias');

        switch (Yii::$app->request->get('type')) {
            case Camps::TYPE_COUNTRY :
                $model = LocCountries::find()->where(['alias' => $alias])->one();
                if ($model) $search->country_id = $model->id;
                break;

            case Camps::TYPE_REGION :
                $model = LocRegions::find()->where(['alias' => $alias])->one();
                if ($model) {
                    $search->country_id = $model->country->id;
                    $search->region_id = $model->id;
                }
                break;

            case Camps::TYPE_CITY :
                $model = LocCities::find()->where(['alias' => $alias])->one();
                if ($model) $search->city_from = $model->id;
                break;

            case Camps::TYPE_TRANSFER :
                $model = LocCities::find()->where(['alias' => $alias])->one();
                if ($model) $search->city_from = $model->id;
                break;

            case Camps::TYPE_TYPE :
                $model = TagsTypes::find()->where(['alias' => $alias])->one();
                if ($model) $search->type = $model->id;
                break;
    
            case Camps::TYPE_SERVICE :
                $model = ComfortTypes::find()->where(['alias' => $alias])->one();
                if ($model) $search->service = $model->id;
                break;

            case Camps::TYPE_YEARS :
                $search->ages = $alias;
                break;

            default:
    
                $search_params = ['SearchForm' => Yii::$app->request->get()];
                $search->load($search_params);
                
                if ($alias) {
                    // для поиска по шаблону /camps/country--region--type
                    
                    $aliases = explode('--', $alias);
                    
                    if (count($aliases) == 1) {
                        // передается страна, регион или тип
                        $cur_alias = array_shift($aliases);
                            
                        $model = LocCountries::find()->where(['alias' => $cur_alias])->one();
                        if ($model) {
                            $search->country_id = $model->id;
    
                            Yii::$app->response->redirect(['/camps/country/' . $alias], 301)->send();
                            Yii::$app->end();
                            
                            break;
                        }
    
                        $model = LocRegions::find()->where(['alias' => $cur_alias])->one();
                        if ($model) {
                            $search->region_id = $model->id;
    
                            Yii::$app->response->redirect(['/camps/region/' . $alias], 301)->send();
                            Yii::$app->end();
                            
                            break;
                        }
    
                        $model = TagsTypes::find()->where(['alias' => $cur_alias])->one();
                        if ($model) {
                            $search->type = $model->id;
                            
                            Yii::$app->response->redirect(['/camps/type/' . $alias], 301)->send();
                            Yii::$app->end();
                            
                            break;
                        }
                        
                    } elseif (count($aliases) == 2) {
                        // передается страна и тип или страна и регион
                        list($alias_country, $alias_type) = $aliases;
    
                        $model = LocCountries::find()->where(['alias' => $alias_country])->one();
                        // указываем 0 для пустого результата поиска
                        $search->country_id = ($model ? $model->id : 0);
    
                        $model = TagsTypes::find()->where(['alias' => $alias_type])->one();
                        if ($model) {$search->type = $model->id; break;}
                                                
                        $model = LocRegions::find()->where(['alias' => $alias_type])->one();
                        // указываем 0 для пустого результата поиска
                        $search->region_id = ($model ? $model->id : 0);
                        
                    } elseif (count($aliases) == 3) {
                        // передается страна и тип
                        list($alias_country, $alias_region, $alias_type) = $aliases;
    
                        $model = LocCountries::find()->where(['alias' => $alias_country])->one();
                        // указываем 0 для пустого результата поиска
                        $search->country_id = ($model ? $model->id : 0);
    
                        $model = LocRegions::find()->where(['alias' => $alias_region])->one();
                        // указываем 0 для пустого результата поиска
                        $search->region_id = ($model ? $model->id : 0);
    
                        $model = TagsTypes::find()->where(['alias' => $alias_type])->one();
                        // указываем 0 для пустого результата поиска
                        $search->type = ($model ? $model->id : 0);
                    } else {
                        // не найдено
                        $search->country_id = 0;
                    }
                }
        }
	    
		return $this->render('CampSearch', [
		    'model' => $search,
            'type' => $this->type
        ]);
	}
}