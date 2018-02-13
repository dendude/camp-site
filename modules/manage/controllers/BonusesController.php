<?php
namespace app\modules\manage\controllers;

use app\models\Bonuses;
use Yii;
use yii\web\Controller;
use app\models\search\BonusesSearch;

class BonusesController extends Controller
{
    const LIST_NAME = 'Бонусы на сайте';
    
    protected function notFound($message = 'Бонус не найден') {
        Yii::$app->session->setFlash('error', $message);
        return $this->redirect(['list']);
    }
    
    public function actionList()
    {
        $searchModel = new BonusesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        
        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    public function actionEdit($id) {
        
        $model = Bonuses::findOne($id);
        if (!$model) return $this->notFound();
        
        if (Yii::$app->request->post('Bonuses')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Бонус успешно сохранен');
                return $this->redirect(['list']);
            }
        }
        
        return $this->render('add', [
            'model' => $model
        ]);
    }
}
