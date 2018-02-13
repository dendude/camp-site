<?php
namespace app\models\queries;

use Yii;
use app\helpers\Statuses;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Orders]].
 *
 * @see Orders
 */
class OrdersQuery extends ActiveQuery
{
    public function waiting() {
        return $this->andWhere(['camp_orders.status' => Statuses::STATUS_NEW]);
    }

    public function using() {
        return $this->andWhere('camp_orders.status != :removed', [':removed' => Statuses::STATUS_REMOVED]);
    }
    
    public function byPartner($partner_id) {
        return $this->andWhere(['camp_orders.partner_id' => $partner_id]);
    }
    
    public function byUser($user_id) {
        return $this->andWhere(['camp_orders.user_id' => $user_id]);
    }
    
    public function bySelf() {
        return $this->byUser(Yii::$app->user->id);
    }
}
