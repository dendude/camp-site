<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%camp_users_actions}}".
 *
 * @property integer $id
 */
class UsersActions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%camp_users_actions}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ,
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
        ];
    }

    /**
     * @inheritdoc
     * @return UsersActionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UsersActionsQuery(get_called_class());
    }
}
