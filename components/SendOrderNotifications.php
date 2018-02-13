<?php
namespace app\components;

use app\helpers\Normalize;
use app\models\Orders;
use app\models\Settings;
use yii\helpers\Url;

class SendOrderNotifications
{
    protected $order;
    protected $smtp;
    protected $settings;
    
    public function __construct(Orders $order) {
        $this->order = $order;
        $this->smtp = new SmtpEmail();
        $this->settings = Settings::lastSettings();
    }
    
    public function send() {
        $this->_sendToManagers();
        $this->_sendToPartners();
        $this->_sendToClient();
    }
    
    protected function _sendToManagers() {
        $order = $this->order;
        
        $emails = Normalize::emailsStrToArr($this->settings->emails_order);
        $emails = array_unique($emails);
    
        foreach ($emails AS $email) {
            // отправка уведомлений админам
            $this->smtp->sendEmailByType(SmtpEmail::TYPE_NOTIF_TO_MANAGERS, $email, 'Администратор', [
                '{id}'            => $order->id,
                '{camp-url}'      => Url::to($order->camp->getCampUrl(), true),
                '{camp-name}'     => $order->camp->about->name_short,
                '{camp-smena}'    => $order->campItem->name_full,
                '{date-from}'     => $order->campItem->date_from_orig,
                '{date-to}'       => $order->campItem->date_to_orig,
                '{camp-contacts}' => implode('<br/>', [
                    "ФИО руководителя: {$order->camp->contacts->boss_fio}",
                    "Email руководителя: {$order->camp->contacts->boss_email}",
                    "Телефон руководителя: " . Normalize::formatPhone($order->camp->contacts->boss_phone),
                    "ФИО сотрудника: {$order->camp->contacts->worker_fio}",
                    "Email сотрудника: {$order->camp->contacts->worker_email}",
                    "Телефон сотрудника: " . Normalize::formatPhone($order->camp->contacts->worker_phone),
                ]),
                '{client-fio}'    => $order->client_fio,
                '{client-email}'  => $order->client_email,
                '{client-phone}'  => Normalize::formatPhone($order->client_phone),
                '{children-data}' => implode('<br />', $order->getOrderData()),
                '{price-partner}' => ($order->price_partner . ' ' . $order->currency_partner),
                '{price-user}'    => ($order->price_user . ' ' . $order->currency),
                '{manage-url}'    => Url::to(['/manage/orders/list'], true),
            ]);
        }
    }
    
    protected function _sendToPartners() {
        $order = $this->order;
    
        $emails = Normalize::emailsStrToArr($order->camp->contacts->notif_order_emails);
        $emails[$order->camp->contacts->worker_fio] = $order->camp->contacts->worker_email;

        $emails = array_unique($emails);
    
        foreach ($emails AS $name => $email) {
            // отправка уведомлений админам
            $name = is_numeric($name) ? 'Менеджер' : $name;
            
            $this->smtp->sendEmailByType(SmtpEmail::TYPE_NOTIF_TO_PARTNERS, $email, $name, [
                '{id}'            => $order->id,
                '{camp-url}'      => Url::to($order->camp->getCampUrl(), true),
                '{camp-name}'     => $order->camp->about->name_short,
                '{camp-smena}'    => $order->campItem->name_full,
                '{manager-name}'  => $name,
                '{date-from}'     => $order->campItem->date_from_orig,
                '{date-to}'       => $order->campItem->date_to_orig,
                '{client-fio}'    => $order->client_fio,
                '{client-email}'  => $order->client_email,
                '{client-phone}'  => Normalize::formatPhone($order->client_phone),
                '{children-data}' => implode('<br />', $order->getOrderData()),
                '{price-partner}' => ($order->price_partner . ' ' . $order->currency_partner),
                '{price-user}'    => ($order->price_user . ' ' . $order->currency),
                '{manage-url}'    => Url::to(['/manage/orders/list'], true),
            ]);
        }
    }
    
    protected function _sendToClient() {
        $order = $this->order;

        $this->smtp->sendEmailByType(SmtpEmail::TYPE_NOTIF_TO_USERS, $order->client_email, $order->client_fio, [
            '{id}' => $order->id,
            '{camp-url}' => Url::to($order->camp->getCampUrl(), true),
            '{camp-name}' => $order->camp->about->name_short,
            '{camp-smena}' => $order->campItem->name_full,
            '{manager-name}' => $order->camp->contacts->worker_fio,
            '{date-from}' => $order->campItem->date_from_orig,
            '{date-to}' => $order->campItem->date_to_orig,
            '{client-fio}' => $order->client_fio,
            '{client-email}' => $order->client_email,
            '{client-phone}' => Normalize::formatPhone($order->client_phone),
            '{children-data}' => implode('<br />', $order->getOrderData()),
            '{price}' => ($order->price_user . ' ' . $order->currency),
        ]);
    }
}