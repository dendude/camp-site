<?php

namespace app\modules\partner\controllers;

use yii\web\Controller;

/**
 * Default controller for the `office` module
 */
class ContractsController extends Controller
{
    const LIST_NAME = 'Договоры';

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionList()
    {
        return $this->render('list');
    }
}
