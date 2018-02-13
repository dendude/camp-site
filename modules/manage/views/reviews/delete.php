<?php
use yii\helpers\Html;
use app\modules\manage\controllers\CampsTypesController;

/** @var $model \app\models\Reviews */

$this->title = 'Удаление отзыва';
$this->params['breadcrumbs'] = [
    ['label' => CampsTypesController::LIST_NAME, 'url' => ['list']],
    ['label' => $this->title]
];
?>
<div class="max-width-700">
    <div class="box box-widget widget-user-2">
        <div class="widget-user-header bg-red strong">Подтверждаете удаление?</div>
        <div class="box-body">
            <div class="separator"></div>
            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->getAttributeLabel('base_id') ?></label>
                <div class="col-xs-6"><?= Html::encode($model->camp->getFullName()) ?></div>
            </div>

            <div class="separator"></div>

            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->getAttributeLabel('user_name') ?></label>
                <div class="col-xs-6"><?= Html::encode($model->user_name) ?></div>
            </div>
            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->getAttributeLabel('user_email') ?></label>
                <div class="col-xs-6"><?= Html::encode($model->user_email) ?></div>
            </div>

            <div class="separator"></div>

            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->getAttributeLabel('stars') ?></label>
                <div class="col-xs-6"><?= Html::encode($model->stars) ?></div>
            </div>
            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->getAttributeLabel('comment_positive') ?></label>
                <div class="col-xs-6"><?= Html::encode($model->comment_positive) ?></div>
            </div>
            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->getAttributeLabel('comment_negative') ?></label>
                <div class="col-xs-6"><?= Html::encode($model->comment_negative) ?></div>
            </div>
            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->getAttributeLabel('comment_manager') ?></label>
                <div class="col-xs-6"><?= Html::encode($model->comment_manager) ?></div>
            </div>

            <div class="separator"></div>

            <div class="row">
                <div class="col-xs-offset-2 col-xs-4">
                    <?= Html::a('Удалить', ['trash', 'id' => $model->id], ['class' => 'btn btn-danger btn-flat btn-block']) ?>
                </div>
                <div class="col-xs-4">
                    <?= Html::a('Отмена', ['list'], ['class' => 'btn btn-default btn-flat btn-block']) ?>
                </div>
            </div>
            <div class="separator"></div>
        </div>
    </div>
</div>