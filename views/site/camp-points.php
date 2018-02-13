<?
use app\helpers\Normalize;
use app\models\Reviews;

/**
 * @var $model \app\models\Camps
 * @var $reviews \app\models\Reviews[]
 * @var $reviews_items \app\models\ReviewsItems[]
 */

$reviews = Reviews::find()->active()->byCamp($model->id)->all();
$reviews_items = \app\models\ReviewsItems::find()->ordering()->all();

$reviews_values = [];
$reviews_totals = [];

foreach ($reviews AS $r) {
    if (empty($r->votes_arr)) continue;
    
    foreach ($r->votes_arr AS $vid => $val) {
        if (!isset($reviews_values[$vid])) $reviews_values[$vid] = [];
    
        $reviews_values[$vid][] = $val;
    }
}

foreach ($reviews_values AS $vid => $v_arr) {
    $reviews_totals[$vid] = array_sum($v_arr);
}
?>
<h3 class="text-center">Критерии отзывов</h3>
<table width="100%">
    <? foreach ($reviews_items AS $item): ?>
        <? if (!isset($reviews_totals[$item->id])) continue; ?>
        <tr>
            <td class="text-right" width="48%"><?= $item->title ?></td>
            <td width="4%">&nbsp;</td>
            <td class="text-left color-orange"><?= Normalize::getStarsIcons($reviews_totals[$item->id] / count($reviews_values[$item->id]), 5) ?></td>
        </tr>
    <? endforeach; ?>
</table>