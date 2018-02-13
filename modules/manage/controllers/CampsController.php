<?php
namespace app\modules\manage\controllers;

use app\components\CampValidate;
use app\helpers\Normalize;
use app\helpers\Statuses;
use app\models\BaseItems;
use app\models\BasePeriods;
use app\models\BasePlacements;
use app\models\Camps;
use app\models\CampsAbout;
use app\models\CampsClient;
use app\models\CampsContacts;
use app\models\CampsContract;
use app\models\CampsMedia;
use app\models\CampsPlacement;
use app\models\Changes;
use app\models\search\CampsSearch;
use Yii;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

class CampsController extends Controller
{
    const LIST_NAME = 'Список лагерей';
    const CAMP_DATA_NAME = 'admin-camp-data';
    const CAMP_SESSION_ID = 'admin-camp-id';
    
    protected function notFound($message = 'Лагерь не найден') {
        Yii::$app->session->setFlash('error', $message);
        return $this->redirect(['list']);
    }
    
    public function actionShow($id) {
        $model = Camps::findOne($id);
        if (!$model) return $this->notFound();
        
        return $this->redirect($model->getCampUrl());
    }
    
    public function actionList()
    {
        $searchModel = new CampsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        
        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    public function actionChanged()
    {
        $query = Changes::find()->waiting();
    
        $countQuery = clone $query;
    
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => 10
        ]);
        $models = $query->offset($pages->offset)->limit($pages->limit)->ordering()->all();
    
