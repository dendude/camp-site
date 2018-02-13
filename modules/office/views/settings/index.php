<?
use yii\helpers\Url;
use app\models\Users;
use yii\widgets\ActiveForm;

/** @var $model Users */

$this->title = \app\modules\office\controllers\SettingsController::INDEX_NAME;
$this->params['breadcrumbs'] = [$this->title];
?>
<div class="clearfix"></div>

<? $form = ActiveForm::begin(); ?>
<?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
<?= \app\helpers\MHtml::alertMsg(); ?>
<div class="bg-gray p-25 b-r-6">
    <?= $form->field($model, 'settings_arr[user_notif_email]')->checkbox(['class' => 'ichecks', 'label' => 'Получать уведомления на Email']) ?>
    <?= $form->field($model, 'settings_arr[user_notif_phone]')->checkbox([
        'class' => 'ichecks',
        'label' => 'Получать SMS-уведомления [временно недоступно]',
        'labelOptions' => ['class' => 'text-muted'],
        'disabled' => 'disabled']) ?>
    
    <div class="m-t-30"></div>
    <div class="row">
        <div class="col-xs-12 col-md-offset-4 col-md-7">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>