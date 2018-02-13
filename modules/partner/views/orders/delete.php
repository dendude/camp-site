<?php
use yii\helpers\Html;
use app\helpers\Normalize;

/** @var $model \app\models\Orders */

$this->title = 'Подтверждаете удаление брони №' . $model->id . ' ?';
$this->params['breadcrumbs'] = [
    ['label' => \app\modules\partner\controllers\OrdersController::LIST_NAME, 'url' => ['list']],
    ['label' => $this->title]
];
?>
<div class="clearfix"></div>
<div class="bg-gray p-25 b-r-6">
    <div class="form-horizontal">
        <div class="form-group">
            <label class="col-xs-6 text-right">Место отдыха</label>
            <div class="col-xs-6"><?= Html::encode($model->camp->about->country->name . ', ' . $model->camp->about->region->name) ?></div>
        </div>
        <div class="form-group">
            <label class="col-xs-6 text-right">Лагерь</label>
            <div class="col-xs-6"><?= Html::encode($model->camp->about->name_full) ?></div>
        </div>
        <div class="form-group">
            <label class="col-xs-6 text-right">Смена</label>
            <div class="col-xs-6"><?= Html::encode($model->campItem->name_full) ?></div>
        </div>
        <div class="form-group m-b-30">
            <label class="col-xs-6 text-right">Дата заезда</label>
            <div class="col-xs-6"><?= Normalize::getShortDate($model->campItem->date_from) ?> - <?= Normalize::getShortDate($model->campItem->date_to) ?></div>
        </div>
        <div class="form-group">
            <div class="col-xs-offset-2 col-xs-4">
                <?= Html::a('Удалить', ['trash', 'id' => $model->id], ['class' => 'btn btn-danger btn-block']) ?>
            </div>
            <div class="col-xs-4">
                <?= Html::a('Отмена', ['list'], ['class' => 'btn btn-default btn-block']) ?>
            </div>
        </div>
    </div>
</div>