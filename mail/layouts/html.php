<?php
use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body style="margin: 0; padding: 0">
    <div class="content" style="background-color: #CCC; font-family: Tahoma, Arial, Verdana, Georgia, Serif; font-size: 14px; padding: 20px">
        <div style="width: 600px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; padding: 15px;">
            <table cellspacing="0" cellpadding="0" width="100%">
                <tr><td valign="top"><?= $this->render('_header') ?></td></tr>
                <tr><td valign="top"><?= $content ?></td></tr>
                <tr><td valign="top"><?= $this->render('_footer') ?></td></tr>
            </table>
        </div>
        <p style="text-align: center; color: #777">Москва, Ленинградский проспект д.26 к.1 оф.2<br/>тел: 8-800-222-74-66</p>
    </div>
</body>
</html>