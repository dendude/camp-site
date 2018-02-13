<?php
namespace app\modules\manage\controllers;

use yii\web\Controller;

class RatingsController extends Controller
{
    public function actionList()
    {
        return $this->render('list');
    }
}
