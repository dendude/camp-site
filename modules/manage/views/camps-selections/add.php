<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/** @var $model \app\models\Selections */
/** @var $this \yii\web\View */

$action = $model->id ? 'Редактирование подборки' : 'Добавление подборки';
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => \app\modules\manage\controllers\CampsSelectionsController::LIST_NAME, 'url' => ['list']],
    ['label' => $action]
];

$w100 = ['inputOptions' => ['class' => 'form-control w-100']];
$form = ActiveForm::begin();
?>
<div class="max-width-1200">
    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
    <?= \app\helpers\MHtml::alertMsg(); ?>
    <div class="box box-primary">
        <div class="box-header with-border"><?= Yii::$app->params['required_fields'] ?></div>
        <div class="box-body">
            <div class="form-group <? if ($model->isAttributeRequired('photo')): ?>required<? endif; ?>">
                <label class="control-label text-right col-xs-12 col-lg-4" for="<?= Html::getInputId($model, 'photo') ?>">
                    <?= $model->getAttributeLabel('photo') ?>
                </label>
                <div class="col-xs-12 col-lg-7">
                    <?= \app\modules\manage\widgets\DropZoneWidget::widget([
                        'zone_id' => 'drop_zone',
                        'model' => $model,
                        'field' => 'photo',
                        'url' => \yii\helpers\Url::to(['upload']),
                    ]) ?>
                </div>
            </div>
            
            <div class="separator"></div>
            <?= $form->field($model, 'type_id')->dropDownList(\app\models\TagsTypes::getFilterList(), [
                'prompt' => ''
            ]) ?>
            <div class="separator"></div>
            <?= $form->field($model, 'ordering', $w100)->input('number') ?>
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