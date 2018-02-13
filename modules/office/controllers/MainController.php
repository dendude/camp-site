<?php

namespace app\modules\office\controllers;

use yii\web\Controller;

/**
 * Default controller for the `office` module
 */
class MainController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
