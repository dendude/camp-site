<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\manage\controllers\MenuController;

$this->title = $model->id ? 'Редактирование пункта меню' : 'Добавление пункта меню';
$this->params['breadcrumbs'] = [
    ['label' => MenuController::LIST_NAME, 'url' => ['list']],
    ['label' => $this->title]
];

$form = ActiveForm::begin();

$inputMiddle = ['inputOptions' => ['class' => 'form-control input-middle']];
?>
<div class="max-width-900">
    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
    <? \app\helpers\MHtml::alertMsg(); ?>
    <div class="box box-primary">
        <div class="box-header with-border"><?= Yii::$app->params['required_fields'] ?></div>
        <div class="box-body">
            <?= $form->field($model, 'parent_id')->dropDownList(\app\models\Menu::getFilterList(true), ['encode' => false, 'prompt' => '--']) ?>
            <div class="separator"></div>
            <?= $form->field($model, 'name') ?>
            <?= $form->field($model, 'title') ?>
            <div class="separator"></div>
            <?= $form->field($model, 'page_id')->dropDownList(\app\models\Pages::getFilterList(), ['prompt' => '']) ?>
            <div class="form-group">
                <div class="col-xs-12 col-md-4"></div>
                <div class="col-xs-12 col-md-8">или</div>
            </div>
            <?= $form->field($model, 'type_id')->dropDownList(\app\models\TagsTypes::getFilterList(), ['prompt' => '']) ?>
            <div class="separator"></div>
            <?= $form->field($model, 'status')->checkbox(['label' => 'Опубликовать', 'class' => 'ichecks']) ?>
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