<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $model \app\models\Settings */
/** @var $this \yii\web\View */

$this->title = 'Настройка SMTP для отправки почты';
$this->params['breadcrumbs'] = [$this->title];

$w100 = ['inputOptions' => ['class' => 'form-control w-100']];
$w200 = ['inputOptions' => ['class' => 'form-control w-200']];
$w300 = ['inputOptions' => ['class' => 'form-control w-300']];

$form = ActiveForm::begin();
?>
<div class="max-width-800">
    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
    <?= \app\helpers\MHtml::alertMsg(); ?>
    <div class="box box-primary">
        <div class="box-header with-border"><?= Yii::$app->params['required_fields'] ?></div>
        <div class="box-body">
            <div class="separator"></div>
            
            <?= $form->field($model, 'email_username') ?>
            <?= $form->field($model, 'email_password') ?>
            
            <div class="separator"></div>

            <?= $form->field($model, 'email_host') ?>
            <?= $form->field($model, 'email_port', $w100) ?>
    
            <div class="separator"></div>
            
            <?= $form->field($model, 'email_fromname') ?>
            <?= $form->field($model, 'email_sign')->textarea(['autofocus' => Yii::$app->request->get('sign')]) ?>
            
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