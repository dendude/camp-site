<?php

namespace app\modules\office\controllers;

use app\models\forms\PasswordForm;
use Yii;
use app\models\Users;
use yii\web\Controller;

class ProfileController extends Controller
{
    const INDEX_NAME = 'Мой профиль';
    
    public function actionIndex()
    {
        /** @var $model Users */
        $model = Users::$profile;
        $model->setScenario(Users::SCENARIO_OFFICE);
        
        if (Yii::$app->request->post('Users')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Данные профиля успешно сохранены');
                return $this->redirect(['index']);
            }
        }
        
        return $this->render('index', [
            'model' => $model
        ]);
    }
    
    public function actionPassword()
    {
        $model = new PasswordForm();
        
        if (Yii::$app->request->post('PasswordForm')) {
            $model->load(Yii::$app->request->post());
            if ($model->change()) {
                Yii::$app->session->setFlash('success', 'Пароль успешно изменен');
                return $this->redirect(['index']);
            }
        }
        
        return $this->render('password', [
            'model' => $model
        ]);
    }
}
