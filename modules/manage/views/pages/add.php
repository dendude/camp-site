<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\manage\controllers\PagesController;
use \app\models\Pages;
use yii\helpers\Url;

/** @var $model Pages */
/** @var $this \yii\web\View */

$action = $model->id ? 'Редактирование страницы' : 'Создание страницы';
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => PagesController::LIST_NAME, 'url' => ['list']],
    ['label' => $action]
];

$form = ActiveForm::begin();
echo Html::activeHiddenInput($model, 'id');

$inputSmall = ['inputOptions' => ['class' => 'form-control input-small']];
$inputMiddle = ['inputOptions' => ['class' => 'form-control input-middle']];

if ($model->id) {
    // для показа кол-ва символов у редактируемой страницы
    $this->registerJs("$('textarea').keyup();");
}
?>
<div class="max-width-1200">
    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
    <?= \app\helpers\MHtml::alertMsg(); ?>
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
            
            <!--<div class="separator"></div>
            
            <?/*= $form->field($model, 'crumbs_real') */?>
            --><?/*= $form->field($model, 'crumbs_verb') */?>
            
            <div class="separator"></div>
            
            <?= $form->field($model, 'is_sitemap')->checkbox(['label' => 'Добавить страницу в карту сайта', 'class' => 'ichecks']) ?>
            <?= $form->field($model, 'is_auto')->checkbox(['label' => 'Автоматическая страница или страница-форма', 'class' => 'ichecks']) ?>
            <div class="row row-comment">
                <div class="col-xs-offset-4 col-xs-8">Для автоматических страниц контент закладывается разработчиком</div>
            </div>

            <div class="separator"></div>
            
            <?= $form->field($model, 'meta_t')->textarea(['onkeyup' => 'charsCalculate(this)', 'maxlength' => true]) ?>
            <?= $form->field($model, 'meta_d')->textarea(['onkeyup' => 'charsCalculate(this)', 'maxlength' => true]) ?>
            <?= $form->field($model, 'meta_k')->textarea(['onkeyup' => 'charsCalculate(this)', 'maxlength' => true]) ?>
            
            <div class="separator"></div>

            <div class="form-group">
                <div class="col-xs-offset-4 col-xs-2">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
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
<input type="hidden" value="<?= Yii::$app->request->referrer ?>" name="ref-page"/>
<?php ActiveForm::end() ?>