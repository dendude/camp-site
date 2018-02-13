<?php

namespace app\modules\partner\controllers;

use yii\web\Controller;

/**
 * Default controller for the `office` module
 */
class StatsController extends Controller
{
    const INDEX_NAME = 'Статистика';

    public function actionIndex()
    {
        return $this->render('index');
    }
}
