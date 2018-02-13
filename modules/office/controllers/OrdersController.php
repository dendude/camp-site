<?php

namespace app\modules\office\controllers;

use app\helpers\Statuses;
use app\models\Orders;
use app\models\search\OrdersSearch;
use Yii;
use yii\web\Controller;

class OrdersController extends Controller
{
    const LIST_NAME = 'Мои бронирования';
    
    protected function notFound($message = 'Бронь не найдена') {
        Yii::$app->session->setFlash('error', $message);
        return $this->redirect(['list']);
    }
    
    public function actionList()
    {
        $searchModel = new OrdersSearch();
        $searchModel->setScenario(OrdersSearch::SCENARIO_OFFICE);
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        
        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    public function actionShow($id) {
        /** @var $model Orders */
        $model = Orders::find()->bySelf()->using()->andWhere(['id' => $id])->one();
        if (!$model) return $this->notFound();
            
        $this->layout = 'show';
        
        return $this->render('show', [
            'model' => $model
        ]);
    }
    
    public function actionDelete($id, $confirm = false) {
        /** @var $model Orders */
        $model = Orders::find()->bySelf()->using()->andWhere(['id' => $id])->one();
        if (!$model) return $this->notFound();
        
        if (!in_array($model->status, [Statuses::STATUS_NEW, Statuses::STATUS_SHOWED])) {
            Yii::$app->session->setFlash('error', 'Бронь недоступна для удаления');
            return $this->redirect(['list']);
        } elseif ($confirm) {
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
