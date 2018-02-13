<?php

use yii\grid\GridView;
use yii\helpers\Html;
use app\helpers\Statuses;
use app\helpers\Normalize;
use app\helpers\MHtml;
use app\helpers\ManageList;
use app\modules\manage\controllers\LocRegionsController;
use app\modules\manage\controllers\LocCountriesController;
use app\models\Users;
use app\modules\manage\controllers\LocCitiesController;

/** @var $dataProvider \yii\data\DataProviderInterface */
/** @var $searchModel \app\models\LocCities */

$action = LocCitiesController::LIST_NAME;
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => LocCountriesController::LIST_NAME, 'url' => ['loc-countries/list']],
    ['label' => LocRegionsController::LIST_NAME, 'url' => ['loc-regions/list']],
    ['label' => $action]
];
?>
<?= MHtml::alertMsg(); ?>
<?= Html::a('Добавить', ['add'], ['class' => 'btn btn-primary btn-flat m-b-15']); ?>

<div class="box box-success">
    <div class="box-body">
    <?= GridView::widget([
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
                    'attribute' => 'manager_id',
                    'format' => 'text',
                    'filter' => Users::getManagersFilter(),
                    'value' => function($model){
                        return Users::getStaticManagerName($model->manager_id);
                    },
                    'headerOptions' => [
                        'class' => 'text-center',
                        'width' => 200
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ],
                ],
                [
                    'attribute' => 'country_id',
                    'format' => 'text',
                    'filter' => \app\models\LocCountries::getFilterList(),
                    'value' => function($model){
                        return $model->country->name;
                    },
                    'headerOptions' => [
                        'class' => 'text-center',
                        'width' => 200
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ],
                ],
                [
                    'attribute' => 'region_id',
                    'format' => 'text',
                    'filter' => \app\models\LocRegions::getFilterList($searchModel->country_id),
                    'value' => function($model){
                        return $model->region->name;
                    },
                    'headerOptions' => [
                        'class' => 'text-center',
                        'width' => 200
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ],
                ],
                [
                    'attribute' => 'name',
                    'headerOptions' => [
                        'class' => 'text-left'
                    ],
                ],
                [
                    'attribute' => 'alias',
                    'headerOptions' => [
                        'class' => 'text-left'
                    ],
                ],
                [
                    'attribute' => 'ordering',
                    'headerOptions' => [
                        'class' => 'text-center',
                        'width' => 80
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ],
                ],
                [
                    'attribute' => 'status',
                    'format' => 'html',
                    'filter' => Statuses::statuses(),
                    'value' => function($model){
                        return Statuses::getFull($model->status);
                    },
                    'headerOptions' => [
                        'width' => 100,
                        'class' => 'text-center'
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ]
                ],
                /*[
                    'attribute' => 'created',
                    'format' => 'html',
                    'filter' => false,
                    'value' => function($model){
                        return Normalize::getFullDateByTime($model->created, '<br/>');
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
                    'attribute' => 'modified',
                    'format' => 'html',
                    'filter' => false,
                    'value' => function($model){
                        return Normalize::getFullDateByTime($model->modified, '<br/>');
                    },
                    'headerOptions' => [
                        'width' => 120,
                        'class' => 'text-center'
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ]
                ],*/
                [
                    'header' => 'Действия',
                    'format' => 'raw',
                    'value' => function($model) {
                        return ManageList::get($model);
                    },
                    'headerOptions' => [
                        'width' => 100,
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