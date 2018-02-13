<?php
namespace app\traits;

use app\models\Users;

/**
 * Class CommonBeforeValidate
 *
 * @property boolean isNewRecord
 * @property integer manager_id
 * @property integer created
 * @property integer modified
 *
 * @package app\traits
 */
trait TraitBeforeValidate
{
    public function traitBeforeValidate()
    {
        if ($this->isNewRecord) {
            $this->created = time();
        } else {
            $this->modified = time();
        }
        
        if (Users::isAdmin()) $this->manager_id = \Yii::$app->user->id;
    }
}