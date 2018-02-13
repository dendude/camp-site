<?php
namespace app\modules\partner;

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
    public $controllerNamespace = 'app\modules\partner\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!Users::isPartner()) {
            return Yii::$app->response->redirect(Yii::$app->homeUrl);
        }

        $this->layout = 'partner';

        return true;
    }
}
