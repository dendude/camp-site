<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\manage\controllers\PagesController;
use \app\models\Pages;
use yii\helpers\Url;
use app\helpers\Statuses;

/** @var $model \app\models\EmailMass */
/** @var $this \yii\web\View */

$action = $model->id ? 'Редактирование рассылки' : 'Создание рассылки';
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => \app\modules\manage\controllers\MailSendsController::LIST_NAME, 'url' => ['list']],
    ['label' => $action]
];

$form = ActiveForm::begin();
echo Html::activeHiddenInput($model, 'id');

$w200 = ['inputOptions' => ['class' => 'form-control w-200']];
$dtpk = ['inputOptions' => ['class' => 'form-control w-150 datepickers-time']];
?>
<div class="max-width-1200">
    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
    <?= \app\helpers\MHtml::alertMsg(); ?>
    <div class="box box-primary">
        <div class="box-header with-border"><?= Yii::$app->params['required_fields'] ?></div>
        <div class="box-body">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'comment')->textarea(['maxlength' => true, 'placeholder' => 'Комментарий для менеджеров, например: новогодняя рассылка']) ?>
    
            <div class="separator"></div>
    
            <?= $form->field($model, 'send_date', $dtpk)->textInput(['disabled' => $model->send_now]) ?>
            <?= $form->field($model, 'send_now')->checkbox(['class' => 'ichecks']) ?>
            
            <div class="separator"></div>
            <?= $form->field($model, 'status', $w200)->dropDownList(Statuses::statuses(Statuses::TYPE_EMAIL_MASS)) ?>
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
            <div class="form-group">
                <div class="col-xs-offset-4 col-xs-2">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>

<?php
$this->registerJs("
// опция Отправить немедленно
$('#" . Html::getInputId($model, 'send_now') . "').on('ifChecked', function(){
    $('#" . Html::getInputId($model, 'send_date') . "').prop('disabled', true);
}).on('ifUnchecked', function(){
    $('#" . Html::getInputId($model, 'send_date') . "').prop('disabled', false);
});
");
