<?php
namespace app\components;

use app\helpers\Normalize;
use app\models\Orders;
use yii\base\Component;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * данные для формы оплаты
 * Class PayTravelComponent
 * @package app\components
 */
class PayTravel extends Component
{
    const AUTH_LOGIN = 'kempcentr-api';
    const AUTH_PASS = 'manager2016'; // manager2016
    
    const BILL_URL = '//platform.pay.travel/payment/choice';
    
    const TOKEN_BASE32 = 'SAAHNNWX3XFV7BPZVX5YENXX7G27P6CFDWBDNU4BUXHXJUCME2IQ';
    const TOKEN_BASE64 = 'kAB2ttfdy1+F+a37gjb3+bX3+EUdgjbTgaXPdNBMJpE=';
    const TOKEN_HEX = '900076B6D7DDCB5F85F9ADFB8236F7F9B5F7F8451D8236D381A5CF74D04C2691';
        
    static $PASS_CODE_LENGTH = 6;
    static $PIN_MODULO;
    
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
    
    protected $order;
    protected $params = [];
    
    protected static $user_param;
    protected static $user_value;
    protected static $exp_date;

    public function __construct($order_id = null, array $config = [])
    {
        $this->order = Orders::findOne($order_id);
        
        self::$PIN_MODULO = pow(10, self::$PASS_CODE_LENGTH);
                
        parent::__construct($config);
    }
    
    public function getParams() {
        return $this->params;
    }
    
    public function authenticate() {
        if (self::$user_param || self::$user_value) return;
            
        $auth = $this->_request('accounts/authenticate', self::METHOD_POST, [
            'login' => self::AUTH_LOGIN,
            'password' => self::AUTH_PASS,
            'oneTimePassword' => $this->_getTimePass()
        ]);
    
        self::$user_param = $auth['name'];
        self::$user_value = $auth['value'];
        self::$exp_date = $auth['expiration'];
    }
    
    public function paymentOptions() {
        $this->authenticate();
        return $this->_request('paymentOptions', self::METHOD_GET);
    }
    
    public function getEventsHandlers() {
        $this->authenticate();
        return $this->_request('events/handlers', self::METHOD_GET);
    }
    
    public function setEventsHandlers() {
        $this->authenticate();
        return $this->_request('events/handlers', self::METHOD_PUT, [
            'url' => Url::to(['/payment/event', 'orderCode' => '{orderCode}', 'eventType' => '{eventType}'], true)
        ]);
    }
    
    public function ordersForAgency() {
        $this->authenticate();
    
        $sys_codes = [];
        $payments = $this->paymentOptions();
        foreach ($payments AS $pm) $sys_codes[] = $pm['paymentSystem']['code'];
        
        $tourists = [];
        foreach ($this->order->order_data AS $k => $v) {
            if (strpos($k, 'child_birth') === 0) {
                foreach ($this->order->order_data[$k] AS $kk => $vv) {
                    $tourists[] = [
                        'firstName' => $this->order->order_data['child_fio'][$kk],
                        'lastName' => $this->order->order_data['child_fio'][$kk],
                        'sex' => 'male',
                        'dateOfBirth' => Normalize::getSqlDate($this->order->order_data['child_birth'][$kk]),
                        'passport' => 'passport',
                    ];
                }
            }
        }
        
        $result = $this->_request('orders/forAgency', self::METHOD_POST, [
            'sourceCode' => $this->params['agencySourceCode'],
            'paymentAction' => 'hold',
            'priceData' => [
                'operatorOriginalPrice' => [
                    'amount' => $this->order->price_partner,
                    'currencyCode' => Orders::CUR_RUB
                ],
                'agencyOriginalCommission' => [
                    'amount' => ($this->order->price_user - $this->order->price_partner),
                    'currencyCode' => Orders::CUR_RUB
                ],
                'exchangeRate' => 1,
                'priceIsLinked' => false,
                'exchangeRateSourceType' => 'manual',
                'exchangeRateAdditionalPercent' => 0,
                'currencyCode' => Orders::CUR_RUB
            ],
            'agencyPriceData' => [
                'originalMargin' => [
                    'amount' => 0,
                    'currencyCode' => Orders::CUR_RUB
                ],
                'agencyMargin' => [
                    'amount' => 0,
                    'currencyCode' => Orders::CUR_RUB
                ],
                'paymentSystemCommission' => [
                    'amount' => 0,
                    'currencyCode' => Orders::CUR_RUB
                ],
                'agencyMarginPercent' => 0,
                'hasClearing' => false,
                'autoWithdrawal' => false
            ],
            'allowedPaymentSystemCodes' => [$sys_codes[2]],
            'orderData' => [
                'description' => "{$this->order->camp->about->name_short} {$this->order->campItem->name_short}",
                'country' => $this->order->camp->about->country->name,
                'tourists' => $tourists,
                'services' => [
                    ['description' => "Оплата отдыха в детском лагере \"{$this->order->camp->about->name_short}\""],
                ],
            ],
        ]);
        
        if (isset($result['code'])) {
            // уникальный код заявки (PNR)
            $this->params['orderCode'] = $result['code'];
            //$this->params['billingCode'] = $result['agencyCode'];
            $this->params['returnUrl'] = Url::to(['/payment/{status}'], true);
            $this->params['payerType'] = 'tourist';
        }
        
        return $result;
    }
    
    protected function _request($action, $method, $params = []) {
        
        $headers = ['Content-type: application/json'];
        if (self::$user_param && self::$user_value) {
            $headers[] = 'Cookie: ' . self::$user_param . '=' . self::$user_value;
        }
        
        $ch = curl_init("https://platform.pay.travel/v1/api/{$action}");
        if (count($params)) curl_setopt($ch, CURLOPT_POSTFIELDS, Json::encode($params));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        $errno = curl_errno($ch);
        
        curl_close($ch);
        
        if ($http_code >= 200 && $http_code <= 299) {
            return json_decode($response, true);
        } elseif ($response !== false) {
            echo $action;
            print_r(json_decode($response, true));
        } else {
            throw new \Exception($error, $errno);
        }
    }
    
    protected function _getTimePass($time = null) {
        if (!$time) $time = floor(time() / 30);
        $secret = base64_decode(self::TOKEN_BASE64);
        
        $time = pack("N", $time);
        $time = str_pad($time,8, chr(0), STR_PAD_LEFT);
        
        $hash = hash_hmac('sha1',$time,$secret,true);
        $offset = ord(substr($hash,-1));
        $offset = $offset & 0xF;
        
        $truncatedHash = $this->_hashToInt($hash, $offset) & 0x7FFFFFFF;
        $pinValue = str_pad($truncatedHash % self::$PIN_MODULO,6,"0",STR_PAD_LEFT);;
        return $pinValue;
    }
    
    protected function _hashToInt($bytes, $start) {
        $input = substr($bytes, $start, strlen($bytes) - $start);
        $val2 = unpack("N",substr($input,0,4));
        return $val2[1];
    }
}