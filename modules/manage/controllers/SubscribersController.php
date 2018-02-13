<?php
namespace app\modules\manage\controllers;

use yii\web\Controller;

class SubscribersController extends Controller
{
    public function actionList()
    {
        return $this->render('list');
    }
}
