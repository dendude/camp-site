<?php

namespace app\modules\office\controllers;

use yii\web\Controller;

/**
 * Default controller for the `office` module
 */
class MessagesController extends Controller
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
