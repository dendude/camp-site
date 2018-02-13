<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $model \app\models\Settings */
/** @var $this \yii\web\View */

$this->title = 'Настройка уведомлений';
$this->params['breadcrumbs'] = [$this->title];

$w100 = ['inputOptions' => ['class' => 'form-control w-100']];
$w200 = ['inputOptions' => ['class' => 'form-control w-200']];
$w300 = ['inputOptions' => ['class' => 'form-control w-300']];

$form = ActiveForm::begin();
?>
<div class="max-width-1200">
    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
    <?= \app\helpers\MHtml::alertMsg(); ?>
    <div class="box box-primary">
        <div class="box-header with-border"><?= Yii::$app->params['required_fields'] ?></div>
        <div class="box-body">
            <div class="separator"></div>
            
            <?= $form->field($model, 'emails_order')->textarea() ?>
            <?= $form->field($model, 'emails_new_camp')->textarea() ?>
            <?= $form->field($model, 'emails_edit_camp')->textarea() ?>
            <?= $form->field($model, 'emails_change_order_status')->textarea() ?>
    
            <div class="separator"></div>
            <div class="row row-comment">
                <div class="col-xs-12 col-md-offset-4 col-md-7">
                    Несколько емайлов нужно указывать через запятую или переносом строки
                </div>
            </div>
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