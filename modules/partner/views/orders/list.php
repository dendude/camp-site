<?
use yii\grid\GridView;
use app\models\Orders;
use app\helpers\Statuses;
use app\helpers\Normalize;
use app\helpers\ManageList;
use yii\helpers\Html;

/** @var $dataProvider \yii\data\DataProviderInterface */
/** @var $searchModel \yii\base\Model */

$this->title = \app\modules\partner\controllers\OrdersController::LIST_NAME;
$this->params['breadcrumbs'] = [$this->title];
?>

<div class="clearfix"></div>
<?= \app\helpers\MHtml::alertMsg(['class' => 'm-none']); ?>

<?= GridView::widget([
    'tableOptions' => Yii::$app->params['officeGridTableOptions'],
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
            'label' => 'Лагерь и смена',
            'attribute' => 'camp_id',
            'format' => 'html',
            'value' => function(Orders $model){
                return $model->camp->about->name_short
                . '<br/>' . Html::tag('small', $model->campItem->name_short . " [{$model->campItem->date_from_orig} - {$model->campItem->date_to_orig}]", ['class' => 'text-muted'])
                . '<br/>' . Html::tag('small', "{$model->camp->about->country->name}/{$model->camp->about->region->name}", ['class' => 'text-muted']);
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
                'width' => 100,
            ],
            'contentOptions' => [
                'class' => 'text-center'
            ],
            'value' => function(Orders $model){
                return $model->price_user . ' ' . $model->currency;
            },
        ],
        [
            'label' => 'Оплачено',
            'attribute' => 'price_payed',
            'headerOptions' => [
                'class' => 'text-center',
                'width' => 100,
            ],
            'contentOptions' => [
                'class' => 'text-center'
            ],
            'value' => function(Orders $model){
                return $model->price_payed . ' ' . $model->currency;
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
                return ManageList::get($model, ['show', 'edit', 'delete']);
            },
            'headerOptions' => [
                'class' => 'text-center',
                'width' => 120,
            ],
            'contentOptions' => [
                'class' => 'text-center'
            ]
        ]
    ],
]);