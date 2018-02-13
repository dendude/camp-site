<?php
namespace app\modules\office;

use Yii;
use app\models\Users;

/**
 * office module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\office\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (Yii::$app->user->isGuest) {
            return Yii::$app->response->redirect(Yii::$app->homeUrl);
        }

        $this->layout = 'office';

        return true;
    }
}
