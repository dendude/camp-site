<?php
namespace app\commands;

use app\models\Base;
use app\models\BaseItems;
use app\models\Camps;
use app\models\CampsAbout;
use app\models\CampsClient;
use app\models\CampsContacts;
use app\models\CampsContract;
use app\models\CampsMedia;
use app\models\CampsPlacement;
use yii\console\Controller;
use yii\helpers\Json;

/**
 * Class CampsMinPriceController
 * @package app\commands
 *
 * выполняется по крону каждые 5 минут
 */

class CampsMinPriceController extends Controller {
            
    public function actionRun()
    {
        /** @var $camps Camps[] */
        $camps = Camps::find()->using()->all();
        foreach ($camps AS $camp) {
            
            /** @var $item BaseItems */
            $item = BaseItems::find()->byCamp($camp->id)->active()->orderBy(['date_from' => SORT_ASC, 'date_to' => SORT_ASC])->one();
            
            if ($item) {
                $camp->updateAttributes(['min_price' => $item->getCurrentPrice()]);
            } else {
                $camp->updateAttributes(['min_price' => 0]);
            }
        }
    }
}
