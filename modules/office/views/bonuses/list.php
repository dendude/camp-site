<?
use yii\helpers\Url;

$this->params['breadcrumbs'] = [
    \app\modules\office\controllers\BonusesController::LIST_NAME
];

$ca = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
$act[$ca] = 'class="active"';
?>
<div class="layout-container">

</div>