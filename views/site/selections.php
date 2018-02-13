<?php
use app\helpers\MetaHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\forms\UploadForm;
use app\models\Camps;

/**
 * @var $this yii\web\View
 * @var $model \app\models\Pages
 * @var $selections \app\models\Selections[]
 */

$this->title = $model->title;
$this->params['breadcrumbs'] = [
    $model->title
];

MetaHelper::setMeta($model, $this);
?>
<div class="layout-container">
    <h1 class="index-title m-t-0"><?= $model->title ?></h1>
    <div class="m-b-20"><?= $model->content ?></div>
    <? if ($selections): ?>
    <ul class="selections-list">
        <? foreach ($selections AS $sel): ?>
            <li>
                <a href="<?= Url::to(['site/camps', 'type' => Camps::TYPE_TYPE, 'alias' => $sel->type->alias]) ?>">
                    <span class="selection-photo">
                        <img src="<?= UploadForm::getSrc($sel->photo, UploadForm::TYPE_PAGES, '_md') ?>"
                             alt="<?= Html::encode($sel->type->title_full) ?>">
                    </span>
                    <span class="selection-title"><?= Html::encode($sel->type->title_full) ?></span>
                </a>
            </li>
        <? endforeach; ?>
    </ul>
    <? else: ?>
        <div class="alert alert-info">Подборки не обнаружены</div>
    <? endif; ?>
</div>
<div class="m-t-30">
    <?= \app\widgets\CampsNew::widget(); ?>
</div>
    