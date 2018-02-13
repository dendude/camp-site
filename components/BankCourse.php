<?php
namespace app\components;

use app\models\Orders;
use Yii;
use yii\base\Component;
use yii\bootstrap\Html;
use app\models\Settings;

class BankCourse extends Component {

    const SCAN_URL = 'http://www.cbr.ru/scripts/XML_daily.asp';
    
    const CACHE_NAME = 'bank-course';
    const CACHE_DURATION = 600;
        
    protected $full_url;
    protected static $courses = [];
    
    public function __construct(array $config = [])
    {
        parent::__construct($config);
        
        if (Yii::$app->cache->exists(self::CACHE_NAME)) {
            // установка из кеша
            self::$courses = Yii::$app->cache->get(self::CACHE_NAME);
        }
    }
    
    public function getCourse($abbr)
    {
        if (isset(self::$courses[$abbr])) return self::$courses[$abbr];
                
        $ch = curl_init(self::SCAN_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        $content = curl_exec($ch);
        $err_no = curl_errno($ch);
        curl_close($ch);
        
        try {
            if ($err_no) throw new \Exception();
            
            $xml = new \SimpleXMLElement($content);
    
            foreach ($xml AS $currency) {
                if ($currency->CharCode != $abbr) continue;
        
                $value = (float)str_replace(',', '.', $currency->Value);
                $value = round($value, 2);
                self::$courses[$abbr] = $value;
    
                Yii::$app->cache->set(self::CACHE_NAME, self::$courses, self::CACHE_DURATION);
        
                return $value;
            }
        } catch (\Exception $e) {
            // на случай ошибки
            if ($abbr == 'USD') return 58.06;
            if ($abbr == 'EUR') return 60.49;
        }
        
        return null;
    }
    
    public function convertToRubs($abbr, $amount, $commission = true)
    {
        if ($abbr == Orders::CUR_RUB) return $amount;
        
        $settings = Settings::lastSettings();
        $val_k = 1; // коэффициент для учета комиссии
        if ($commission) $val_k += ($settings->convert_percent / 100);

        return round($amount * $this->getCourse($abbr) * $val_k, 2);
    }
}
