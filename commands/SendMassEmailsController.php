<?php
namespace app\commands;

use app\components\SmtpEmail;
use app\helpers\Statuses;
use app\models\EmailMass;
use app\models\Users;
use yii\console\Controller;

/**
 * отправка рассылки
 * Class SendMassEmailsController
 * @package app\commands
 *
 * выполняется по крону каждые 10 минут
 */
class SendMassEmailsController extends Controller {
            
    public function actionRun() {
        // не начинаем новый процесс отправки пока существуют запущенные рассылки
        if (EmailMass::find()->where(['status' => Statuses::STATUS_USED])->exists()) return;
        
        /** @var $mass EmailMass */
        $mass = EmailMass::find()->usage()
            ->andWhere(['status' => Statuses::STATUS_PROCESS])
            ->andWhere('send_time = 0 OR send_time <= :time', [':time' => time()])
            ->orderBy('send_time ASC, id ASC')->one();
        
        if ($mass) {
            /** @var $users Users[] */
            $users = Users::find()->select('id, email, first_name')->using()->all();
    
            if ($users) {
                $mass->updateAttributes([
                    'status' => Statuses::STATUS_USED,
                    'count_total' => count($users)
                ]);
                
                $smtp = new SmtpEmail();
                $smtp->setTemplate('html/mass');
                
                foreach ($users AS $u) {
                    $smtp->sendEmail($u->email, $u->first_name, $mass->title, $mass->content);
                    $mass->updateCounters(['count_sent' => 1]);
                }
            }
    
            $mass->updateAttributes(['status' => Statuses::STATUS_ACTIVE]);
        }
    }
}
