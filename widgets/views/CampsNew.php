<?php
use app\models\forms\UploadForm;
use app\helpers\Normalize;
use yii\helpers\Html;

/** @var $camps \app\models\Camps[] */
?>
<div class="layout-container">
    <div class="camp-new">
        <h2 class="index-title">Новое на CAMP-CENTR</h2>
        <div class="slider-container">
            <ul class="slider-new">
                <? foreach ($camps AS $k => $m): ?>
                    <li>
                        <a href="<?= $m->getCampUrl() ?>" class="slider-new__item">
                            <span class="slider-new__photo">
                                <img src="<?= UploadForm::getSrc($m->media->photo_main, UploadForm::TYPE_CAMP, '_md') ?>"
                                     alt="<?= Html::encode($m->about->name_short) ?>" />
                            </span>
                            <span class="slider-new__info">
                                <span class="slider-new__title">
                                    <?= Html::encode($m->about->name_short) ?>, <?= Html::encode($m->about->country->name) ?>
                                </span>
                                <span class="slider-new__date"><?= Normalize::getDateByTime($m->created) ?></span>
                            </span>
                        </a>
                    </li>
                <? endforeach; ?>
            </ul>
            <div class="slider-controls slider-controls-new">
                <span class="slide-prev"></span>
                <span class="slide-next"></span>
            </div>
        </div>
    </div>
</div>
<?php
$this->registerJs("
tns({
    container: document.querySelector('.slider-new'),
    controlsContainer: document.querySelector('.slider-controls-new'),
    autoplayHoverPause: true,
    controls: true,
    autoplay: true,
    nav: false,
    speed: 750,
    items: 3,
    responsive: {
        400: 1,
        800: 2,
        1100: 3
    }
});
");