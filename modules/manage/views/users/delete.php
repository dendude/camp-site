<?php
use yii\helpers\Html;
use app\modules\manage\controllers\CampsTypesController;

/** @var $model \app\models\Users */

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
                <label class="col-xs-6 text-right"><?= $model->getAttributeLabel('role') ?></label>
                <div class="col-xs-6"><?= Html::encode($model->getRoleName()) ?></div>
            </div>

            <div class="separator"></div>

            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->getAttributeLabel('email') ?></label>
                <div class="col-xs-6"><?= Html::encode($model->email) ?></div>
            </div>

            <div class="separator"></div>

            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->getAttributeLabel('last_name') ?></label>
                <div class="col-xs-6"><?= Html::encode($model->last_name) ?></div>
            </div>
            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->getAttributeLabel('first_name') ?></label>
                <div class="col-xs-6"><?= Html::encode($model->first_name) ?></div>
            </div>
            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->getAttributeLabel('sur_name') ?></label>
                <div class="col-xs-6"><?= Html::encode($model->sur_name) ?></div>
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