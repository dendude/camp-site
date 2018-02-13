<?
use yii\grid\GridView;
use app\models\Orders;
use app\helpers\Statuses;
use app\helpers\Normalize;
use app\helpers\ManageList;
use app\models\Reviews;
use yii\helpers\Html;

/** @var $dataProvider \yii\data\DataProviderInterface */
/** @var $searchModel \yii\base\Model */

$this->title = \app\modules\partner\controllers\ReviewsController::LIST_NAME;
$this->params['breadcrumbs'] = [$this->title];

echo GridView::widget([
    'tableOptions' => Yii::$app->params['officeGridTableOptions'],
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'attribute' => 'id',
            'format' => 'integer',
            'headerOptions' => [
                'width' => 60,
                'class' => 'text-center'
            ],
            'contentOptions' => [
                'class' => 'text-center'
            ]
        ],
        [
            'attribute' => 'base_id',
            'format' => 'raw',
            'value' => function(Reviews $model){
                return Html::a(Html::encode($model->camp->about->name_short), $model->camp->getCampUrl(), ['target' => '_blank'])
                . '<br/>' . Html::tag('small', "[{$model->camp->about->country->name}/{$model->camp->about->region->name}]", ['class' => 'text-muted']);
            },
            'headerOptions' => [
                'class' => 'text-left',
            ],
            'contentOptions' => [
                'class' => 'text-left'
            ],
        ],
        [
            'label' => 'Автор отзыва',
            'attribute' => 'user_email',
            'headerOptions' => [
                'class' => 'text-left',
            ],
            'format' => 'html',
            'value' => function(Reviews $model){
                return Html::tag('small', Html::encode($model->user_name) . '<br/>' . Html::encode($model->user_email));
            },
        ],
        [
            'attribute' => 'stars',
            'headerOptions' => [
                'class' => 'text-center',
                'width' => 120,
            ],
            'contentOptions' => [
                'class' => 'text-center'
            ],
        ],
        [
            'attribute' => 'status',
            'format' => 'html',
            'filter' => Statuses::statuses(Statuses::TYPE_REVIEW_OFFICE),
            'value' => function($model){
                return Statuses::getFull($model->status, Statuses::TYPE_REVIEW_OFFICE);
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
                return ManageList::get($model, ['show', 'cancel']);
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