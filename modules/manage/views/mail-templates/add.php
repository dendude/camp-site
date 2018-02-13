<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\manage\controllers\MailTemplatesController;
use yii\helpers\Url;

/** @var $model \app\models\EmailTemplates */

$this->title = $model->id ? 'Редактирование шаблона' : 'Добавление шаблона';
$this->params['breadcrumbs'] = [
    ['label' => MailTemplatesController::LIST_TEMPLATES, 'url' => ['list']],
    ['label' => $this->title]
];

$form = ActiveForm::begin();
?>
<div class="max-width-1200">
    <?= \app\helpers\MHtml::alertMsg(); ?>
    
    <div class="box box-primary">
        <div class="box-header with-border"><?= Yii::$app->params['required_fields'] ?></div>
        <div class="box-body">
            <?= $form->field($model, 'subject') ?>
            <div class="separator"></div>
    
            <?= $form->field($model, 'content', ['template' => '<div class="col-xs-4 text-right">{label}</div><div class="col-xs-8">{error}</div>']) ?>
            <?= \app\modules\manage\widgets\FroalaEditorWidget::widget([
                'model' => $model,
                'field' => 'content',
        
                'imageUploadUrl' => Url::to(['upload-image']),
                'filesUploadUrl' => Url::to(['upload-files']),
                'filesManagerUrl' => Url::to(['files-manager']),
            ]); ?>
            
            <div class="separator"></div>
            <div class="separator"></div>
            <div class="form-group">
                <div class="col-xs-offset-4 col-xs-2">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary btn-flat']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>