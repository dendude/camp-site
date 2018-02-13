<?php
use yii\grid\GridView;
use yii\helpers\Html;
use app\models\Users;
use app\helpers\Normalize;
use app\helpers\MHtml;
use app\helpers\ManageList;
use app\models\Icons;
use app\models\forms\UploadForm;

/** @var $dataProvider \yii\data\DataProviderInterface */
/** @var $searchModel \yii\base\Model */

$action = \app\modules\manage\controllers\CampsIconsController::LIST_NAME;
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
                    'attribute' => 'photo',
                    'format' => 'html',
                    'value' => function(Icons $model){
                        return Html::a(
                            Html::img(UploadForm::getSrc($model->photo, UploadForm::TYPE_PAGES, '_xs')),
                            UploadForm::getSrc($model->photo, UploadForm::TYPE_PAGES), [
                            'title' => Html::encode($model->icon_name),
                            'class' => 'a-slider',
                            'encode' => false
                        ]);
                    },
                    'headerOptions' => [
                        'class' => 'text-center',
                        'width' => 50
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ],
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
                    'attribute' => 'icon_name',
                    'headerOptions' => [
                        'class' => 'text-left',
                    ],
                    'contentOptions' => [
                        'class' => 'text-left'
                    ],
                ],
                [
                    'attribute' => 'created',
                    'format' => 'html',
                    'filter' => false,
                    'value' => function(Icons $model){
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
                    'value' => function(Icons $model) {
                        return ManageList::get($model, ['edit']);
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