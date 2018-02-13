<?
use yii\helpers\Url;
use app\models\Users;
use yii\widgets\ActiveForm;

/**
 * @var $model Users
 */

$this->title = \app\modules\office\controllers\ProfileController::INDEX_NAME;
$this->params['breadcrumbs'] = [$this->title];
?>
<div class="clearfix"></div>

<? $form = ActiveForm::begin(); ?>
<?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
<?= \app\helpers\MHtml::alertMsg(); ?>

<div class="bg-gray p-25 b-r-6 m-b-50">
    <div class="row">
        <div class="col-xs-12 col-md-4 text-right">
            <label for="" class="control-label">
                <?= $model->getAttributeLabel('email') ?>
            </label>
        </div>
        <div class="col-xs-12 col-md-7">
            <p class="form-control-static">
                <strong class="p-r-10"><?= $model->email ?></strong>
            </p>
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-xs-12 col-md-4 text-right">
            <label for="" class="control-label">
                <?= $model->getAttributeLabel('pass_origin') ?>
            </label>
        </div>
        <div class="col-xs-12 col-md-7">
            <p class="form-control-static">
                <strong class="p-r-10"><?= str_repeat('x', 8) ?></strong>[<a href="<?= Url::to(['password']) ?>">Изменить</a>]
            </p>
        </div>
    </div>
    
    <?= $form->field($model, 'last_name') ?>
    <?= $form->field($model, 'first_name') ?>
    <?= $form->field($model, 'sur_name') ?>
    
    <div class="row m-t-30">
        <div class="col-xs-12 col-md-offset-4 col-md-7">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>