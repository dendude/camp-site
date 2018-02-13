<?php

namespace app\modules\office\controllers;

use yii\web\Controller;

/**
 * Default controller for the `office` module
 */
class BonusesController extends Controller
{
    const LIST_NAME = 'Мои бонусы';
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionList()
    {
        return $this->render('list');
    }
}
