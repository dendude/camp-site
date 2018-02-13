<?php
namespace app\modules\manage\controllers;

use app\helpers\Statuses;
use app\models\LocRegions;
use app\models\search\LocRegionsSearch;
use Yii;
use yii\web\Controller;

class LocRegionsController extends Controller
{
    const LIST_NAME = 'Регионы';
    
    protected function notFound($message = 'Регион не найден') {
        Yii::$app->session->setFlash('error', $message);
        $this->redirect(['list'])->send();
        Yii::$app->end();
    }
    
    public function actionList()
    {
        $searchModel = new LocRegionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        
        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    public function actionAdd() {
        $model = new LocRegions();
        
        if (Yii::$app->request->post('LocRegions')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Регион успешно добавлен');
                return $this->redirect(['list']);
            }
        }
        
        return $this->render('add', [
            'model' => $model
        ]);
    }
    
    public function actionEdit($id) {
        
        $model = LocRegions::findOne($id);
        if (!$model) $this->notFound();
        
        if (Yii::$app->request->post('LocRegions')) {
            
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                if (Yii::$app->request->post('ref-page')) {
                    return $this->redirect(Yii::$app->request->post('ref-page'));
                } else {
                    return $this->redirect(['list']);
                }
            }
        }
        
        return $this->render('add', [
            'model' => $model
        ]);
    }
    
    public function actionDelete($id, $confirm = false) {
        
        $model = LocRegions::findOne($id);
        if (!$model) $this->notFound();
        
        if ($confirm) {
            $model->updateAttributes(['status' => Statuses::STATUS_REMOVED]);
            Yii::$app->session->setFlash('success', 'Регион успешно удален');
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
