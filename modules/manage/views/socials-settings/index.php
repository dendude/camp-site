<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $model \app\models\Settings */
/** @var $this \yii\web\View */

$this->title = 'Настройка ссылок на сообщества соцсетей';
$this->params['breadcrumbs'] = [$this->title];

$form = ActiveForm::begin();
?>
<div class="max-width-800">
    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
    <?= \app\helpers\MHtml::alertMsg(); ?>
    <div class="box box-primary">
        <div class="box-header with-border"><?= Yii::$app->params['required_fields'] ?></div>
        <div class="box-body">
            <div class="separator"></div>
            
            <?= $form->field($model, 'social_vk') ?>
            <?= $form->field($model, 'social_ok') ?>
            <?= $form->field($model, 'social_fb') ?>
            
            <div class="separator"></div>
            
            <div class="form-group">
                <div class="col-xs-offset-4 col-xs-2">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>