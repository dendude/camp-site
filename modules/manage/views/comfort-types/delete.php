<?php
use yii\helpers\Html;
use app\modules\manage\controllers\CampsTypesController;

/** @var $model \app\models\ComfortTypes */

$this->title = 'Удаление отзыва';
$this->params['breadcrumbs'] = [
    ['label' => \app\modules\manage\controllers\ReviewsItemsController::LIST_NAME, 'url' => ['list']],
    ['label' => $this->title]
];
?>
<div class="max-width-700">
    <div class="box box-widget widget-user-2">
        <div class="widget-user-header bg-red strong">Подтверждаете удаление?</div>
        <div class="box-body">
            <div class="separator"></div>

            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->getAttributeLabel('title') ?></label>
                <div class="col-xs-6"><?= Html::encode($model->title) ?></div>
            </div>
            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->getAttributeLabel('icon') ?></label>
                <div class="col-xs-6"><i class="fa fa-2x <?= $model->icon ?>"></i></div>
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