        return $this->render('changed', [
            'models' => $models,
            'pages' => $pages,
        ]);
    }
    
    public function actionChangedProcess($id) {
        $model = Changes::findOne($id);
        if (!$model) return $this->notFound();
    
        $model->status = Statuses::STATUS_ACTIVE;
        $model->save();
        
        return $this->redirect(['changed']);
    }
    
    public function actionAdd()
    {
        $model = new Camps();
        
        $base_placement = new BasePlacements();
        $base_period = new BasePeriods();
        
        $base_item = new BaseItems();
        $base_item->setScenario(BaseItems::SCENARIO_ADMIN);
        
        $camp_about = new CampsAbout();
        $camp_about->setScenario(CampsAbout::SCENARIO_ADMIN);
        
        $camp_placement = new CampsPlacement();
        
        $camp_media = new CampsMedia();
        
        $camp_client = new CampsClient();
        
        $camp_contacts = new CampsContacts();
        $camp_contacts->setScenario(CampsContacts::SCENARIO_ADMIN);
        
        $camp_contract = new CampsContract();
        $camp_contract->setScenario(CampsContract::SCENARIO_ADMIN);
                
        $step = Yii::$app->request->post('step');
        
        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {
            // все формы
            $camp_data = Yii::$app->request->post();
            
            $model->load($camp_data);
            $camp_about->load($camp_data);
            $camp_placement->load($camp_data);
            $camp_media->load($camp_data);
            $camp_client->load($camp_data);
            $camp_contacts->load($camp_data);
            $camp_contract->load($camp_data);
            
            $result = [];
            switch ($step) {
                case 1:
                    CampValidate::stepSimple($camp_about, $result);
                    break;
                    
                case 2:
                    CampValidate::stepSimple($camp_placement, $result);
                    if (!$camp_placement->is_without_places) {
                        $places_models = CampValidate::stepPlacements($result);
                    }
                    break;
                    
                case 3:
                    CampValidate::stepSimple($camp_media, $result);
                    break;
                    
                case 4:
                    CampValidate::stepSimple($camp_client, $result);
                    break;
                    
                case 5:
                    CampValidate::stepSimple($camp_contacts, $result);
                    break;
                    
                case 6:
                    CampValidate::stepSimple($camp_contract, $result);
                    if ($camp_contract->contract_period_type == CampsContract::PERIOD_ITEMS) {
                        // мульти-валидация периодов сезонности
                        $periods_models = CampValidate::stepPeriods($result);
                    }
                    break;
                
                case 7:
                    $items_models = CampValidate::stepItems($result, null, BaseItems::SCENARIO_ADMIN);
                    break;
                
                default:
                    /** step1 */
                    CampValidate::stepSimple($camp_about, $result);
                    if ($result) {$result['step'] = 1; break;}
                                        
                    /** step2 */
                    CampValidate::stepSimple($camp_placement, $result);
                    if (!$camp_placement->is_without_places) {
                        $places_models = CampValidate::stepPlacements($result);
                    }
                    if ($result) {$result['step'] = 2; break;}
                    
                    /** step3 */
                    CampValidate::stepSimple($camp_media, $result);
                    if ($result) {$result['step'] = 3; break;}
    
                    /** step4 */
                    CampValidate::stepSimple($camp_client, $result);
                    if ($result) {$result['step'] = 4; break;}
    
                    /** step5 */
                    CampValidate::stepSimple($camp_contacts, $result);
                    if ($result) {$result['step'] = 5; break;}
    
                    /** step6 */
                    CampValidate::stepSimple($camp_contract, $result);
                    if ($camp_contract->contract_period_type == CampsContract::PERIOD_ITEMS) {
                        // мульти-валидация периодов сезонности
                        $periods_models = CampValidate::stepPeriods($result);
                    }
                    if ($result) {$result['step'] = 6; break;}
    
                    /** step7 */
                    // мульти-валидация смен
                    $items_models = CampValidate::stepItems($result, null, BaseItems::SCENARIO_ADMIN);
                    if ($result) {$result['step'] = 7; break;}
    
                    /** step8 */
                    CampValidate::stepSimple($model, $result);
                    if ($result) {$result['step'] = 8; break;}
            }
            
            if (count($result)) {
                // есть ошибки валидации
                if (!isset($result['step'])) {
                    $result['step'] = Yii::$app->request->post('step', 8);
                }
            } elseif (Yii::$app->request->post('step') == 8) {
                // последний шаг без ошибок
                if ($model->save()) {
                    
                    /** сохраняем зависимые модели */
                    $camp_about->camp_id = $model->id;
                    $camp_about->save();
                    
                    $camp_placement->camp_id = $model->id;
                    $camp_placement->save();
                    
                    $camp_media->camp_id = $model->id;
                    $camp_media->save();
                    
                    $camp_client->camp_id = $model->id;
                    $camp_client->save();
                    
                    $camp_contacts->camp_id = $model->id;
                    $camp_contacts->save();
                    
                    $camp_contract->camp_id = $model->id;
                    $camp_contract->save();
    
                    // сохраняем алиас для лагеря
                    $model->updateAttributes(['alias' => Normalize::alias($camp_about->name_short)]);
                    
                    /**
                     * сохраняем варианты размещений
                     * @var $places_models BasePlacements[]
                     */
                    if (!empty($places_models)) {
                        foreach ($places_models AS $m) {
                            $m->camp_id = $model->id;
                            $m->save();
                        }
                    }
                    
                    /**
                     * сохраняем сезонность
                     * @var $periods_models BasePeriods[]
                     */
                    if (!empty($periods_models)) {
                        foreach ($periods_models AS $m) {
                            $m->partner_id = $model->partner_id;
                            $m->camp_id = $model->id;
                            $m->save();
                        }
                    }
                    
                    /**
                     * сохраняем смены
                     * @var $items_models BaseItems[]
                     */
                    if (!empty($items_models)) {
                        foreach ($items_models AS $m) {
                            $m->partner_id = $model->partner_id;
                            $m->camp_id = $model->id;
                            $m->save();
                        }
                    }
                    
                    // редирект с сообщением
                    $result['redirect'] = Url::to(['list']);
                    Yii::$app->session->setFlash('success', 'Лагерь успешно добавлен');
                } else {
                    $result = $model->getErrors();
                }
            }
            
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $result;
        }
        
        return $this->render('add', [
            'model' => $model,
            'base_item' => $base_item,
            'base_period' => $base_period,
            'base_placement' => $base_placement,
            
            'camp_contract'  => $camp_contract,
            'camp_contacts'  => $camp_contacts,
            'camp_media'     => $camp_media,
            'camp_placement' => $camp_placement,
            'camp_about'     => $camp_about,
            'camp_client'    => $camp_client,
        ]);
    }
    
    public function actionEdit($id)
    {
        $model = Camps::findOne($id);
        if (!$model) return $this->notFound();
        
        $base_placement = new BasePlacements();
        $base_placements = BasePlacements::find()->byCamp($model->id)->using()->ordering()->all();
        
        $base_period = new BasePeriods();
        $base_periods = BasePeriods::find()->byCamp($model->id)->using()->ordering()->all();
        
        $base_item = new BaseItems();
        $base_item->setScenario(BaseItems::SCENARIO_ADMIN);
        $base_items = BaseItems::find()->byCamp($model->id)->using()->ordering()->all();
        
        $camp_about = $model->about;
        $camp_about->setScenario(CampsAbout::SCENARIO_ADMIN);
        
        $camp_placement = $model->placement;
        
        $camp_media = $model->media;
        
        $camp_client = $model->client;
    
        $camp_contacts = $model->contacts;
        $camp_contacts->setScenario(CampsContacts::SCENARIO_ADMIN);
        
        $camp_contract = $model->contract;
        $camp_contract->setScenario(CampsContract::SCENARIO_ADMIN);
        
        $step = Yii::$app->request->post('step');
        
        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {
            // все формы
            $camp_data = Yii::$app->request->post();
            
            $model->load($camp_data);
            $camp_about->load($camp_data);
            $camp_placement->load($camp_data);
            $camp_media->load($camp_data);
            $camp_client->load($camp_data);
            $camp_contacts->load($camp_data);
            $camp_contract->load($camp_data);
            
            $result = [];
            switch ($step) {
                case 1:
                    CampValidate::stepSimple($camp_about, $result);
                    break;
                
                case 2:
                    CampValidate::stepSimple($camp_placement, $result);
                    if (!$camp_placement->is_without_places) {
                        $places_models = CampValidate::stepPlacements($result, $model->id);
                    }
                    break;
                
                case 3:
                    CampValidate::stepSimple($camp_media, $result);
                    break;
                
                case 4:
                    CampValidate::stepSimple($camp_client, $result);
                    break;
                
                case 5:
                    CampValidate::stepSimple($camp_contacts, $result);
                    break;
                
                case 6:
                    CampValidate::stepSimple($camp_contract, $result);
                    if ($camp_contract->contract_period_type == CampsContract::PERIOD_ITEMS) {
                        // мульти-валидация периодов сезонности
                        $periods_models = CampValidate::stepPeriods($result, $model->id);
                    }
                    break;
                
                case 7:
                    $items_models = CampValidate::stepItems($result, $model->id, BaseItems::SCENARIO_ADMIN);
                    break;
                
                default:
                    /** step1 */
                    CampValidate::stepSimple($camp_about, $result);
                    if ($result) {$result['step'] = 1; break;}
                    
                    /** step2 */
                    CampValidate::stepSimple($camp_placement, $result);
                    if (!$camp_placement->is_without_places) {
                        $places_models = CampValidate::stepPlacements($result, $model->id);
                    }
                    if ($result) {$result['step'] = 2; break;}
                    
                    /** step3 */
                    CampValidate::stepSimple($camp_media, $result);
                    if ($result) {$result['step'] = 3; break;}
                    
                    /** step4 */
                    CampValidate::stepSimple($camp_client, $result);
                    if ($result) {$result['step'] = 4; break;}
                    
                    /** step5 */
                    CampValidate::stepSimple($camp_contacts, $result);
                    if ($result) {$result['step'] = 5; break;}
                    
                    /** step6 */
                    CampValidate::stepSimple($camp_contract, $result);
                    if ($camp_contract->contract_period_type == CampsContract::PERIOD_ITEMS) {
                        // мульти-валидация периодов сезонности
                        $periods_models = CampValidate::stepPeriods($result, $model->id);
                    }
                    if ($result) {$result['step'] = 6; break;}
                    
                    /** step7 */
                    // мульти-валидация смен
                    $items_models = CampValidate::stepItems($result, $model->id, BaseItems::SCENARIO_ADMIN);
                    if ($result) {$result['step'] = 7; break;}
                    
                    /** step8 */
                    CampValidate::stepSimple($model, $result);
                    if ($result) {$result['step'] = 8; break;}
            }
            
            if (count($result)) {
                // есть ошибки валидации
                if (!isset($result['step'])) {
                    $result['step'] = Yii::$app->request->post('step', 8);
                }
            } elseif (Yii::$app->request->post('step') == 8) {
                // последний шаг без ошибок
                if ($model->save()) {
                    
                    /** сохраняем зависимые модели */
                    $camp_about->camp_id = $model->id;
                    $camp_about->save();
                    
                    $camp_placement->camp_id = $model->id;
                    $camp_placement->save();
                    
                    $camp_media->camp_id = $model->id;
                    $camp_media->save();
                    
                    $camp_client->camp_id = $model->id;
                    $camp_client->save();
                    
                    $camp_contacts->camp_id = $model->id;
                    $camp_contacts->save();
                    
                    $camp_contract->camp_id = $model->id;
                    $camp_contract->save();
                    
                    /**
                     * сохраняем варианты размещений
                     * @var $places_models BasePlacements[]
                     */
                    $ids = [0];
                    if (!empty($places_models)) {
                        foreach ($places_models AS $m) {
                            $m->camp_id = $model->id;
                            $m->save();
                            // массив для учета при удалении
                            $ids[] = $m->id;
                        }
                    }
                    BasePlacements::updateAll(
                        ['status' => Statuses::STATUS_REMOVED],
                        ['and', ['camp_id' => $model->id], ['not in', 'id', $ids]]
                    );
                    
                    /**
                     * сохраняем сезонность
                     * @var $periods_models BasePeriods[]
                     */
                    $ids = [0];
                    if (!empty($periods_models)) {
                        foreach ($periods_models AS $m) {
                            $m->partner_id = $model->partner_id;
                            $m->camp_id = $model->id;
                            $m->save();
                            // массив для учета при удалении
                            $ids[] = $m->id;
                        }
                    }
                    BasePeriods::updateAll(
                        ['status' => Statuses::STATUS_REMOVED],
                        ['and', ['camp_id' => $model->id], ['not in', 'id', $ids]]
                    );
                    
                    /**
                     * сохраняем смены
                     * @var $items_models BaseItems[]
                     */
                    $ids = [0];
                    if (!empty($items_models)) {
                        foreach ($items_models AS $m) {
                            $m->partner_id = $model->partner_id;
                            $m->camp_id = $model->id;
                            $m->save();
                            // массив для учета при удалении
                            $ids[] = $m->id;
                        }
                    }
                    BaseItems::updateAll(
                        ['status' => Statuses::STATUS_REMOVED],
                        ['and', ['camp_id' => $model->id], ['not in', 'id', $ids]]
                    );
                    
                    // редирект с сообщением
                    $result['redirect'] = Url::to(['list']);
                    Yii::$app->session->setFlash('success', 'Лагерь успешно сохранен');
                } else {
                    $result = $model->getErrors();
                }
            }
            
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $result;
        }
        
        return $this->render('add', [
            'model' => $model,
            
            'base_item' => $base_item,
            'base_items' => $base_items,
            
            'base_period' => $base_period,
            'base_periods' => $base_periods,
            
            'base_placement' => $base_placement,
            'base_placements' => $base_placements,
            
            'camp_contract'  => $camp_contract,
            'camp_contacts'  => $camp_contacts,
            'camp_media'     => $camp_media,
            'camp_placement' => $camp_placement,
            'camp_about'     => $camp_about,
            'camp_client'    => $camp_client,
        ]);
    }
    
    public function actionDelete($id, $confirm = false) {
        
        $model = Camps::findOne($id);
        if (!$model) return $this->notFound();
        
        if ($confirm) {
            $model->setDeleteStatus();
            
            Yii::$app->session->setFlash('success', 'Лагерь успешно удален');
            if (Yii::$app->request->get('new')) {
                return $this->redirect(['camps-new/list']);
            } else {
                return $this->redirect(['list']);
            }
        }
        
        return $this->render('delete', [
            'model' => $model
        ]);
    }
    
    public function actionTrash($id) {
        $this->actionDelete($id, true);
    }
}
