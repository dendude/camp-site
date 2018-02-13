<?php
use app\helpers\MetaHelper;
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $page \app\models\Pages
 * @var $message string
 */

$this->title = $page->title;
MetaHelper::setMeta($page, $this);
?>
<div class="layout-container">
    <h1 class="index-title"><?= $page->title ?></h1>
    <div class="m-b-75 m-t-30">
        <div class="alert alert-success"><?= $message ?></div>
        <p>Если вы желаете отправить ещё один лагерь, то воспользуйтесь снова <a href="<?= Url::to(['/camp-register']) ?>">формой добавления лагеря</a>.</p>
    </div>
</div>