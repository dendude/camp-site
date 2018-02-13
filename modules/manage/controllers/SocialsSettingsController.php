<?php
namespace app\modules\manage\controllers;

use Yii;
use app\models\Settings;
use yii\web\Controller;

class SocialsSettingsController extends Controller
{
    public function actionIndex() {
        $model = Settings::lastSettings();
        
        if (Yii::$app->request->post('Settings')) {
            $model = new Settings();
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
