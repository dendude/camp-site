<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\manage\controllers\FaqController;

/** @var $model \app\models\Bonuses */
/** @var $this \yii\web\View */

$action = $model->id ? 'Редактирование бонуса' : 'Добавление бонуса';
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => FaqController::LIST_NAME, 'url' => ['list']],
    ['label' => $action]
];

$w150 = ['inputOptions' => ['class' => 'form-control w-150']];

$form = ActiveForm::begin();
?>
<div class="max-width-800">
    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
    <? \app\helpers\MHtml::alertMsg(); ?>
    <div class="box box-primary">
        <div class="box-header with-border"><?= Yii::$app->params['required_fields'] ?></div>
        <div class="box-body">
            <div class="separator"></div>
            <?= $form->field($model, 'sys_name')->textInput(['placeholder' => 'Название для менеджеров']) ?>
            <?= $form->field($model, 'site_name') ?>

            <div class="separator"></div>
    
            <?= $form->field($model, 'icon_color', $w150)->input('color') ?>
            <?= $form->field($model, 'icon_class', $w150) ?>
            <div class="row row-comment">
                <div class="col-xs-12 col-md-offset-4 col-md-8">
                    Например: <code>fa-user</code><br>
                    Выбрать подходящую иконку можно по <a href="/lib/AdminLTE/pages/UI/icons.html" target="_blank">этой ссылке</a>.
                </div>
            </div>
                
            <div class="separator"></div>
    
            <?= $form->field($model, 'bonuses', $w150)->input('number', ['min' => 1, 'step' => 1]) ?>
            <?= $form->field($model, 'ordering', $w150)->input('number', ['step' => 1]) ?>
    
            <div class="separator"></div>
    
            <?= $form->field($model, 'status')->checkbox(['class' => 'ichecks', 'label' => 'Опубликовать на сайте']) ?>
            
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