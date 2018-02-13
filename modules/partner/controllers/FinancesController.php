<?php

namespace app\modules\partner\controllers;

use yii\web\Controller;

/**
 * Default controller for the `office` module
 */
class FinancesController extends Controller
{
    const LIST_NAME = 'Финансы';

    public function actionList()
    {
        return $this->render('list');
    }
}
