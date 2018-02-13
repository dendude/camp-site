<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \app\models\Reviews;
use app\helpers\Statuses;
use yii\helpers\Url;
use app\models\ReviewsItems;

/** @var $model Reviews */
/** @var $this \yii\web\View */

$action = $model->id ? 'Редактирование отзыва' : 'Добавление отзыва';
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => \app\modules\manage\controllers\ReviewsController::LIST_NAME, 'url' => ['list']],
    ['label' => $action]
];

if ($model->id) {
    // для показа кол-ва символов у редактируемой страницы
    $this->registerJs("$('textarea').trigger('keyup');");
}

$form = ActiveForm::begin();

$w100 = ['inputOptions' => ['class' => 'form-control w-100']];
$w200 = ['inputOptions' => ['class' => 'form-control w-200']];

$camps_filter = $model->base_id ? [$model->base_id => $model->camp->about->name_short] : [];
?>
<div class="max-width-1000">
    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
    <? \app\helpers\MHtml::alertMsg(); ?>
    <div class="box box-primary">
        <div class="box-header with-border"><?= Yii::$app->params['required_fields'] ?></div>
        <div class="box-body">
            <?= $form->field($model, 'base_id')->dropDownList($camps_filter, [
                'class' => 'form-control select2',
                'data-data-type' => 'json',
                'data-minimum-input-length' => 3,
                'data-ajax--url' => Url::to(['/ajax/camps']),
                'data-ajax--delay' => 750,
                'data-ajax--cache' => 'true',
                'data-type' => 'camp',
                'prompt' => '- Начните вводить название лагеря -'
            ]) ?>
            
            <div class="separator"></div>

            <?= $form->field($model, 'user_name') ?>
            <?= $form->field($model, 'user_email') ?>

            <div class="separator"></div>

            <?= $form->field($model, 'stars', $w100)->textInput(['readonly' => !empty($model->votes_arr)])->label('Общая оценка') ?>
            
            <? if ($model->votes_arr): ?>
                <div class="form-group">
                    <div class="col-xs-12 col-md-4 text-right">
                        <label for="">Оценки по критериям</label>
                    </div>
                    <div class="col-xs-12 col-md-7">
                        <table class="table table-condensed">
                        <? foreach (ReviewsItems::find()->where(['id' => array_keys($model->votes_arr)])->all() AS $item): ?>
                            <tr>
                                <td><?= Html::encode($item->title) ?>:</td>
                                <td><?= $model->votes_arr[$item->id] ?></td>
                                <td><?= ReviewsItems::getVoteName($model->votes_arr[$item->id]) ?></td>
                            </tr>
                        <? endforeach; ?>
                        </table>
                    </div>
                </div>
            <? endif; ?>

            <div class="separator"></div>
            
            <?= $form->field($model, 'comment_positive')->textarea(['onkeyup' => 'charsCalculate(this)', 'maxlength' => true]) ?>
            <?= $form->field($model, 'comment_negative')->textarea(['onkeyup' => 'charsCalculate(this)', 'maxlength' => true]) ?>
            <?= $form->field($model, 'comment_manager')->textarea(['onkeyup' => 'charsCalculate(this)', 'maxlength' => true]) ?>
                
            <div class="separator"></div>
            
            <?= $form->field($model, 'ordering', $w100)->input('number') ?>
    
            <div class="separator"></div>
    
            <?= $form->field($model, 'status')->checkbox(['class' => 'ichecks', 'label' => 'Опубликовать на сайте', 'uncheck' => Statuses::STATUS_USED]) ?>
            <?/*= $form->field($model, 'user_notice')->checkbox(['class' => 'ichecks', 'label' => 'Опубликовать на сайте']) */?>
            
            <div class="separator"></div>

            <div class="form-group">
                <div class="col-xs-offset-4 col-xs-2">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" value="<?= Yii::$app->request->referrer ?>" name="ref-page"/>
<?php ActiveForm::end() ?>