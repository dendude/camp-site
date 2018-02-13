<?
use yii\helpers\Html;
use app\models\Orders;
use app\helpers\Statuses;

/** @var $this \yii\web\View */
/** @var $model_period \app\models\BasePeriods */
/** @var $class $string */

$help_block = Html::tag('div', '', ['class' => 'help-block small m-none']);
?>
<tr class="<?= $class ?>">
    <td width="120" class="table-items">
        <?= Html::activeHiddenInput($model_period, '[{index}]id') ?>
    
        <div class="form-group-item">
            <?= Html::activeHiddenInput($model_period, '[{index}]date_from') ?>
            <?= Html::activeTextInput($model_period, '[{index}]date_from_orig', [
                'class' => 'form-control input-sm datepickers',
                'placeholder' => $model_period->getAttributeLabel('date_from_orig'),
            ]) ?>
            <?= $help_block ?>
        </div>
    </td>
    <td width="120" class="table-items">
        <div class="form-group-item">
            <?= Html::activeHiddenInput($model_period, '[{index}]date_to') ?>
            <?= Html::activeTextInput($model_period, '[{index}]date_to_orig', [
                'class' => 'form-control input-sm datepickers',
                'placeholder' => $model_period->getAttributeLabel('date_to_orig'),
            ]) ?>
            <?= $help_block ?>
        </div>
    </td>
    <td class="table-items">
        <button type="button" class="btn btn-sm btn-success btn-add-periods" title="Добавить период">
            <i class="glyphicon glyphicon-plus"></i>
        </button>
        <button type="button" class="btn btn-sm btn-danger btn-delete-periods" title="Удалить период">
            <i class="glyphicon glyphicon-trash"></i>
        </button>
    </td>
</tr>