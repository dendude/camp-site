<?
use yii\helpers\Html;
use app\models\CampsPlacement;

/** @var $this \yii\web\View */
/** @var $model_placement CampsPlacement */
/** @var $class string */
/** @var $pk integer */

$help_block = Html::tag('div', '', ['class' => 'help-block small m-none']);
$index = isset($pk) ? $pk : '{index}';
?>
<tr class="<?= $class ?>">
    <td class="table-items">
        <div class="form-group-item">
            <?= Html::activeDropDownList($model_placement, "placement_arr[{$index}][comfort_type]", CampsPlacement::getPlacementTypes(), [
                'class' => 'form-control input-sm',
            ]) ?>
            <?= $help_block ?>
        </div>
    </td>
    <td class="table-items">
        <div class="form-group-item">
            <div class="input-group">
                <?= Html::activeTextInput($model_placement, "placement_arr[{$index}][count_places]", [
                    'class' => 'form-control input-sm',
                    'placeholder' => 'Например: 3, 3-4, 5',
                ]) ?><span class="input-group-addon">- местное</span>
            </div><?= $help_block ?>
        </div>
    </td>
    <td class="table-items">
        <button type="button" class="btn btn-sm btn-success btn-add-placement" title="Добавить вариант размещения">
            <i class="glyphicon glyphicon-plus"></i>
        </button>
        <button type="button" class="btn btn-sm btn-danger btn-delete-placement" title="Удалить вариант размещения">
            <i class="glyphicon glyphicon-trash"></i>
        </button>
    </td>
</tr>