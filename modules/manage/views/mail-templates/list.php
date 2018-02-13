<?php
use yii\grid\GridView;
use yii\helpers\Html;
use app\modules\manage\controllers\MailController;
use app\modules\manage\controllers\SettingsController;
use app\models\Users;
use app\models\EmailTemplates;
use app\helpers\ManageList;
use app\modules\manage\controllers\MailTemplatesController;

/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $searchModel EmailTemplates */

$this->title = MailTemplatesController::LIST_TEMPLATES;
$this->params['breadcrumbs'] = ['label' => $this->title];
?>
<?= \app\helpers\MHtml::alertMsg(); ?>

<div class="m-b-15">
    <?= Html::a('Добавить', ['add'], ['class' => 'btn btn-primary btn-flat pull-left m-r-15']); ?>
    <?= Html::a('Подпись писем', ['mail-settings/index', 'sign' => 1], ['class' => 'btn btn-default btn-flat pull-left']); ?>
    <div class="clearfix"></div>
</div>

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
                'value' => function(EmailTemplates $model){
                    return Users::getStaticManagerName($model->manager_id);
                },
                'headerOptions' => [
                    'class' => 'text-center',
                    'width' => 250
                ],
                'contentOptions' => [
                    'class' => 'text-center'
                ],
            ],
            [
                'attribute' => 'subject',
                'headerOptions' => [
                    'class' => 'text-left'
                ],
            ],
            [
                'attribute' => 'created',
                'format' => 'html',
                'filter' => false,
                'value' => function($model){
                    return \app\helpers\Normalize::getFullDateByTime($model->created, '<br/>');
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
                    return \app\helpers\Normalize::getFullDateByTime($model->modified, '<br/>');
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