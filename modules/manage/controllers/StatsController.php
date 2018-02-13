<?php
namespace app\modules\manage\controllers;

use yii\web\Controller;

class StatsController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
