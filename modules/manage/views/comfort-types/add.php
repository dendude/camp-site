<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/** @var $model \app\models\ComfortTypes */
/** @var $this \yii\web\View */

$action = $model->id ? 'Редактирование услуги' : 'Добавление услуги';
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => \app\modules\manage\controllers\ComfortTypesController::LIST_NAME, 'url' => ['list']],
    ['label' => $action]
];

$w100 = ['inputOptions' => ['class' => 'form-control w-100']];
$w200 = ['inputOptions' => ['class' => 'form-control w-200']];

$form = ActiveForm::begin();
?>
<div class="max-width-1000">
    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
    <? \app\helpers\MHtml::alertMsg(); ?>
    <div class="box box-primary">
        <div class="box-header with-border"><?= Yii::$app->params['required_fields'] ?></div>
        <div class="box-body">
            <?= $form->field($model, 'title') ?>
            <?= \app\helpers\MHtml::aliasField($model, 'alias', 'alias') ?>
            <div class="row row-comment">
                <div class="col-xs-offset-4 col-xs-8">
                    Пример: вводим "справочник/статья1", клик "Получить URL" покажет "spravochnik/statya1".<br/>
                    После сохранения ссылка на страницу будет такой: "spravochnik/statya1.html".
                </div>
            </div>
    
            <div class="separator"></div>
            
            <?= $form->field($model, 'icon', $w200)->textInput(['placeholder' => 'fa-user']) ?>
            <div class="row row-comment">
                <div class="col-xs-12 col-md-offset-4 col-md-8">
                    Выбрать подходящую иконку можно по <a href="/lib/AdminLTE/pages/UI/icons.html" target="_blank">этой ссылке</a>.
                    <br>
                    Например, чтобы вставить иконку пользователя, нужно ввести <code>fa-user</code>
                </div>
            </div>
            <div class="separator"></div>
            <?= $form->field($model, 'ordering', $w100)->input('number') ?>
            <div class="separator"></div>
            <?= $form->field($model, 'status')->checkbox(['class' => 'ichecks', 'label' => 'Опубликовать на сайте']) ?>
            <div class="separator"></div>
    
            <div class="form-group">
                <div class="col-xs-offset-4 col-xs-2">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            
            <?= $form->field($model, 'content', ['template' => '<div class="col-xs-4 text-right">{label}</div><div class="col-xs-8">{error}</div>']) ?>
            <?= \app\modules\manage\widgets\FroalaEditorWidget::widget([
                'model' => $model,
                'field' => 'content',
        
                'imageUploadUrl' => Url::to(['upload-image']),
                'filesUploadUrl' => Url::to(['upload-files']),
                'filesManagerUrl' => Url::to(['files-manager']),
            ]); ?>

            <div class="form-group">
                <div class="col-xs-offset-4 col-xs-2">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>