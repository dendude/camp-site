<?php

namespace app\modules\partner\controllers;

use app\models\search\ReviewsSearch;
use Yii;
use yii\web\Controller;

/**
 * Default controller for the `office` module
 */
class ReviewsController extends Controller
{
    const LIST_NAME = 'Отзывы о лагерях';
    
    protected function notFound($message = 'Отзыв не найден') {
        Yii::$app->session->setFlash('error', $message);
        $this->redirect(['list'])->send();
        Yii::$app->end();
    }
    
    public function actionList()
    {
        $searchModel = new ReviewsSearch();
        $searchModel->setScenario(ReviewsSearch::SCENARIO_PARTNER);
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        
        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}
