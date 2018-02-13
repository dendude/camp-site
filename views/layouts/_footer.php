<?
use yii\helpers\Html;
use app\models\Pages;
use app\models\Menu;
use yii\helpers\Url;
use app\models\Users;

/**
 * @var $this \yii\web\View
 */

$settings = \app\models\Settings::lastSettings();
$bottom_menu = Menu::find()->active()->bottom()->all();
?>
<footer class="footer">
    <? if ($bottom_menu): ?>
        <div class="footer__menu">
            <div class="layout-container">
                <nav>
                    <ul>
                        <? foreach ($bottom_menu AS $item): ?>
                            <li <? if (isset($this->params['id']) && $this->params['id'] == $item->page_id): ?>class="active"<? endif; ?>>
                                <a href="<?= Pages::getUrlById($item->page_id) ?>"><?= Html::encode($item->name) ?></a>
                            </li>
                        <? endforeach; ?>
                        <li class="hidden-xs">
                            <a href="<?= Pages::getUrlById(Pages::PAGE_CAMP_REGISTER_ID) ?>" class="footer__register-camp">Регистрация лагеря</a>
                        </li>
                        
                        <? if (Yii::$app->user->isGuest): ?>
                            <li class="hidden-xs"><a href="<?= Url::to(['/auth/login']) ?>"><i class="fa fa-sign-in"></i>Личный кабинет</a></li>
                        <? else: ?>
                            <li class="hidden-xs"><a href="<?= Url::to(['/office/main/index']) ?>">Личный кабинет</a></li>
                        <? endif; ?>
                        <? if (Users::isAdmin()): ?>
                            <li class="hidden-xs"><a href="<?= Url::to(['/manage/main/index']) ?>">Admin</a></li>
                        <? endif; ?>
                        <? if (!Yii::$app->user->isGuest): ?>
                            <li class="hidden-xs"><a href="<?= Url::to(['/auth/logout']) ?>">Выход</a></li>
                        <? endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    <? endif; ?>
    
    <div class="footer__info">
        <div class="layout-container">
            <div class="footer__blocks">
                <div class="footer__about">
                    <div class="footer__socials">
                        <a href="<?= $settings->social_fb ?>" class="footer__socials-item social-fb" target="_blank" title="Facebook"></a>
                        <a href="<?= $settings->social_vk ?>" class="footer__socials-item social-vk" target="_blank" title="ВКонтакте"></a>
                        <a href="<?= $settings->social_ok ?>" class="footer__socials-item social-ok" target="_blank" title="Одноклассники"></a>
                        <a href="#" class="footer__socials-item social-tw" target="_blank" title="Twitter"></a>
                        <a href="#" class="footer__socials-item social-in" target="_blank" title="Instagram"></a>
                    </div>
                    <p class="footer__company">
                        <?= date('Y') ?> ООО «КЭМП-ЦЕНТР»<br>
                        интернет-каталог детских лагерей<br>
                        с бесплатным бронированием путевок
                    </p>
                    <p class="footer__address">
                        Адрес: 125040, Москва,<br>
                        Ленинградский проспект д. 26 к. 1 оф. 2
                    </p>
                </div>
                <div class="footer__contacts">
                    <p class="footer__phone">8-800-222-74-66</p>
                    <ul class="footer__pays">
                        <li><img src="/img/pay-visa.png" alt="visa"></li>
                        <li><img src="/img/pay-pal.png" alt="pay-pal"></li>
                        <li><img src="/img/pay-master-card.png" alt="master-card"></li>
                    </ul>
                    <p class="footer__about-pays">Удобная оплата банковскими картами<br>и другими способами</p>
                </div>
                <div class="footer__call">
                    <a class="footer__call-me" href="#">Перезвоните мне</a>
                    <p class="footer__copy">&copy;&nbsp;КЭМП-ЦЕНТР. ВСЕ ПРАВА ЗАЩИЩЕНЫ</p>
                </div>
            </div>
        </div>
    </div>
    </div>
</footer>
<a href="#top" class="arrow-top"><i class="fa fa-arrow-up"></i></a>

<script async="async" src="https://w.uptolike.com/widgets/v1/zp.js?pid=1653756" type="text/javascript"></script>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) { (w[c] = w[c] || []).push(function() {
        try { w.yaCounter42335319 = new Ya.Metrika({ id:42335319, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); }
        catch(e) { } });
    var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js";
    if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); }
    else { f(); } })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/42335319" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
    ga('create', 'UA-96477834-1', 'auto');
    ga('send', 'pageview');
</script>

<!-- VK Widget -->
<div id="vk_community_messages"></div>
<script type="text/javascript">
    VK.Widgets.CommunityMessages("vk_community_messages", 130077299, {expandTimeout: "120000",tooltipButtonText: "Есть ли у вас вопрос ?"});
</script>