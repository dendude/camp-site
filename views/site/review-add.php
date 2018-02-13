<?php
use app\helpers\MetaHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\ReviewsItems;
use app\helpers\CampHelper;

/* @var $this yii\web\View */
/* @var $camp \app\models\Camps */
/* @var $review \app\models\Reviews */
/* @var $review_items \app\models\ReviewsItems[] */
/* @var $model \app\models\Pages */

$this->title = $model->title;
MetaHelper::setMeta($model, $this);

$this->params['breadcrumbs'] = [
    ['label' => 'Все лагеря', 'url' => ['/camps']],
    ['label' => $camp->about->country->name, 'url' => CampHelper::getCountryCampsUrl($camp)],
    ['label' => $camp->about->region->name, 'url' => CampHelper::getRegionCampsUrl($camp)],
    ['label' => $camp->getAgesText(), 'url' => CampHelper::getAgesCampsUrl($camp)],
    ['label' => $camp->about->name_short, 'url' => $camp->getCampUrl()],
];

$this->params['breadcrumbs'][] = $model->title;

$comment_template = ['template' => '<div class="col-xs-12">{input}</div>'];

$review_items = ReviewsItems::find()->active()->ordering()->all();
?>
<div class="layout-container camp-container">
    <div class="camp-content camp-order-form">
        <h1 class="order-form-title m-t-0">
            Оставить отзыв о лагере:<br>
            <strong><a href="<?= $camp->getCampUrl() ?>"><?= Html::encode($camp->about->name_short) ?></a></strong>
        </h1>
        
        <? if (Yii::$app->session->hasFlash('success')): ?>
            
            <?= \app\helpers\MHtml::alertMsg(); ?>
            
        <? else: ?>
            
            <? $form = ActiveForm::begin(); ?>
                <?= Html::activeHiddenInput($review, 'base_id', ['value' => $camp->id]) ?>
            
                <?= $form->errorSummary($review, ['class' => 'alert alert-danger']) ?>
                
                <div class="form-group">
                    <div class="col-xs-12 col-md-3">
                        <h2 class="order-form-about">Достоинства +</h2>
                    </div>
                    <div class="col-xs-12 col-md-9">
                        <?= $form->field($review, 'comment_positive', $comment_template)->textarea() ?>
                    </div>
                </div>
    
                <div class="form-group">
                    <div class="col-xs-12 col-md-3">
                        <h2 class="order-form-about color-red">Недостатки -</h2>
                    </div>
                    <div class="col-xs-12 col-md-9">
                        <?= $form->field($review, 'comment_negative', $comment_template)->textarea(['placeholder' => 'Не обязательное поле']) ?>
                    </div>
                </div>
    
                <div class="form-group">
                    <div class="col-xs-12 col-md-3">
                        <h2 class="order-form-about color-gray">Оцените лагерь</h2>
                    </div>
                    <div class="col-xs-12 col-md-9">
                        <? foreach ($review_items AS $r_item): ?>
                            <h4 class="m-t-5"><?= Html::encode($r_item->title) ?></h4>
                            <ul class="review-votes m-b-20 clearfix">
                                <? foreach (ReviewsItems::getVoteItems() AS $k => $v): ?>
                                    <? $is_active = (!empty($review->votes_arr[$r_item->id]) && $review->votes_arr[$r_item->id] == $k); ?>
                                    <li <? if($is_active): ?>class="active"<? endif; ?>>
                                        <a class="vote-<?= $k ?>" onclick="set_vote(this); return false;"><?= $v ?></a>
                                        <?= Html::activeHiddenInput($review, "votes_arr[{$r_item->id}]", ['value' => $k, 'disabled' => !$is_active]) ?>
                                    </li>
                                <? endforeach; ?>
                            </ul>
                        <? endforeach; ?>
                    </div>
                </div>
            
                <? if (Yii::$app->user->isGuest): ?>
                    <div class="form-group">
                        <div class="col-xs-12 col-md-6">
                            <?= $form->field($review, 'user_name', $comment_template)->textInput(['placeholder' => 'Ваше имя']) ?>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <?= $form->field($review, 'user_email', $comment_template)->textInput(['placeholder' => 'Ваше Email для ответа']) ?>
                        </div>
                    </div>
                <? endif; ?>
    
                <div class="row m-t-20 m-b-20">
                    <div class="col-xs-12 col-md-offset-3 col-md-9">
                        <?= str_replace('{captcha}', '<div id="widget_review"></div>',
                            $form->field($review, 'captcha', ['template' => '<div class="col-xs-12">{captcha}{error}</div>'])) ?>
                    </div>
                </div>
        
                <div class="form-group">
                    <div class="col-xs-12 col-md-offset-3 col-md-9">
                        <button type="submit" class="btn btn-primary">Отправить отзыв</button>
                    </div>
                </div>
        
            <? ActiveForm::end(); ?>
            
        <? endif; ?>
    </div>
    
    <div class="camp-order camp-order-lg hidden-xs">
        <h3 class="order-title">Как оставить отзыв?</h3>
        <ol class="order-steps">
            <li>Введите достоинства</li>
            <li>Введите, при наличии, недостатки.</li>
            <li>Оцените лагерь по нескольким критериям.</li>
            <li>Нажмите кнопку "Отправить отзыв".</li>
        </ol>
        <p>После этого наши модераторы обработают ваш отзыв и опубликуют его на сайте.</p>
    </div>
</div>

<div class="m-t-75">
    <div class="layout-container"><?= \app\widgets\CampOrders::widget() ?></div>
</div>