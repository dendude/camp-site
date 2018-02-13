<?
use yii\grid\GridView;
use app\models\Orders;
use app\helpers\Statuses;
use app\helpers\Normalize;
use app\helpers\ManageList;
use yii\helpers\Html;
use app\models\Camps;
use app\helpers\MHtml;
use app\models\forms\UploadForm;
use app\models\Pages;

/** @var $dataProvider \yii\data\DataProviderInterface */
/** @var $searchModel \yii\base\Model */

$this->title = \app\modules\partner\controllers\CampsController::LIST_NAME;
$this->params['breadcrumbs'] = [$this->title];
?>
<div class="clearfix"></div>
<?= MHtml::alertMsg(); ?>
<div class="pull-left m-b-10">
    <?= Html::a('Добавить', Pages::getUrlById(Pages::PAGE_CAMP_REGISTER_ID), ['class' => 'btn btn-primary']); ?>
</div>

<?
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
                return Html::a(Html::img(UploadForm::getSrc($model->media->photo_main, UploadForm::TYPE_CAMP, '_xs')), UploadForm::getSrc($model->media->photo_main), [
                    'class' => 'a-slider', 'title' => Html::encode($model->about->name_short)
                ]);
            },
        ],
        [
            'label' => 'Название лагеря и расположение',
            'attribute' => 'about_name_short',
            'format' => 'html',
            'headerOptions' => [
                'class' => 'text-left',
            ],
            'contentOptions' => [
                'class' => 'text-left'
            ],
            'value' => function(Camps $model){
                return Html::encode($model->about->name_short)
                    . '<br/>' . Html::tag('small', Html::encode($model->about->country->name . ' ' . $model->about->region->name), ['class' => 'text-muted']);
            },
        ],
        [
            'label' => 'Ответственный',
            'attribute' => 'contacts_worker',
            'format' => 'ntext',
            'headerOptions' => [
                'class' => 'text-left',
                'width' => 100,
            ],
            'value' => function(Camps $model){
                return $model->contacts->worker_fio
                . PHP_EOL . $model->contacts->worker_email
                . PHP_EOL . $model->contacts->worker_phone;
            },
        ],
        [
            'attribute' => 'status',
            'format' => 'html',
            'filter' => Statuses::statuses(Statuses::TYPE_CAMP),
            'value' => function($model){
                return Statuses::getFull($model->status, Statuses::TYPE_CAMP);
            },
            'headerOptions' => [
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
                'width' => 120,
                'class' => 'text-center'
            ],
            'contentOptions' => [
                'class' => 'text-center'
            ]
        ]
    ],
]);