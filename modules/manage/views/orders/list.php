<?php

use yii\grid\GridView;
use yii\helpers\Html;
use app\helpers\Statuses;
use app\models\Users;
use app\helpers\Normalize;
use app\helpers\MHtml;
use app\helpers\ManageList;
use app\models\Orders;
use app\models\search\OrdersSearch;

/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\DataProviderInterface
 * @var $searchModel \yii\base\Model
 */

$action = \app\modules\manage\controllers\OrdersController::LIST_NAME;
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => $action]
];
?>
<?= MHtml::alertMsg(); ?>

<div class="row m-b-15">
    <div class="col-xs-12 col-sm-4 text-left">
        <?= Html::a('Добавить', ['add'], ['class' => 'btn btn-primary btn-flat']); ?>
    </div>
    <div class="col-xs-12 col-sm-8">
        <div class="pull-right">
            <?= Html::beginForm(['process'], 'GET', ['target' => '_blank']); ?>
            <div class="input-group">
                <?= Html::activeTextInput($searchModel, 'date_from', [
                    'class' => 'form-control w-150 datepickers',
                    'placeholder' => 'Дата с',
                ]); ?>
                <?= Html::activeTextInput($searchModel, 'date_to', [
                    'class' => 'form-control w-150 datepickers',
                    'placeholder' => 'Дата по',
                ]); ?>
                <div class="btn-group">
                    <?= Html::activeDropDownList($searchModel, 'doc_type', OrdersSearch::getDocTypes(), [
                        'class' => 'form-control',
                    ]); ?>
                </div>
                <div class="input-group-btn">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        Действие<span class="caret m-l-5"></span>
                    </button>
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <a href="#" onclick="OrdersProc.run(this)"
                               data-type="<?= OrdersSearch::PROC_DOWNLOAD ?>"
                               data-field="<?= Html::getInputId($searchModel, 'proc_type') ?>">
                                <?= OrdersSearch::getProcTypeName(OrdersSearch::PROC_DOWNLOAD) ?>
                            </a>
                        </li>
                        <li>
                            <a href="#" onclick="OrdersProc.run(this)"
                               data-type="<?= OrdersSearch::PROC_SENDMAIL ?>"
                               data-field="<?= Html::getInputId($searchModel, 'proc_type') ?>">
                                <?= OrdersSearch::getProcTypeName(OrdersSearch::PROC_SENDMAIL) ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <?= Html::activeHiddenInput($searchModel, 'id'); ?>
            <?= Html::activeHiddenInput($searchModel, 'manager_id'); ?>
            <?= Html::activeHiddenInput($searchModel, 'camp_id'); ?>
            <?= Html::activeHiddenInput($searchModel, 'details'); ?>
            <?= Html::activeHiddenInput($searchModel, 'price_user'); ?>
            <?= Html::activeHiddenInput($searchModel, 'status'); ?>
            <?= Html::activeHiddenInput($searchModel, 'proc_type'); ?>
            
            <?= Html::endForm(); ?>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

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
                    'attribute' => 'camp_id',
                    'format' => 'ntext',
                    //'filter' => \app\models\Camps::getFilterList(),
                    'value' => function(Orders $model){
                        return $model->camp->getFullName();
                    },
                    'headerOptions' => [
                        'class' => 'text-left',
                    ],
                    'contentOptions' => [
                        'class' => 'text-left'
                    ],
                ],
                [
                    'attribute' => 'details',
                    'format' => 'ntext',
                    'value' => function(Orders $model){
                        return implode(PHP_EOL, $model->getOrderData());
                    },
                    'headerOptions' => [
                        'class' => 'text-left',
                    ],
                    'contentOptions' => [
                        'class' => 'text-left'
                    ],
                ],
                [
                    'label' => 'Сумма',
                    'attribute' => 'price_user',
                    'headerOptions' => [
                        'class' => 'text-center',
                        'width' => 120,
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ],
                    'value' => function(Orders $model){
                        return $model->price_user . ' ' . $model->currency;
                    },
                ],
                [
                    'attribute' => 'status',
                    'format' => 'html',
                    'filter' => Statuses::statuses(Statuses::TYPE_ORDER),
                    'value' => function($model){
                        return Statuses::getFull($model->status, Statuses::TYPE_ORDER);
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