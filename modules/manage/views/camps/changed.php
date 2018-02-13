<?php
use app\helpers\MetaHelper;
use yii\widgets\LinkPager;
use yii\helpers\Html;
use app\models\forms\UploadForm;
use yii\helpers\Url;
use app\helpers\Normalize;

/** @var $this \yii\web\View */
/** @var $models \app\models\Changes[] */
/** @var $pages \yii\data\Pagination */

$this->title = 'Изменения лагерей партнерами';
$this->params['breadcrumbs'] = [
    ['label' => \app\modules\manage\controllers\CampsController::LIST_NAME, 'url' => ['list']],
    $this->title
];
?>
<?= \app\helpers\MHtml::alertMsg(); ?>
<? if (!$models): ?>
    <div class="alert alert-info">Новые изменения не обнаружены</div>
<? endif; ?>
<? foreach ($models as $k => $model): ?>
    <div class="box box-info w-1000">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($model->camp->about->name_short) ?></h3>
            <div class="pull-right">Изменено: <strong><?= Normalize::getFullDateByTime($model->created) ?></strong></div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-xs-3">
                    <a href="<?= UploadForm::getSrc($model->camp->media->photo_main) ?>" class="a-slider" title="<?= Html::encode($model->camp->about->name_full) ?>">
                        <img class="w-p-100" src="<?= UploadForm::getSrc($model->camp->media->photo_main, UploadForm::TYPE_CAMP, '_md') ?>" alt="Фото-аватар">
                    </a>
                </div>
                <div class="col-xs-9">
                    <?= $model->getDiffList() ?>

                    <a href="<?= Url::to(['camps/show', 'id' => $model->camp_id]) ?>" class="btn btn-default btn-sm m-r-10" target="_blank">
                        <i class="fa fa-search"></i>&nbsp;&nbsp;Посмотреть на сайте
                    </a>
                    <a href="<?= Url::to(['camps/edit', 'id' => $model->camp_id]) ?>" class="btn btn-info btn-sm m-r-10" target="_blank">
                        <i class="fa fa-pencil"></i>&nbsp;&nbsp;Редактировать
                    </a>
                    <a href="<?= Url::to(['camps/changed-process', 'id' => $model->id]) ?>" class="btn btn-warning btn-sm m-r-10">
                        <i class="fa fa-check"></i>&nbsp;&nbsp;Отметить как проверенный
                    </a>
                    <a href="<?= Url::to(['camps/delete', 'id' => $model->camp_id]) ?>" class="btn btn-danger btn-sm" target="_blank">
                        <i class="fa fa-trash"></i>&nbsp;&nbsp;Удалить
                    </a>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
<? endforeach; ?>

<?
// display pagination
echo LinkPager::widget([
    'pagination' => $pages,
]);