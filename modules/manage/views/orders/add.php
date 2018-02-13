<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\manage\controllers\OrdersController;
use \app\models\Orders;
use app\helpers\Statuses;
use app\models\BaseItems;
use app\helpers\Normalize;
use yii\helpers\Url;

/** @var $model Orders */
/** @var $this \yii\web\View */

$action = $model->id ? 'Редактирование брони' : 'Добавление брони';
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => OrdersController::LIST_NAME, 'url' => ['list']],
    ['label' => $action]
];

$w100 = ['inputOptions' => ['class' => 'form-control w-100']];
$w200 = ['inputOptions' => ['class' => 'form-control w-200']];
$dtpk = ['inputOptions' => ['class' => 'form-control w-150 datepickers']];

$price_tpl = str_replace(['{class}','{addon}'], ['w-200', $model->currency], Yii::$app->params['group_template']);
$price_partner_tpl = str_replace(['{class}','{addon}'], ['w-200', $model->currency_partner], Yii::$app->params['group_template']);

$price_template = ['template' => $price_tpl];
$price_partner_template = ['template' => $price_partner_tpl];

if ($model->id) {
    // для показа кол-ва символов у редактируемой страницы
    $this->registerJs("$('textarea').keyup();");
}

$form = ActiveForm::begin();
echo Html::activeHiddenInput($model, 'id');
?>
<div class="max-width-1000">
    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
    <? \app\helpers\MHtml::alertMsg(); ?>
    <div class="box box-primary">
        <div class="box-header with-border"><?= Yii::$app->params['required_fields'] ?></div>
        <div class="box-body">
    
            <div class="form-group">
                <div class="col-xs-12 col-md-offset-4 col-md-7">
                    <p class="form-control-static">
                        <strong class="text-success">Данные заказа</strong>
                    </p>
                </div>
            </div>
    
            <? if ($model->id): ?>
                <? foreach ($model->order_data['child_fio'] AS $k => $v): ?>
                    <?= $form->field($model, 'child_fio[]')->textInput([
                        'value' => $model->order_data['child_fio'][$k],
                    ])->label($model->getAttributeLabel('child_fio') . ' ' . ($k+1)) ?>
                    <?= $form->field($model, 'child_birth[]', $dtpk)->textInput([
                        'value' => $model->order_data['child_birth'][$k]
                    ])->label($model->getAttributeLabel('child_birth') . ' ' . ($k+1)) ?>
                    <div class="separator"></div>
                <? endforeach; ?>
            <? else: ?>
                <?= $form->field($model, 'child_birth[]', $dtpk) ?>
                <?= $form->field($model, 'child_fio[]') ?>
            <? endif; ?>
    
            <div class="separator"></div>
    
            <?= $form->field($model, 'client_fio')->textInput([
                'value' => $model->order_data['client_fio']
            ])->label('ФИО заказчика') ?>
    
            <?= $form->field($model, 'client_email', $w200)->textInput([
                'value' => $model->order_data['client_email']
            ])->label('Email заказчика') ?>
    
            <?= $form->field($model, 'client_phone', $w200)->textInput([
                'value' => Normalize::formatPhone($model->order_data['client_phone'])
            ])->label('Номер телефона заказчика') ?>
    
            <?= $form->field($model, 'client_comment')->textarea([
                'value' => $model->order_data['client_comment']
            ])->label('Комментарий заказчика') ?>
    
            <div class="line-separator m-t-20 m-b-20"></div>
            
            <div class="form-group">
                <div class="col-xs-12 col-md-offset-4 col-md-7">
                    <p class="form-control-static">
                        <strong class="text-success">Данные выбранной смены</strong>
                    </p>
                </div>
            </div>
            
            <? if ($model->isNewRecord): ?>
                <div class="camp-camp-id-block">
                    <?= $form->field($model, 'camp_id')->dropDownList(\app\models\Camps::getFilterList(), [
                        'prompt' => ''
                    ]) ?>
                </div>
            <? else: ?>
                <div class="form-group">
                    <div class="col-xs-12 col-md-4 text-right">
                        <label for="" class="control-label">Организатор</label>
                    </div>
                    <div class="col-xs-12 col-md-7">
                        <p class="form-control-static">
                            <strong><?= Html::encode($model->camp->about->name_full) ?></strong>
                        </p>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12 col-md-4 text-right">
                        <label for="" class="control-label"><?= $model->getAttributeLabel('camp_id') ?></label>
                    </div>
                    <div class="col-xs-12 col-md-7">
                        <p class="form-control-static">
                            <strong><?= Html::encode($model->camp->about->name_short) ?></strong>
                        </p>
                    </div>
                </div>
            <? endif; ?>
            
            <div class="camp-item-id-block">
                <?= $form->field($model, 'item_id')->dropDownList(BaseItems::getFilterListOrder($model->camp_id, true), [
                    'onchange' => 'set_price_data(this)'
                ]) ?>
            </div>
    
            <div class="separator"></div>
    
            <?= $form->field($model, 'price_partner', $price_partner_template)->textInput(['readonly' => true]) ?>
            <?= $form->field($model, 'price_user', $price_template) ?>
            <?= $form->field($model, 'price_payed', $price_template) ?>
            <div class="form-group">
                <div class="col-xs-12 col-md-4 text-right">
                    <label for="" class="control-label"><?= $model->getAttributeLabel('trans_in_price') ?></label>
                </div>
                <div class="col-xs-12 col-md-7">
                    <p class="form-control-static">
                        <strong class="trans-in-price-block"><?= Statuses::getFull($model->trans_in_price, Statuses::TYPE_YESNO) ?></strong>
                    </p>
                </div>
            </div>
                
            <div class="separator"></div>
    
            <?= $form->field($model, 'status', $w200)->dropDownList(Statuses::statuses(Statuses::TYPE_ORDER)) ?>
            
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
<?php
$this->registerJs("
$('#" . Html::getInputId($model, 'camp_id') . "').on('change', function(){
    var \$obj = $(this);
    
    ajaxData('.camp-camp-id-block', '#" . Html::getInputId($model, 'item_id') . "', '" . Url::to(['/ajax/options']) . "', {
        type: 'camp_items',
        id: \$obj.val()
    });
});
");
?>
<script>
    function set_price_data(obj) {
        var $obj = $(obj);
        
        ajaxData('.camp-item-id-block', null, '<?= Url::to(['/ajax/price']) ?>', {
            type: 'camp_item_prices',
            id: $obj.val()
        }, function(resp){
            $('#<?= Html::getInputId($model, 'price_partner') ?>').val(resp.price_partner);
            $('#<?= Html::getInputId($model, 'price_user') ?>').val(resp.price_user);
            $('.trans-in-price-block').html(resp.trans_in_price);
        });
    }
</script>