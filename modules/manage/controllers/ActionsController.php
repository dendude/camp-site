<?php
namespace app\modules\manage\controllers;

use yii\web\Controller;

class ActionsController extends Controller
{
    public function actionList()
    {
        return $this->render('list');
    }
}
