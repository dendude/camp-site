<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\manage\controllers\CampsPlacesController;
use \app\models\TagsPlaces;

/** @var $model TagsPlaces */
/** @var $this \yii\web\View */

$action = $model->id ? 'Редактирование объекта' : 'Создание объекта';
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => CampsPlacesController::LIST_NAME, 'url' => ['list']],
    ['label' => $action]
];

$form = ActiveForm::begin();
echo Html::activeHiddenInput($model, 'id');

$w100 = ['inputOptions' => ['class' => 'form-control w-100']];
$w200 = ['inputOptions' => ['class' => 'form-control w-200']];

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
            <?= $form->field($model, 'title') ?>
            <?= \app\helpers\MHtml::aliasField($model, 'alias', 'alias') ?>
            <div class="row row-comment">
                <div class="col-xs-offset-4 col-xs-8">
                    Пример: вводим "стадион", клик "Получить URL" покажет "stadion".<br/>
                    После сохранения ссылка на страницу будет такой: "stadion.html".
                </div>
            </div>
    
            <!--<div class="separator"></div>
    
            <?/*= $form->field($model, 'icon', $w200) */?>
            <div class="row row-comment">
                <div class="col-xs-offset-4 col-xs-8">
                    Пример: user, на выходе получим fa fa-user. <br/>
                    Доступные иконки для выбора <a href="/lib/AdminLTE/pages/UI/icons.html" target="_blank">смотреть здесь</a>
                </div>
            </div>-->

            <div class="separator"></div>
            
            <?= $form->field($model, 'meta_t')->textarea(['onkeyup' => 'charsCalculate(this)', 'maxlength' => true]) ?>
            <?= $form->field($model, 'meta_d')->textarea(['onkeyup' => 'charsCalculate(this)', 'maxlength' => true]) ?>
            <?= $form->field($model, 'meta_k')->textarea(['onkeyup' => 'charsCalculate(this)', 'maxlength' => true]) ?>
                
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
        </div>
    </div>
</div>
<input type="hidden" value="<?= Yii::$app->request->referrer ?>" name="ref-page"/>
<?php ActiveForm::end() ?>