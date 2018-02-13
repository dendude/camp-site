<?
use yii\helpers\Url;
use app\components\LangUrlManager;
use app\components\BankCourse;

$curs = new BankCourse();

?>
<!-- Logo -->
<a href="<?= Url::to(['main/index']) ?>" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b>С</b>C</span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>Camp</b>Center</span>
</a>
<!-- Header Navbar: style can be found in header.less -->
<nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>

    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            <li>
                <a href="https://www.cbr.ru/" target="_blank">
                    1 <?= \app\models\Orders::CUR_USD ?> = <?= $curs->getCourse(\app\models\Orders::CUR_USD) ?> RUR
                </a>
            </li>
            <li>
                <a href="https://www.cbr.ru/" target="_blank">
                    1 <?= \app\models\Orders::CUR_EUR ?> = <?= $curs->getCourse(\app\models\Orders::CUR_EUR) ?> RUR
                </a>
            </li>
            <li>
                <a href="<?= Url::to(Yii::$app->homeUrl) ?>" target="_blank">На сайт</a>
            </li>
            <li>
                <a href="<?= Url::to(['/office/main/index']) ?>">Личный кабинет</a>
            </li>
            <li>
                <a href="<?= Url::to(['/auth/logout']) ?>">Выход</a>
            </li>
        </ul>
    </div>
</nav>