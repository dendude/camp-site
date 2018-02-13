<?php
use app\components\BankCourse;

$curs = new BankCourse();
?>
<div class="office-curs">
    Курсы валют<br>
    1 USD = <?= $curs->convertToRubs(\app\models\Orders::CUR_USD, 1) ?> RUB<br>
    1 EUR = <?= $curs->convertToRubs(\app\models\Orders::CUR_EUR, 1) ?> RUB<br>
</div>