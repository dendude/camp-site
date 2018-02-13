<?php
namespace app\components;

use app\models\Reviews;
use app\models\Settings;
use yii\helpers\Url;

class SendReviewNotifications
{
    protected $review;
    protected $smtp;
    protected $settings;
    
    public function __construct(Reviews $review) {
        $this->review = $review;
        $this->smtp = new SmtpEmail();
        $this->settings = Settings::lastSettings();
    }
    
    public function send() {
        $this->_sendToManagers();
        $this->_sendToPartners();
        $this->_sendToClient();
    }
    
    protected function _sendToManagers() {
        $review = $this->review;
        
        $emails = explode(',', str_replace(' ', '', trim($this->settings->emails_order)));
        $emails = array_unique(array_filter($emails));
    
        foreach ($emails AS $email) {
            // отправка уведомлений админам
            $this->smtp->sendEmailByType(8, $email, 'Администратор', [
                '{camp-url}' => Url::to($review->camp->getCampUrl(), true),
                '{camp-name}' => $review->camp->about->name_short,
                '{client-name}' => $review->user_name,
                '{client-email}' => $review->user_email,
                '{manage-url}' => Url::to(['/manage/reviews/list'], true),
                '{comment-positive}' => nl2br($review->comment_positive),
                '{comment-negative}' => nl2br($review->comment_negative),
                '{stars}' => $review->stars,
            ]);
        }
    }
    
    protected function _sendToPartners() {
        $review = $this->review;
    
        $emails = explode(',', str_replace(' ', '', trim($review->camp->contacts->notif_order_emails)));
        $emails = array_unique(array_filter($emails));
    
        $emails[$review->camp->contacts->worker_fio] = $review->camp->contacts->worker_email;
    
        foreach ($emails AS $name => $email) {
            // отправка уведомлений админам
            $name = is_numeric($name) ? 'Менеджер' : $name;
            
            $this->smtp->sendEmailByType(9, $email, $name, [
                '{camp-url}' => Url::to($review->camp->getCampUrl(), true),
                '{camp-name}' => $review->camp->about->name_short,
                '{manager-name}' => $name,
                '{client-name}' => $review->user_name,
                '{client-email}' => $review->user_email,
                '{comment-positive}' => nl2br($review->comment_positive),
                '{comment-negative}' => nl2br($review->comment_negative),
                '{stars}' => $review->stars,
            ]);
        }
    }
    
    protected function _sendToClient() {
        $review = $this->review;
                    
        $this->smtp->sendEmailByType(10, $review->user_email, $review->user_name, [
            '{camp-url}' => Url::to($review->camp->getCampUrl(), true),
            '{camp-name}' => $review->camp->about->name_short,
            '{comment-positive}' => nl2br($review->comment_positive),
            '{comment-negative}' => nl2br($review->comment_negative),
            '{stars}' => $review->stars,
        ]);
    }
}