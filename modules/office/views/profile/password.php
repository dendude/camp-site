<?
use yii\widgets\ActiveForm;

/**
 * @var $model \app\models\forms\PasswordForm
 *@var $this \yii\web\View
 */

$this->title = 'Изменение пароля';
$this->params['breadcrumbs'] = [
    ['label' => \app\modules\office\controllers\ProfileController::INDEX_NAME, 'url' => ['index']],
    $this->title
];

$w300 = ['inputOptions' => ['class' => 'form-control w-300']];
?>
<div class="clearfix"></div>

<? $form = ActiveForm::begin(); ?>

<?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
<?= \app\helpers\MHtml::alertMsg(); ?>

<div class="bg-gray p-25 b-r-6">
    <?= $form->field($model, 'pass_old', $w300)->passwordInput(['value' => '']) ?>
    <?= $form->field($model, 'pass_new', $w300)->passwordInput(['value' => '']) ?>
    <?= $form->field($model, 'pass_new2', $w300)->passwordInput(['value' => '']) ?>
    <div class="row">
        <div class="col-xs-12 col-md-offset-4 col-md-7">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>
<?php
$this->registerJs('
    $("#' . $form->id . '").find("input").val("");
');
