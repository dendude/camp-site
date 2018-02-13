<?php
/**
 * Created by PhpStorm.
 * User: dendude
 * Date: 13.11.16
 * Time: 18:20
 */

namespace app\components;

use yii\base\Component;
use yii\helpers\Json;

class ReCaptcha extends Component
{
    const REQUEST_URL = 'https://www.google.com/recaptcha/api/siteverify';
    const PUBLIC_KEY = '6LePfCIUAAAAAO-fAzzRV4FTiOHHn5nr7rsxkQmb';
    const PRIVATE_KEY = '6LePfCIUAAAAAOuJ7z3-FVL-mSRwWhNnjf0S-rrN';
    
    const FIELD_NAME = 'g-recaptcha-response';
    
    protected $captcha_value;
    
    public function __construct($captcha_value, array $config = [])
    {
        $this->captcha_value = $captcha_value;
        parent::__construct($config);
    }
    
    protected function sendRequest()
    {
        $ch = curl_init(self::REQUEST_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'secret' => self::PRIVATE_KEY,
            'response' => $this->captcha_value,
            'ip' => \Yii::$app->request->userIP
        ]);
        $resp = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
                
        if ($http_code == 200) return Json::decode($resp);
    
        return false;
    }
    
    public function validate()
    {
        $response = $this->sendRequest();
        return (isset($response['success']) && $response['success'] === true);
    }
}