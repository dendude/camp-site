<?php
/**
 * Created by PhpStorm.
 * User: dendude
 * Date: 09.03.15
 * Time: 21:55
 */

namespace app\controllers;

use app\components\SendOrderNotifications;
use app\helpers\Statuses;
use app\models\BaseItems;
use app\models\Camps;
use app\models\forms\UploadForm;
use app\models\LocCities;
use app\models\LocCountries;
use app\models\LocRegions;
use app\models\Orders;
use app\models\TagsTypes;
use app\models\Users;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\helpers\Html;

use app\helpers\Normalize;
use yii\web\Response;
use yii\web\UploadedFile;

class AjaxController extends Controller {
    public function beforeAction($action)
    {
        if (!Yii::$app->request->isAjax) die;
	    Yii::$app->request->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    
    public function actionAlias() {
        $str = Yii::$app->request->post('str', '');
        echo Normalize::alias($str);
    }
    
    public function actionUpload() {
        $upload_form = new UploadForm();
        if (!Users::isAdmin()) $upload_form->setScenario(UploadForm::SCENARIO_CAMP);
        $upload_form->imageFile = UploadedFile::getInstances($upload_form, 'imageFile');
        
        if ($upload_form->upload(UploadForm::TYPE_CAMP)) {
            echo Json::encode([
                'file_name' => $upload_form->getImageName()
            ]);
        } else {
            echo Json::encode($upload_form->getErrors());
        }
    }
    
    public function actionPrice() {
        $result = [];
        
        switch (Yii::$app->request->post('type')) {
            case 'camp_item_prices':
                $id = Yii::$app->request->post('id', 0);
                $model = BaseItems::findOne($id);
                if ($model) {
                    $result['price_partner'] = $model->partner_price;
                    $result['price_user'] = $model->getCurrentPrice();
                    $result['trans_in_price'] = Statuses::getFull($model->camp->about->trans_in_price, Statuses::TYPE_YESNO);
                }
                break;
        }
    
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
    }

    public function actionCamps() {
        $result = [];

        $q = Yii::$app->request->get('q');
        if (mb_strlen($q, Yii::$app->charset) >= 3) {
            /** @var $camps Camps[] */
            $camps = Camps::find()
                    ->joinWith('about')
                    ->andFilterWhere(['like', "CONCAT(camp_camps_about.name_short,' ',camp_camps_about.name_full,' ',camp_camps_about.name_org)", $q])
                    ->using()->limit(50)->all();
            if ($camps) {
                $result['results'] = [];
                foreach ($camps AS $camp) {
                    $result['results'][] = [
                        'id' => $camp->id,
                        'text' => $camp->about->name_short,
                        'full' => $camp->about->name_full,
                        'org' => $camp->about->name_org,
                        'img' => UploadForm::getSrc($camp->media->photo_main, UploadForm::TYPE_CAMP, '_xs')
                    ];
                }
            }
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
    }
    
    public function actionItemPrice() {
        $item_id = Yii::$app->request->post('item_id');
        $base_item = BaseItems::findOne($item_id);
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['price' => $base_item->partner_price];
    }
    
    public function actionEscort() {
        $result = [];
        
        $q = Yii::$app->request->get('q');
        if (mb_strlen($q, Yii::$app->charset) >= 2) {
            /** @var $items LocCities[] */
            $items = LocCities::find()->usage()->andFilterWhere(['like', 'name', $q])->limit(50)->all();
            if ($items) {
                $result['results'] = [];
                foreach ($items AS $item) {
                    $result['results'][] = [
                        'id' => $item->id,
                        'text' => $item->name,
                        'region' => $item->region->name,
                        'country' => $item->country->name,
                    ];
                }
            }
        }
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
    }
    
    public function actionOrder() {
        $result = [];
        
        if (Yii::$app->request->post('Orders')) {
            
            $order = new Orders();
            $order->load(Yii::$app->request->post());
                                           
            if ($order->validate()) {
                if ($order->save(false)) {
                    $notifications = new SendOrderNotifications($order);
                    $notifications->send();
                    
                    $result['message'] = 'Ваша заявка успешно отправлена!<br/>Наши менеджеры свяжутся с вами в ближайшее время.';
                    
                    if ($order->camp->contract->opt_use_paytravel) {
                        // подключена возможность оплаты на сайте
                        $result['button'] = Html::a('Перейти к оплате', Url::to(['/payment/go', 'order_id' => $order->id]), [
                            'class'  => 'btn btn-success btn-block m-t-10',
                            'target' => '_blank',
                        ]);
                    }
                } else {
                    $result['errors'] = ['system' => 'Ошибка сохранения заявки!<br/>Пожалуйста, попробуйте позже.'];
                }
            } else {
                $result['errors'] = $order->getErrors();
            }
        }
    
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
    }
        
    public function actionOptions() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $result = ['content' => ''];
        
        $id = Yii::$app->request->post('id', 0);
        $selected = Yii::$app->request->post('selected', '');
        $is_full = Yii::$app->request->post('full', false);
        $has_camps = Yii::$app->request->post('has_camps', false);
        
        $items = [];
        
        switch (Yii::$app->request->post('type')) {
            
            case 'countries':
                $first = Yii::$app->request->post('first', '- Выбор страны -');
                $empty = Yii::$app->request->post('empty', '- Страны не найдены -');
                
                if ($has_camps) {
                    $countries = LocCountries::getFilterListWithCamps();
                } else {
                    $countries = LocCountries::getFilterList($is_full, $has_camps);
                }
                
                if ($countries) {
                    $items[''] = $first;
                    foreach ($countries AS $k => $v) $items[$k] = $v;
                } else {
                    $items[''] = $empty;
                }
                break;
            
            case 'regions':
                $first = Yii::$app->request->post('first', '- Выбор региона -');
                $empty = Yii::$app->request->post('empty', '- Регионы не найдены -');
    
                if ($has_camps) {
                    $regions = LocRegions::getFilterListWithCamps($id);
                } else {
                    $regions = LocRegions::getFilterList($id, $is_full, $has_camps);
                }
    
                if ($regions) {
                    $items[''] = $first;
                    foreach ($regions AS $k => $v) $items[$k] = $v;
                } else {
                    $items[''] = $empty;
                }
                break;
    
            case 'types':
                $first = Yii::$app->request->post('first', '- Типы лагерей -');
                $empty = Yii::$app->request->post('empty', '- Типы не найдены -');
                
                $country_id = Yii::$app->request->post('country_id');
                $region_id = Yii::$app->request->post('region_id');
        
                if ($has_camps) {
                    $regions = TagsTypes::getFilterListWithCamps($country_id, $region_id);
                } else {
                    $regions = TagsTypes::getFilterList();
                }
        
                if ($regions) {
                    $items[''] = $first;
                    foreach ($regions AS $k => $v) $items[$k] = $v;
                } else {
                    $items[''] = $empty;
                }
                break;
    
            case 'cities':
                $first = Yii::$app->request->post('first', '- Выбор города -');
                $empty = Yii::$app->request->post('empty', '- Города не найдены -');
        
                $cities = LocCities::getFilterList($id, $is_full);
    
                if ($cities) {
                    $items[''] = $first;
                    foreach ($cities AS $k => $v) $items[$k] = $v;
                } else {
                    $items[''] = $empty;
                }
                break;
    
            case 'camp_items':
                $first = Yii::$app->request->post('first', '- Выбор смены -');
                $empty = Yii::$app->request->post('empty', '- Смены не найдены -');
        
                $models = BaseItems::getFilterListOrder($id);
    
                if ($models) {
                    $items[''] = $first;
                    foreach ($models AS $k => $v) $items[$k] = $v;
                } else {
                    $items[''] = $empty;
                }
                break;
        }
        
        if (count($items)) {
            foreach ($items AS $k => $v) {
                $result['content'] .= Html::tag('option', $v, ['value' => $k, 'selected' => ($selected == $k)]);
            }
        }
        
        return $result;
    }
} 
