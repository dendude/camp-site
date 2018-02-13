<?
/** @var string $content */
?>
<?= str_replace('src="', 'src="' . Yii::$app->params['site_url'], $content) ?>