<?php
namespace app\controllers;

use app\helpers\Statuses;
use app\models\Orders;
use Yii;
use yii\web\Controller;
use app\components\PayTravel;

class PaymentController extends Controller
{
    public function actionGo() {
        $id = Yii::$app->request->get('order_id');
        $order = Orders::find()->where(['id' => $id, 'status' => [Statuses::STATUS_DISABLED, Statuses::STATUS_ACTIVE]])->one();
        
        if ($order) {
            $pay = new PayTravel($id);
            $pay_order = $pay->ordersForAgency();
    
            if (isset($pay_order['code'])) {
                return Yii::$app->response->redirect('//platform.pay.travel/payment/choice?' . http_build_query($pay->getParams()));
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка оплаты, пожалуйста напишите администратору!');
                Yii::$app->session->setFlash('order_id', $id);
            }
        } else {
            Yii::$app->session->setFlash('error', 'Заказ не найден или уже был оплачен!');
            Yii::$app->session->setFlash('order_id', $id);
        }
    
        return Yii::$app->response->redirect(['/site/order', 'id' => $id]);
    }
    
    public function actionCreated() {
        echo __FUNCTION__;
    }
    
    public function actionApproved() {
        echo __FUNCTION__;
    }
    
    public function actionDeclined() {
        echo __FUNCTION__;
    }
    
    public function actionError() {
        echo __FUNCTION__;
    }
    
    public function actionUnknown() {
        echo __FUNCTION__;
    }
}
