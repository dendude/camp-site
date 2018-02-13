<?php
namespace app\modules\manage\controllers;

use app\helpers\Normalize;
use app\helpers\Statuses;
use app\models\Camps;
use app\models\Orders;
use app\models\search\OrdersSearch;
use CanGelis\PDF\PDF;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

class OrdersController extends Controller
{
    const LIST_NAME = 'Заказы (бронирование)';

    protected function notFound($message = 'Бронь не найдена') {
        Yii::$app->session->setFlash('error', $message);
        $this->redirect(['list'])->send();
        Yii::$app->end();
    }

    public function actionList()
    {
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    public function actionProcess()
    {
        $searchModel = new OrdersSearch();
        $searchModel->search(Yii::$app->request->get());
        
        Yii::setAlias('print', Yii::getAlias('@app/views/print'));
        
        if ($searchModel->proc_type == OrdersSearch::PROC_DOWNLOAD) {
            Yii::$app->response->format = Response::FORMAT_RAW;
    
            $list = $searchModel->getList();
            
            $totalSum = 0;
            foreach ($list AS $item) {
                $totalSum += $item->price_partner;
            }
            
            $content = $this->renderFile(Yii::getAlias('@print/pdf.php'), [
                'totalSum' => number_format($totalSum, 2, '.', ''),
                'model' => $searchModel,
                'list' => $list,
            ]);
        
            $pdf = new \mPDF();
            $pdf->WriteHTML($content);
            $pdf->Output();
            
            Yii::$app->end();
        }
    }

    public function actionAdd() {
        $model = new Orders();

        if (Yii::$app->request->post('Orders')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Бронь успешно добавлена');
                return $this->redirect(['list']);
            }
        }

        return $this->render('add', [
            'model' => $model
        ]);
    }

    public function actionEdit($id) {

        $model = Orders::findOne($id);
        if (!$model) $this->notFound();
        
        if ($model->status == Statuses::STATUS_NEW) {
            $model->updateAttributes(['status' => Statuses::STATUS_SHOWED]);
        }

        if (Yii::$app->request->post('Orders')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Бронь успешно сохранена');
                return $this->redirect(['list']);
            }
        }

        return $this->render('add', [
            'model' => $model
        ]);
    }

    public function actionDelete($id, $confirm = false) {

        $model = Orders::findOne($id);
        if (!$model) $this->notFound();

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
