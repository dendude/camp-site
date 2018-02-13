<?php
use yii\helpers\Html;
use app\modules\manage\controllers\FaqController;

/** @var $model \app\models\Camps */
$this->title = 'Подтверждаете удаление лагеря №' . $model->id . ' ?';
$this->params['breadcrumbs'] = [
    ['label' => \app\modules\partner\controllers\CampsController::LIST_NAME, 'url' => ['list']],
    ['label' => $this->title]
];
?>
<div class="clearfix"></div>
<div class="bg-gray p-25 b-r-6">
    <div class="form-horizontal">
        <div class="form-group">
            <label class="col-xs-6 text-right"><?= $model->about->getAttributeLabel('name_short') ?></label>
            <div class="col-xs-6"><?= Html::encode($model->about->name_short) ?></div>
        </div>
        <div class="form-group">
            <label class="col-xs-6 text-right"><?= $model->about->getAttributeLabel('name_full') ?></label>
            <div class="col-xs-6"><?= Html::encode($model->about->name_full) ?></div>
        </div>
        <div class="form-group">
            <label class="col-xs-6 text-right"><?= $model->about->getAttributeLabel('loc_country') ?></label>
            <div class="col-xs-6"><?= Html::encode($model->about->country->name) ?></div>
        </div>
        <div class="form-group">
            <label class="col-xs-6 text-right"><?= $model->about->getAttributeLabel('loc_region') ?></label>
            <div class="col-xs-6"><?= Html::encode($model->about->region->name) ?></div>
        </div>
        <div class="form-group">
            <label class="col-xs-6 text-right"><?= $model->about->getAttributeLabel('loc_city') ?></label>
            <div class="col-xs-6"><?= Html::encode($model->about->city->name) ?></div>
        </div>
        <div class="form-group">
            <label class="col-xs-6 text-right"><?= $model->about->getAttributeLabel('loc_address') ?></label>
            <div class="col-xs-6"><?= Html::encode($model->about->loc_address) ?></div>
        </div>
        <div class="form-group">
            <div class="col-xs-offset-2 col-xs-4">
                <?= Html::a('Удалить', ['trash', 'id' => $model->id], ['class' => 'btn btn-danger btn-flat btn-block']) ?>
            </div>
            <div class="col-xs-4">
                <?= Html::a('Отмена', ['list'], ['class' => 'btn btn-default btn-flat btn-block']) ?>
            </div>
        </div>
    </div>
</div>