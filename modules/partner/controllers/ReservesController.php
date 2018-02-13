<?php

namespace app\modules\partner\controllers;

use yii\web\Controller;

/**
 * Default controller for the `office` module
 */
class ReservesController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionList()
    {
        return $this->render('list');
    }
}
