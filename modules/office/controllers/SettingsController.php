<?php

namespace app\modules\office\controllers;

use Yii;
use app\models\Users;
use yii\web\Controller;

/**
 * Default controller for the `office` module
 */
class SettingsController extends Controller
{
    const INDEX_NAME = 'Мои настройки';
    
    public function actionIndex()
    {
        /** @var $model Users */
        $model = Users::$profile;
        $model->setScenario(Users::SCENARIO_OFFICE);
        
        if (Yii::$app->request->post('Users')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Настройки успешно сохранены');
                return $this->redirect(['index']);
            }
        }
        
        return $this->render('index', [
            'model' => $model
        ]);
    }
}
