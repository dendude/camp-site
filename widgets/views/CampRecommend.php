<?php
use app\models\forms\UploadForm;
use yii\helpers\Html;

/**
 * @var $orders \app\models\Orders[]
 * @var $camps \app\models\Camps[]
 * @var $class string
 */
?>
<div class="camp-recommends">
    <? foreach ($camps AS $camp): ?>
        <a href="<?= $camp->getCampUrl() ?>" class="camp-recommends__item <?= $class ?>" title="<?= Html::encode($camp->about->name_short) ?>">
            <span class="camp-recommends__title"><?= Html::encode($camp->about->name_short) ?></span>
            <span class="camp-recommends__subtitle"><?= $camp->about->country->name ?>, <?= $camp->about->region->name ?></span>
            <span class="camp-recommends__photo">
                <img src="<?= UploadForm::getSrc($camp->media->photo_order_free, UploadForm::TYPE_CAMP, '_md') ?>"
                     alt="<?= Html::encode($camp->about->name_short) ?>">
            </span>
            <? if ($camp->itemsActive): ?>
                <span class="camp-recommends__price">
                    <?= number_format($camp->itemsActive[0]->getCurrentPrice(), 0, '', ' ') ?>&nbsp;<i class="als-rub">p</i>
                </span>
                <span class="camp-recommends__order">Забронировать</span>
            <? endif; ?>
        </a>
    <? endforeach; ?>
</div>