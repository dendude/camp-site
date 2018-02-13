<?php
namespace app\modules\partner\controllers;

use app\models\Camps;
use app\models\search\CampsSearch;
use Yii;
use yii\web\Controller;

/**
 * Default controller for the `office` module
 */
class CampsController extends Controller
{
    const LIST_NAME = 'Детские лагеря';
        
    protected function notFound($message = 'Лагерь не найден') {
        Yii::$app->session->setFlash('error', $message);
        $this->redirect(['list'])->send();
        Yii::$app->end();
    }
    
    public function actionShow($id) {
        /** @var $model Camps */
        $model = Camps::find()->byPartner(Yii::$app->user->id)->andWhere(['id' => $id])->one();
        if (!$model) $this->notFound();
        
        return $this->redirect($model->getCampUrl());
    }
    
    public function actionList()
    {
        $searchModel = new CampsSearch();
        $searchModel->setScenario(CampsSearch::SCENARIO_PARTNER);
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        
        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    public function actionEdit($id)
    {
        /** @var $model Camps */
        $model = Camps::find()->byPartner(Yii::$app->user->id)->andWhere(['id' => $id])->one();
        if (!$model) $this->notFound();
    
        return $this->redirect(['/camp-register', 'camp_id' => $model->id]);
    }
    
    public function actionDelete($id, $confirm = false)
    {
        /** @var $model Camps */
        $model = Camps::find()->byPartner(Yii::$app->user->id)->andWhere(['id' => $id])->one();
        if (!$model) $this->notFound();
        
        if ($confirm === true) {
            $model->setDeleteStatus();
            
            Yii::$app->session->setFlash('success', 'Лагерь успешно удален');
            return $this->redirect(['list']);
        }
        
        return $this->render('delete', [
            'model' => $model
        ]);
    }
    
    public function actionTrash($id)
    {
        return $this->actionDelete($id, true);
    }
}
