<?php

use yii\helpers\Url;
use yii\helpers\Html;

use app\models\forms\UploadForm;
use app\models\forms\UploadFileForm;

/**
 * @var \yii\base\Model $model
 * @var string $field
 * @var string $imageUploadUrl
 * @var string $filesUploadUrl
 * @var string $filesManagerUrl
 */

$upload_photo = new UploadForm();
$upload_file = new UploadFileForm();

echo \froala\froalaeditor\FroalaEditorWidget::widget([
    'model' => $model,
    'attribute' => $field,
    'clientOptions'=>[
        'toolbarInline'=> false,
        'theme' => 'default', // optional: dark, red, gray, royal
        'language' => 'ru',
        
        'height' => false,
        'fullPage' => false,
        'heightMin' => 300,
        'heightMax' => 600,
        
        'htmlRemoveTags' => ['style'],
        'paragraphMultipleStyles' => false,
        
        'linkList' => [[
            'text' => 'Google',
            'href' => 'http://google.com',
            'target' => '_blank',
            'rel' => 'nofollow'
        ]],
        
        'saveInterval' => 10000,
        'saveMethod' => 'POST',
        'saveURL' => Url::to(['save-temp-content']),
        'saveParam' => 'content',
        'saveParams' => [
            'id' => $model->id,
            Yii::$app->request->csrfParam => Yii::$app->request->csrfToken,
        ],
        
        'linkStyles' => [
            'link-color-blue' => 'Синий',
            'link-color-red' => 'Красный',
            'link-color-green' => 'Зеленый',
            'link-color-black' => 'Черный',
            'link-color-gray' => 'Серый',
        ],
        
        'fileAllowedTypes' => ['application/pdf', 'application/msword', 'application/msexcell'],
        'fileMaxSize' => (1024 * 1024 * 50),
        'fileUploadMethod' => 'POST',
        'fileUploadParam' => Html::getInputName($upload_file, 'docFile'),
        'fileUploadParams' => [
            Yii::$app->request->csrfParam => Yii::$app->request->csrfToken,
        ],
        'fileUploadURL' => $filesUploadUrl,
        'fileUseSelectedText' => true,
        
        'imageManagerPageSize' => 12,
        'imageManagerScrollOffset' => 10,
        'imageAllowedTypes' => ['jpeg', 'jpg', 'png', 'gif'],
        'imageDefaultAlign' => 'left',
        'imageDefaultDisplay' => 'block',
        'imageManagerLoadMethod' => 'POST',
        'imageManagerLoadURL' => $filesManagerUrl,
        'imageManagerLoadParams' => [
            Yii::$app->request->csrfParam => Yii::$app->request->csrfToken,
            'type' => 'images'
        ],
        'imageManagerDeleteMethod' => 'POST',
        'imageManagerDeleteParams' => [
            Yii::$app->request->csrfParam => Yii::$app->request->csrfToken,
        ],
        'imageManagerDeleteURL' => Url::to(['files-manager-delete']),
        
        'imageOutputSize' => true,
        'imageMaxSize' => (1024 * 1024 * 10),
        'imageMinWidth' => 32,
        'imageMinHeight' => 32,
        'imageDefaultWidth' => 0,
        'imageMove' => true,

        'imageUploadURL' => $imageUploadUrl,
        'imageUploadMethod' => 'POST',
        'imageUploadParam' => Html::getInputName($upload_photo, 'imageFile[0]'),
        'imageUploadParams' => [
            Yii::$app->request->csrfParam => Yii::$app->request->csrfToken,
        ],
        
        'imageStyles' => [
            'border-radius-2' => 'Скругленные углы 2px',
            'border-radius-4' => 'Скругленные углы 4px',
            'border-radius-6' => 'Скругленные углы 6px',
            'border-radius-8' => 'Скругленные углы 8px',
            'border-radius-10' => 'Скругленные углы 10px',

            'border-1' => 'Обводка 1px',
            'border-2' => 'Обводка 2px',
            'border-3' => 'Обводка 3px',
            'border-5' => 'Обводка 5px',
            'border-8' => 'Обводка 8px',
        ]
    ]
]);

$this->registerJs("
    $(document).ready(function(){
        $('a[href*=\"froala.com\"]').closest('div').remove();
    });
");
?>
<div class="m-t-20">
    <ul>
        <li><strong>Enter</strong> - перенос строки с отступом (новый параграф);</li>
        <li><strong>Shift+Enter</strong> - перенос без отступа (обычный перенос строки);</li>
        <!--<li><strong>{name}</strong> - автоподстановка имени пользователя</li>
        <li><strong>{email}</strong> - автоподстановка Email пользователя</li>
        <li><strong>{sitename}</strong> - автоподстановка сайта</li>-->
    </ul>
</div>