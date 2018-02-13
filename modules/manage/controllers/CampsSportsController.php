<?php
namespace app\modules\manage\controllers;

use app\helpers\Statuses;
use app\models\search\TagsSportSearch;
use app\models\TagsSport;
use yii\web\Controller;
use Yii;

class CampsSportsController extends Controller
{
    const LIST_NAME = 'Виды спорта';
    
    protected function notFound($message = 'Вид спорта не найден') {
        Yii::$app->session->setFlash('error', $message);
        $this->redirect(['list'])->send();
        Yii::$app->end();
    }
    
    public function actionList()
    {
        $searchModel = new TagsSportSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        
        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    public function actionAdd() {
        $model = new TagsSport();
        
        if (Yii::$app->request->post('TagsSport')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Объект успешно добавлен');
                return $this->redirect(['list']);
            }
        }
        
        return $this->render('add', [
            'model' => $model
        ]);
    }
    
    public function actionEdit($id) {
        
        $model = TagsSport::findOne($id);
        if (!$model) $this->notFound();
        
        if (Yii::$app->request->post('TagsSport')) {
            
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
    
    //    public function actionShow($id) {
    //        $model = TagsSport::findOne($id);
    //        if (!$model) $this->notFound();
    //
    //        $news_alias = Pages::getAliasById(Pages::PAGE_NEWS_ID);
    //        return $this->redirect(["/{$news_alias}/{$model->alias}"]);
    //    }
    
    public function actionDelete($id, $confirm = false) {
        
        $model = TagsSport::findOne($id);
        if (!$model) $this->notFound();
        
        if ($confirm) {
            $model->updateAttributes(['status' => Statuses::STATUS_REMOVED]);
            Yii::$app->session->setFlash('success', 'Страница успешно удалена');
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
