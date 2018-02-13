<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Settings;

/** @var $model \app\models\Settings */
/** @var $this \yii\web\View */

$this->title = 'Лимиты сайта';
$this->params['breadcrumbs'] = [$this->title];

$w100 = ['inputOptions' => ['class' => 'form-control w-100']];
$w200 = ['inputOptions' => ['class' => 'form-control w-200']];
$w300 = ['inputOptions' => ['class' => 'form-control w-300']];

$form = ActiveForm::begin([
    'fieldConfig' => [
        'template' => '<div class="col-xs-12 col-md-6 text-right">{label}</div><div class="col-xs-12 col-md-3">{input}{error}</div>',
        'labelOptions' => ['class' => 'control-label']
    ],
]);
?>
<div class="max-width-800">
    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
    <?= \app\helpers\MHtml::alertMsg(); ?>
    <div class="box box-primary">
        <div class="box-header with-border"><?= Yii::$app->params['required_fields'] ?></div>
        <div class="box-body">
            <div class="separator"></div>
            
            <?= $form->field($model, 'camps_main_count')->dropDownList(Settings::getCampsMainCounts()) ?>
            
            <div class="separator"></div>
            
            <div class="form-group">
                <div class="col-xs-12 col-md-offset-6 col-md-2">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>