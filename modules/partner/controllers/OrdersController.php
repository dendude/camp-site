<?php

namespace app\modules\partner\controllers;

use app\components\SmtpEmail;
use app\helpers\Normalize;
use app\helpers\Statuses;
use app\models\Settings;
use app\models\Users;
use Yii;
use yii\helpers\Html;
use yii\web\Controller;
use app\models\search\OrdersSearch;
use app\models\Orders;

/**
 * Default controller for the `office` module
 */
class OrdersController extends Controller
{
    const LIST_NAME = 'Заявки на путевки';
    
    protected function notFound($message = 'Заявка не найдена') {
        Yii::$app->session->setFlash('error', $message);
        return $this->redirect(['list']);
    }
    
    public function actionList()
    {
        $searchModel = new OrdersSearch();
        $searchModel->setScenario(OrdersSearch::SCENARIO_PARTNER);
        $dataProvider = $searchModel->search(Yii::$app->request->get());
    
        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    public function actionEdit($id) {
        /** @var $model Orders */
        $model = Orders::find()->byPartner(Yii::$app->user->id)->using()->andWhere(['id' => $id])->one();
        if (!$model) return $this->notFound();
        
        $old_status = $model->status;
        
        if (Yii::$app->request->post('Orders')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                $new_status = $model->status;
                
                if ($old_status != $new_status) {
                    // рассылка уведомлений об изменении статуса заявки
                    $settings = Settings::lastSettings();
                    $emails = Normalize::emailsStrToArr($settings->emails_change_order_status);
                    
                    $smtp = new SmtpEmail();
                    foreach ($emails AS $email) {
                        /** @var $u Users */
                        $u = Users::find()->where(['email' => $email])->one();
                        $name = $u ? $u->first_name : 'Менеджер';
                        
                        $smtp->sendEmailByType(SmtpEmail::TYPE_ORDER_STATUS_CHANGED, $email, $name, [
                            '{partner-info}' => implode('<br/>', [
                                $model->partner->getFullName(),
                                $model->partner->email,
                                $model->partner->phone,
                            ]),
                            '{camp-info}' => implode('<br/>', [
                                Html::a($model->camp->about->name_short, $model->camp->getCampUrl(true), ['target' => '_blank']),
                                $model->camp->about->name_full,
                                $model->camp->about->country->name . ' - ' . $model->camp->about->region->name,
                            ]),
                            '{client-info}' => implode('<br/>', $model->getOrderData(Orders::TYPE_DATA_CLIENT)),
                            '{order-info}' => implode('<br/>', $model->getOrderData(Orders::TYPE_DATA_CHILD)),
                            '{status-old}' => Statuses::getName($old_status, Statuses::TYPE_ORDER),
                            '{status-new}' => Statuses::getName($new_status, Statuses::TYPE_ORDER),
                        ]);
                    }
                }
                
                Yii::$app->session->setFlash('success', 'Бронь успешно сохранена');
                return $this->redirect(['list']);
            }
        }
                
        return $this->render('edit', [
            'model' => $model
        ]);
    }
    
    public function actionShow($id) {
        /** @var $model Orders */
        $model = Orders::find()->byPartner(Yii::$app->user->id)->using()->andWhere(['id' => $id])->one();
        if (!$model) return $this->notFound();
        
        $this->layout = 'show';
        
        return $this->render('show', [
            'model' => $model
        ]);
    }
    
    public function actionDelete($id, $confirm = false) {
        /** @var $model Orders */
        $model = Orders::find()->byPartner(Yii::$app->user->id)->using()->andWhere(['id' => $id])->one();
        if (!$model) return $this->notFound();
        
        if ($confirm) {
            $model->updateAttributes(['status' => Statuses::STATUS_REMOVED]);
            Yii::$app->session->setFlash('success', 'Бронь успешно удалена');
            return $this->redirect(['list']);
        }
        
        return $this->render('delete', [
            'model' => $model
        ]);
    }
    
    public function actionTrash($id) {
        $this->actionDelete($id, true);
    }
}
