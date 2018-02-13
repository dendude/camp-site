<?php
use yii\helpers\Html;
use app\modules\manage\controllers\FaqController;

/** @var $model \app\models\Camps */

$this->title = 'Удаление лагеря из базы';
$this->params['breadcrumbs'] = [
    ['label' => \app\modules\manage\controllers\CampsController::LIST_NAME, 'url' => ['list']],
    ['label' => $this->title]
];
?>
<div class="max-width-800">
    <div class="box box-widget widget-user-2">
        <div class="widget-user-header bg-red strong">Подтверждаете удаление?</div>
        <div class="box-body">
            <div class="separator"></div>
            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->about->getAttributeLabel('id') ?></label>
                <div class="col-xs-6"><?= $model->id ?></div>
            </div>
            <div class="separator"></div>
            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->about->getAttributeLabel('name_full') ?></label>
                <div class="col-xs-6"><?= Html::encode($model->about->name_full) ?></div>
            </div>
            <div class="separator"></div>
            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->about->getAttributeLabel('loc_country') ?></label>
                <div class="col-xs-6"><?= Html::encode($model->about->country->name) ?></div>
            </div>
            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->about->getAttributeLabel('loc_region') ?></label>
                <div class="col-xs-6"><?= Html::encode($model->about->region->name) ?></div>
            </div>
            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->about->getAttributeLabel('loc_city') ?></label>
                <div class="col-xs-6"><?= Html::encode($model->about->city->name) ?></div>
            </div>
            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->about->getAttributeLabel('loc_address') ?></label>
                <div class="col-xs-6"><?= Html::encode($model->about->loc_address) ?></div>
            </div>
            <div class="separator"></div>
            <div class="row">
                <div class="col-xs-offset-2 col-xs-4">
                    <?= Html::a('Удалить', ['trash', 'id' => $model->id, 'new' => Yii::$app->request->get('new')], ['class' => 'btn btn-danger btn-flat btn-block']) ?>
                </div>
                <div class="col-xs-4">
                    <? if (Yii::$app->request->get('new')): ?>
                        <?= Html::a('Отмена', ['camps-new/list'], ['class' => 'btn btn-default btn-flat btn-block']) ?>
                    <? else: ?>
                        <?= Html::a('Отмена', ['list'], ['class' => 'btn btn-default btn-flat btn-block']) ?>
                    <? endif; ?>
                </div>
            </div>
            <div class="separator"></div>
        </div>
    </div>
</div>