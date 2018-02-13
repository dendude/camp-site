<?php
use app\helpers\MHtml;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Users;
use app\helpers\Normalize;
use app\helpers\Statuses;
use app\helpers\ManageList;
use app\models\EmailMass;

/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $searchModel \yii\base\Model
 */

$this->title = \app\modules\manage\controllers\MailSendsController::LIST_NAME;
$this->params['breadcrumbs'] = [$this->title];
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
                    'attribute' => 'manager_id',
                    'format' => 'text',
                    'filter' => Users::getManagersFilter(),
                    'value' => function(\app\models\EmailMass $model){
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
                    'attribute' => 'title',
                    'headerOptions' => [
                        'class' => 'text-left',
                    ],
                    'contentOptions' => [
                        'class' => 'text-left'
                    ],
                ],
                [
                    'attribute' => 'comment',
                    'headerOptions' => [
                        'class' => 'text-left',
                    ],
                    'contentOptions' => [
                        'class' => 'text-left'
                    ],
                ],
                [
                    'attribute' => 'status',
                    'format' => 'html',
                    'filter' => Statuses::statuses(Statuses::TYPE_EMAIL_MASS),
                    'value' => function(EmailMass $model){
                        return Statuses::getFull($model->status, Statuses::TYPE_EMAIL_MASS);
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
                    'attribute' => 'count_sent',
                    'headerOptions' => [
                        'class' => 'text-center',
                        'width' => 120,
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ],
                    'value' => function(EmailMass $model){
                        return $model->isProcessed() ? "{$model->count_sent} / {$model->count_total}" : '';
                    },
                ],
                [
                    'attribute' => 'created',
                    'format' => 'html',
                    'filter' => false,
                    'value' => function(EmailMass $model){
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
                    'attribute' => 'send_time',
                    'format' => 'html',
                    'filter' => false,
                    'value' => function(EmailMass $model){
                        return $model->send_time ? Normalize::getFullDateByTime($model->send_time, '<br/>') : 'Немедленно';
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
                    'value' => function(EmailMass $model) {
                        return ($model->status == Statuses::STATUS_ACTIVE) ? '' : ManageList::get($model);
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