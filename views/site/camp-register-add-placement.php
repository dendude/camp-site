<?
use yii\helpers\Html;
use app\models\CampsPlacement;
use app\models\BasePlacements;

/** @var $this \yii\web\View */
/** @var $model_placement BasePlacements */
/** @var $class string */

$help_block = Html::tag('div', '', ['class' => 'help-block small m-none']);
?>
<div class="row m-t-5 <?= $class ?>">
    <?= Html::activeHiddenInput($model_placement, '[{index}]id') ?>
    
    <div class="col-xs-5">
        <div class="form-group-item">
            <?= Html::activeDropDownList($model_placement, '[{index}]comfort_type', CampsPlacement::getPlacementTypes(), [
                'class' => 'form-control input-sm',
            ]) ?>
            <?= $help_block ?>
        </div>
    </div>
    <div class="col-xs-6">
        <div class="form-group-item">
            <div class="input-group">
                <?= Html::activeTextInput($model_placement, '[{index}]comfort_about', [
                    'class' => 'form-control input-sm',
                    'placeholder' => 'Примеры: 3, 4-5',
                ]) ?><span class="input-group-addon">- местное</span>
            </div>
            <?= $help_block ?>
        </div>
    </div>
    <div class="col-xs-1">
        <button type="button" class="btn btn-sm btn-block btn-success btn-add-placement" title="Добавить вариант размещения">
            <i class="glyphicon glyphicon-plus"></i>
        </button>
        <button type="button" class="btn btn-sm btn-block btn-danger btn-delete-placement" title="Удалить вариант размещения">
            <i class="glyphicon glyphicon-trash"></i>
        </button>
    </div>
</div>