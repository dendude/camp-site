<?
use yii\helpers\Html;
use app\models\Orders;
use app\helpers\Statuses;

/** @var $this \yii\web\View */
/** @var $model_item \app\models\BaseItems */
/** @var $class $string */

$help_block = Html::tag('div', '', ['class' => 'help-block small m-none']);
?>
<tr class="<?= $class ?>">
    <td>
        <?= Html::activeHiddenInput($model_item, '[{index}]id') ?>
        
        <div class="form-group-item m-b-5">
            <?= Html::activeTextInput($model_item, '[{index}]name_short', [
                'class' => 'form-control input-sm',
                'maxlength' => true, 'placeholder' => 'Короткое: 1 смена',
            ]); ?>
            <?= $help_block ?>
        </div>
        <div class="form-group-item">
            <?= Html::activeTextInput($model_item, '[{index}]name_full', [
                'class' => 'form-control input-sm',
                'maxlength' => true, 'placeholder' => 'Полное: 1 смена для любителей велоспорта',
            ]); ?>
            <?= $help_block ?>
        </div>
    </td>
    <td>
        <div class="form-group-item m-b-5">
            <?= Html::activeHiddenInput($model_item, '[{index}]date_from') ?>
            <?= Html::activeTextInput($model_item, '[{index}]date_from_orig', [
                'class' => 'form-control input-sm datepickers date-from-date',
                'placeholder' => 'Дата с'
            ]) ?>
            <?= $help_block ?>
        </div>
        <div class="form-group-item">
            <?= Html::activeHiddenInput($model_item, '[{index}]date_to') ?>
            <?= Html::activeTextInput($model_item, '[{index}]date_to_orig', [
                'class' => 'form-control input-sm datepickers date-to-date',
                'placeholder' => 'Дата по'
            ]) ?>
            <?= $help_block ?>
        </div>
    </td>
    <td>
        <div class="form-group-item m-b-5">
            <div class="input-group input-group-sm">
                <?= Html::activeTextInput($model_item, '[{index}]partner_amount', [
                    'type' => 'number',
                    'min' => 1,
                    'class' => 'form-control',
                    'maxlength' => true,
                    'placeholder' => 'Кол-во'
                ]) ?>
                <span class="input-group-addon">шт.</span>
            </div>
            <?= $help_block ?>
        </div>
        <div class="form-group-item">
            <div class="input-group input-group-sm">
                <?= Html::activeTextInput($model_item, '[{index}]partner_price', [
                    'class' => 'form-control',
                    'maxlength' => true,
                    'placeholder' => 'Цена'
                ]) ?>
                <span class="input-group-addon items-currency-txt"><?= $model_item->getCurrentCurrency() ?></span>
                <?= Html::hiddenInput(
                    Html::getInputName($model_item, '[{index}]currency'),
                    $model_item->getCurrentCurrency(),
                    ['class' => 'items-currency-val']
                ); ?>
            </div>
            <?= $help_block ?>
        </div>
    </td>
    <td class="text-center">
        <button type="button" class="btn btn-sm btn-success btn-add-items" title="Добавить смену">
            <i class="glyphicon glyphicon-plus"></i>
        </button>
        <button type="button" class="btn btn-sm btn-danger btn-delete-items" title="Удалить смену">
            <i class="glyphicon glyphicon-trash"></i>
        </button>
    </td>
</tr>