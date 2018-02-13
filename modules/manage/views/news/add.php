<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\manage\controllers\NewsController;
use \app\models\News;
use yii\helpers\Url;

/** @var $model News */
/** @var $this \yii\web\View */

$action = $model->id ? 'Редактирование новости' : 'Создание новости';
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => NewsController::LIST_NAME, 'url' => ['list']],
    ['label' => $action]
];

$form = ActiveForm::begin();
echo Html::activeHiddenInput($model, 'id');

$w150 = ['inputOptions' => ['class' => 'form-control w-150']];

if ($model->id) {
    // для показа кол-ва символов у редактируемой страницы
    $this->registerJs("$('textarea').keyup();");
}
?>
<div class="max-width-1000">
    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
    <? \app\helpers\MHtml::alertMsg(); ?>
    <div class="box box-primary">
        <div class="box-header with-border"><?= Yii::$app->params['required_fields'] ?></div>
        <div class="box-body">
            
            <div class="form-group <? if ($model->isAttributeRequired('photo')): ?>required<? endif; ?>">
                <label class="control-label text-right col-xs-12 col-lg-4" for="<?= Html::getInputId($model, 'photo') ?>">
                    <?= $model->getAttributeLabel('photo') ?>
                </label>
                <div class="col-xs-12 col-lg-7">
                    <?= \app\modules\manage\widgets\DropZoneWidget::widget([
                        'zone_id' => 'drop_zone',
                        'model' => $model,
                        'field' => 'photo',
                        'url' => \yii\helpers\Url::to(['upload']),
                    ]) ?>
                </div>
            </div>
            
            <div class="separator"></div>
            
            <?= $form->field($model, 'title') ?>
            <?= \app\helpers\MHtml::aliasField($model, 'alias', 'alias') ?>
            <div class="row row-comment">
                <div class="col-xs-offset-4 col-xs-8">
                    Пример: вводим "хорошая/новость", клик "Получить URL" покажет "horoshaya/novost".<br/>
                    После сохранения ссылка на страницу будет такой: "horoshaya/novost.html".
                </div>
            </div>

            <div class="separator"></div>
            <?= $form->field($model, 'meta_t')->textarea(['onkeyup' => 'charsCalculate(this)', 'maxlength' => true]) ?>
            <?= $form->field($model, 'meta_d')->textarea(['onkeyup' => 'charsCalculate(this)', 'maxlength' => true]) ?>
            <?= $form->field($model, 'meta_k')->textarea(['onkeyup' => 'charsCalculate(this)', 'maxlength' => true]) ?>
            <div class="separator"></div>
            <?= $form->field($model, 'about')->textarea() ?>
            <div class="separator"></div>
            <?= $form->field($model, 'ordering', $w150)->input('number', ['step' => 1]) ?>
            <div class="separator"></div>
            <?= $form->field($model, 'status')->checkbox(['class' => 'ichecks', 'label' => 'Опубликовать новость']) ?>
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