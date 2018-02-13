<?php

namespace app\components;

use app\models\EmailTemplates;
use app\models\Settings;
use rmrevin\yii\postman\Component;
use rmrevin\yii\postman\models\LetterModel;
use rmrevin\yii\postman\RawLetter;
use Yii;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\swiftmailer\Mailer;

class SmtpEmail extends Mailer {

    const TYPE_REG_REQUEST = 1;
    const TYPE_REG_CONFIRM = 2;

    const TYPE_RESTORE_REQUEST = 3;
    const TYPE_RESTORE_CONFIRM = 4;
    
    const TYPE_NOTIF_TO_MANAGERS = 5;
    const TYPE_NOTIF_TO_PARTNERS = 6;
    const TYPE_NOTIF_TO_USERS = 7;
    
    const TYPE_CAMP_REGISTERED = 11;
    const TYPE_NEW_CAMP_NOTIFY = 12;
    const TYPE_EDIT_CAMP_NOTIFY = 13;
    
    const TYPE_ORDER_STATUS_CHANGED = 14;
    
    protected $template = 'html/auth';
    
    public function __construct(array $config = []) {
        parent::__construct($config);
    
        // получение конфига почты
        $settings = Settings::lastSettings();
    
        $this->setTransport([
            'class' => 'Swift_SmtpTransport',
            'host' => $settings->email_host,
            'username' => $settings->email_username,
            'password' => $settings->email_password,
            'port' => $settings->email_port,
            'encryption' => false,
        ]);
    }
    
    public function setTemplate($template) {
        $this->template = $template;
    }
        
    // отправка письма
    public function sendEmail($email, $name, $subject, $content, $data = []) {
        // получение конфига почты
        $settings = Settings::lastSettings();

        // для подстановки имени
        if (empty($data['{name}'])) $data['{name}'] = $name;
        if (empty($data['{email}'])) $data['{email}'] = $email;
        
        if (Yii::$app->request->hasProperty('hostInfo')) {
            $data['{sitename}'] = Html::a(Yii::$app->request->hostInfo, Yii::$app->request->hostInfo);
            $data['{site_url}'] = Yii::$app->request->hostInfo;
        } else {
            $data['{sitename}'] = Html::a(Yii::$app->params['sitename'], Yii::$app->params['sitename']);
            $data['{site_url}'] = Yii::$app->params['site_url'];
        }
            
        try {
            // замена переменных
            $content = str_replace(array_keys($data), array_values($data), $content);
            // отправка письма
            return $this->compose($this->template, ['content' => $content])
                ->setFrom([$settings->email_username => $settings->email_fromname])
                ->setTo([$email => $name])
                ->setSubject($subject)
                ->send();
        } catch (\Exception $e) {
            // не стопорим скрипт исключением - просто уведомляем
            \Yii::warning("Wrong send email to {$email}", 'smtp');
        }
    }

    public function sendEmailByType($type_id, $email, $name, $data = []) {
        $template = EmailTemplates::findOne($type_id);
        if ($template) {
            // контент письма
            return $this->sendEmail($email, $name, $template->subject, $template->content, $data);
        }
        return false;
    }
}
