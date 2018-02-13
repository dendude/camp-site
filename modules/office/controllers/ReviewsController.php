<?php

namespace app\modules\office\controllers;

use app\models\search\OrdersSearch;
use app\models\search\ReviewsSearch;
use Yii;
use yii\web\Controller;

class ReviewsController extends Controller
{
    const LIST_NAME = 'Мои отзывы';
    
    protected function notFound($message = 'Отзыв не найден') {
        Yii::$app->session->setFlash('error', $message);
        $this->redirect(['list'])->send();
        Yii::$app->end();
    }
    
    public function actionList()
    {
        $searchModel = new ReviewsSearch();
        $searchModel->setScenario(ReviewsSearch::SCENARIO_OFFICE);
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        
        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}
