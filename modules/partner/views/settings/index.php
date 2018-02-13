<?
use yii\helpers\Url;
use app\models\Users;
use yii\widgets\ActiveForm;

/**
 * @var $model Users
 */

$this->title = \app\modules\partner\controllers\SettingsController::INDEX_NAME;
$this->params['breadcrumbs'] = [$this->title];

$dtpk = ['inputOptions' => ['class' => 'form-control w-150 datepickers']];
?>
<div class="clearfix"></div>

<? $form = ActiveForm::begin(); ?>
<?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
<?= \app\helpers\MHtml::alertMsg(); ?>
<div class="bg-gray p-25 b-r-6">
    <div class="m-b-20">
        <?= $form->field($model, 'settings_arr[partner_notif_camp_email]')->checkbox([
            'class' => 'ichecks', 'label' => 'Получать уведомления об изменении статуса лагеря на Email'
        ]) ?>
        <?= $form->field($model, 'settings_arr[partner_notif_camp_phone]')->checkbox([
            'class' => 'ichecks', 'label' => 'Получать SMS-уведомления об изменении статуса лагеря', 'disabled' => true
        ]) ?>
    </div>
    <div class="m-b-20">
        <?= $form->field($model, 'settings_arr[partner_notif_order_email]')->checkbox([
            'class' => 'ichecks', 'label' => 'Получать уведомления о брони на Email'
        ]) ?>
        <?= $form->field($model, 'settings_arr[partner_notif_order_phone]')->checkbox([
            'class' => 'ichecks', 'label' => 'Получать SMS-уведомления о брони', 'disabled' => true
        ]) ?>
    </div>
    <div class="m-b-20">
        <?= $form->field($model, 'settings_arr[partner_notif_finance_email]')->checkbox([
            'class' => 'ichecks', 'label' => 'Получать уведомления о необходимости оплаты на Email'
        ]) ?>
        <?= $form->field($model, 'settings_arr[partner_notif_finance_phone]')->checkbox([
            'class' => 'ichecks', 'label' => 'Получать SMS-уведомления о необходимости оплаты', 'disabled' => true
        ]) ?>
    </div>
    
    <div class="m-t-30"></div>
    <?= $form->field($model, 'contacts_boss_fio') ?>
    <?= $form->field($model, 'contacts_boss_phone') ?>
    <?= $form->field($model, 'contacts_boss_email') ?>
    <div class="m-t-30"></div>
    <?= $form->field($model, 'contacts_worker_fio') ?>
    <?= $form->field($model, 'contacts_worker_phone') ?>
    <?= $form->field($model, 'contacts_worker_email') ?>
    <div class="m-t-30"></div>
    <?= $form->field($model, 'contacts_office_address') ?>
    <?= $form->field($model, 'contacts_office_phone') ?>
    <?= $form->field($model, 'contacts_office_route') ?>
    <div class="m-t-30"></div>
    <?= $form->field($model, 'contacts_notify_emails') ?>
    <?= $form->field($model, 'contacts_notify_phones') ?>
    <div class="m-t-30"></div>
    <?= $form->field($model, 'contract_inn') ?>
    <?= $form->field($model, 'contract_ogrn_serial') ?>
    <?= $form->field($model, 'contract_ogrn_number') ?>
    <?= $form->field($model, 'contract_ogrn_date_f', $dtpk) ?>
    <div class="m-t-30"></div>
    
    <div class="row">
        <div class="col-xs-12 col-md-offset-4 col-md-7">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>