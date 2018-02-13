<?php
use yii\helpers\Html;
use app\modules\manage\controllers\LocCountriesController;
use app\modules\manage\controllers\LocRegionsController;

/** @var $model \app\models\LocRegions */

$this->title = 'Удаление региона';
$this->params['breadcrumbs'] = [
    ['label' => LocCountriesController::LIST_NAME, 'url' => ['loc-countries/list']],
    ['label' => LocRegionsController::LIST_NAME, 'url' => ['list']],
    ['label' => $this->title]
];
?>
<div class="max-width-700">
    <div class="box box-widget widget-user-2">
        <div class="widget-user-header bg-red strong">Подтверждаете удаление?</div>
        <div class="box-body">
            <div class="separator"></div>
            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->getAttributeLabel('country_id') ?></label>
                <div class="col-xs-6"><?= Html::encode($model->country->name) ?></div>
            </div>
            <div class="separator"></div>
            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->getAttributeLabel('name') ?></label>
                <div class="col-xs-6"><?= Html::encode($model->name) ?></div>
            </div>
            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->getAttributeLabel('alias') ?></label>
                <div class="col-xs-6"><?= Html::encode($model->alias) ?></div>
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