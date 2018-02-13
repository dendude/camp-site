<?php
use yii\grid\GridView;
use yii\helpers\Html;
use app\helpers\Statuses;
use app\helpers\Normalize;
use app\helpers\MHtml;
use app\helpers\ManageList;
use app\models\forms\UploadForm;
use app\models\Camps;
use \yii\helpers\Url;

// для редиректа на текущую страницу после редактирования
Url::remember(Url::current(), 'camps-list');

/** @var $dataProvider \yii\data\DataProviderInterface */
/** @var $searchModel \app\models\search\CampsSearch */

$action = \app\modules\manage\controllers\CampsController::LIST_NAME;
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => $action]
];
?>
<?= MHtml::alertMsg(); ?>
<?= Html::a('Добавить', ['add'], ['class' => 'btn btn-primary btn-flat m-b-15']); ?>

<div class="box box-success">
    <div class="box-body">
        <?
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'attribute' => 'id',
                    'format' => 'integer',
                    'headerOptions' => [
                        'width' => 80,
                        'class' => 'text-center'
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ]
                ],
                [
                    'label' => 'Фото',
                    'format' => 'html',
                    'headerOptions' => [
                        'width' => 50,
                        'class' => 'text-center'
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ],
                    'value' => function(Camps $model){
                        return Html::a(
                            Html::img(UploadForm::getSrc($model->media->photo_main, UploadForm::TYPE_CAMP, '_xs')),
                            UploadForm::getSrc($model->media->photo_main), [
                                'title' => Html::encode($model->about->name_full),
                                'class' => 'a-slider',
                                'encode' => false
                            ]);
                    },
                ],
                [
                    'attribute' => 'about_loc_country',
                    'filter' => \app\models\LocCountries::getFilterList(true),
                    'headerOptions' => [
                        'class' => 'text-left',
                    ],
                    'contentOptions' => [
                        'class' => 'text-left'
                    ],
                    'value' => function(Camps $model){
                        return $model->about->country->name;
                    },
                ],
                [
                    'attribute' => 'about_loc_region',
                    'filter' => \app\models\LocRegions::getFilterList($searchModel->about_loc_country, true),
                    'headerOptions' => [
                        'class' => 'text-left',
                    ],
                    'contentOptions' => [
                        'class' => 'text-left'
                    ],
                    'value' => function(Camps $model){
                        return $model->about->region->name;
                    },
                ],
                [
                    'attribute' => 'about_loc_city',
                    'filter' => \app\models\LocCities::getFilterList($searchModel->about_loc_region, true),
                    'headerOptions' => [
                        'class' => 'text-left',
                    ],
                    'contentOptions' => [
                        'class' => 'text-left'
                    ],
                    'value' => function(Camps $model){
                        return $model->about->city->name;
                    },
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'about_name_short',
                    'headerOptions' => [
                        'class' => 'text-left',
                    ],
                    'contentOptions' => [
                        'class' => 'text-left'
                    ],
                    'value' => function(Camps $model){
                        return Html::encode($model->about->name_short) . '<br/>' . $model->about->name_full
                        . '<br/>' . ($model->incamp_url ? Html::a('Смотреть на Incamp', "//incamp.ru{$model->incamp_url}", ['target' => '_blank']) : '');
                    },
                ],
                [
                    'label' => 'Контакты директора',
                    'attribute' => 'contacts_boss',
                    'format' => 'ntext',
                    'headerOptions' => [
                        'class' => 'text-left',
                    ],
                    'contentOptions' => [
                        'class' => 'text-left'
                    ],
                    'value' => function(Camps $model){
                        return implode(PHP_EOL, [$model->contacts->boss_fio, $model->contacts->boss_phone, $model->contacts->boss_email]);
                    },
                ],
                [
                    'attribute' => 'status',
                    'format' => 'html',
                    'filter' => Statuses::statuses(),
                    'value' => function(Camps $model){
                        return Statuses::getFull($model->status);
                    },
                    'headerOptions' => [
                        'width' => 120,
                        'class' => 'text-center'
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ]
                ],
                [
                    'attribute' => 'created',
                    'format' => 'html',
                    'filter' => false,
                    'value' => function(Camps $model){
                        return Normalize::getFullDateByTime($model->created, '<br/>');
                    },
                    'headerOptions' => [
                        'width' => 100,
                        'class' => 'text-center'
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ]
                ],
                [
                    'header' => 'Действия',
                    'format' => 'raw',
                    'value' => function($model) {
                        return ManageList::get($model, ['show', 'edit', 'delete']);
                    },
                    'headerOptions' => [
                        'width' => 120,
                        'class' => 'text-center'
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ]
                ]
            ],
        ]);
        ?>
    </div>
</div>