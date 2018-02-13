<?php
use app\helpers\MetaHelper;
use yii\widgets\LinkPager;
use yii\helpers\Html;
use app\models\forms\UploadForm;
use yii\helpers\Url;

/** @var $this \yii\web\View */
/** @var $models \app\models\Camps[] */
/** @var $pages \yii\data\Pagination */

$this->title = \app\modules\manage\controllers\CampsNewController::LIST_NAME;
$this->params['breadcrumbs'] = [
    ['label' => \app\modules\manage\controllers\CampsController::LIST_NAME, 'url' => ['camps/list']],
    $this->title
];
?>
<?= \app\helpers\MHtml::alertMsg(); ?>
<? if (!$models): ?>
    <div class="alert alert-info">Новые заявки на размещение лагерей отсутствуют</div>
<? endif; ?>
<div class="row">
<? foreach ($models as $k => $model): ?>
    
    <div class="col-xs-12 col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Html::encode($model->about->name_short) ?></h3>
                <div class="pull-right"><strong>ID: <?= $model->id ?></strong></div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-3">
                        <a href="<?= UploadForm::getSrc($model->media->photo_main) ?>" class="a-slider" title="<?= Html::encode($model->about->name_full) ?>">
                            <img class="w-p-100" src="<?= UploadForm::getSrc($model->media->photo_main, UploadForm::TYPE_CAMP, '_md') ?>" alt="Фото-аватар">
                        </a>
                    </div>
                    <div class="col-xs-9">
                        <table class="table table-condensed table-hover">
                            <tr>
                                <td>Юридическое название</td>
                                <th class="text-left"><?= Html::encode($model->about->name_full) ?></th>
                            </tr>
                            <tr>
                                <th class="text-left">ФИО директора</th>
                                <td><?= Html::encode($model->contacts->boss_fio) ?></td>
                            </tr>
                            <tr>
                                <th class="text-left">Телефон директора</th>
                                <td><?= Html::encode($model->contacts->boss_phone) ?></td>
                            </tr>
                            <tr>
                                <th class="text-left">Email директора</th>
                                <td><?= Html::encode($model->contacts->boss_email) ?></td>
                            </tr>
                            <tr>
                                <th class="text-left">Страна</th>
                                <td><?= $model->about->country->name ?></td>
                            </tr>
                            <tr>
                                <th class="text-left">Регион</th>
                                <td><?= $model->about->region->name ?></td>
                            </tr>
                            <tr>
                                <th class="text-left">Ближайший город</th>
                                <td>
                                        <?= $model->about->city->name ?>
                                    <? if ($model->about->loc_distance_to_city): ?>
                                        (<?= $model->about->loc_distance_to_city ?> км)
                                    <? endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-left">Возраст</th>
                                <td>Для детей <?= $model->about->age_from ?> - <?= $model->about->age_to ?> лет</td>
                            </tr>
                            <tr>
                                <th class="text-left">Адрес</th>
                                <td><?= Html::encode($model->about->loc_address) ?></td>
                            </tr>
                        </table>
    
                        <a href="<?= Url::to(['camps/show', 'id' => $model->id]) ?>" class="btn btn-default btn-sm m-r-10" target="_blank">
                            <i class="fa fa-search"></i>&nbsp;&nbsp;Посмотреть на сайте
                        </a>
                        <a href="<?= Url::to(['camps/edit', 'id' => $model->id]) ?>" class="btn btn-info btn-sm m-r-10">
                            <i class="fa fa-pencil"></i>&nbsp;&nbsp;Обработать
                        </a>
                        <a href="<?= Url::to(['camps/delete', 'id' => $model->id, 'new' => 1]) ?>" class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i>&nbsp;&nbsp;Удалить
                        </a>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    
    <? if ($k%2 == 1): ?><div class="clearfix"></div><? endif; ?>
    
<? endforeach; ?>
</div>
<?
// display pagination
echo LinkPager::widget([
    'pagination' => $pages,
]);