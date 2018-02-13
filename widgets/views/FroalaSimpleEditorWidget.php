<?php
use yii\helpers\Html;
/**
 * @var \yii\base\Model $model
 * @var string $field
 * @var string $type
 * @var array $params
 */

$editor = \froala\froalaeditor\FroalaEditorWidget::widget([
    'model' => $model,
    'attribute' => $field,
    'clientOptions'=> array_merge([
        'toolbarInline'=> false,
        'theme' => 'default', // optional: dark, red, gray, royal
        'language' => 'ru',
        
        'height' => false,
        'fullPage' => false,
        'heightMin' => 200,
        'heightMax' => 600,
    ], $params)
]);
?>
<div class="form-group field-<?= Html::getInputId($model, $field) ?> <? if ($model->isAttributeRequired($field)): ?>required<? endif; ?>">
    <?= Html::activeLabel($model, $field, ['class' => 'control-label col-xs-4']) ?>
    <div class="col-xs-12 col-md-8">
        <? if ($type == \app\widgets\FroalaSimpleEditorWidget::TYPE_CELL): ?>
            <?= $editor; ?>
            <div class="m-t-10 p-l-20">
                <ul>
                    <li><strong>Enter</strong> - перенос строки с отступом (новый параграф);</li>
                    <li><strong>Shift+Enter</strong> - перенос без отступа (обычный перенос строки);</li>
                </ul>
            </div>
        <? endif; ?>
        <div class="form-control-static">
            <div class="help-block m-none"></div>
        </div>
    </div>
    <? if ($type == \app\widgets\FroalaSimpleEditorWidget::TYPE_ROW): ?>
    <div class="col-xs-12 m-t-10">
        <?= $editor ?>
        <div class="m-t-10 p-l-20">
            <ul>
                <li><strong>Enter</strong> - перенос строки с отступом (новый параграф);</li>
                <li><strong>Shift+Enter</strong> - перенос без отступа (обычный перенос строки);</li>
            </ul>
        </div>
    </div>
    <? endif; ?>
</div>
<?php
$this->registerJs("
    $(document).ready(function(){
        $('a[href*=\"froala.com\"]').closest('div').remove();
    });
");