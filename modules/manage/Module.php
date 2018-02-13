<?php
namespace app\modules\manage;

use Yii;
use app\models\Users;

/**
 * Manage module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\manage\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!Users::isAdmin()) {
            return Yii::$app->response->redirect(Yii::$app->homeUrl);
        }
    
        $this->layout = 'manage';
        \Yii::$app->errorHandler->errorAction = 'manage/main/error';
        
        return true;
    }
}
