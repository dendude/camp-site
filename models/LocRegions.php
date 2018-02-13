<?php

namespace app\models;

use app\helpers\Normalize;
use app\models\queries\LocRegionsQuery;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%loc_regions}}".
 *
 * @property integer $id
 * @property integer $country_id
 * @property string $name
 * @property string $name_in
 * @property string $alias
 * @property string $content
 *
 * @property string $meta_t
 * @property string $meta_d
 * @property string $meta_k
 *
 * @property integer $created
 * @property integer $modified
 * @property integer $ordering
 * @property integer $status
 * @property integer $manager_id
 */
class LocRegions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%loc_regions}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'name_in', 'country_id', 'created', 'manager_id'], 'required'],
            
            [['country_id', 'created', 'modified', 'ordering', 'status', 'manager_id'], 'integer'],
            [['country_id', 'created', 'modified', 'ordering', 'status', 'manager_id'], 'default', 'value' => 0],
            
            [['name', 'name_in', 'alias'], 'string', 'max' => 100],
            [['name', 'name_in', 'alias'], 'default', 'value' => ''],

            [['meta_t', 'meta_d', 'meta_k'], 'string', 'max' => 250],
            [['meta_t', 'meta_d', 'meta_k'], 'default', 'value' => ''],
            
            ['alias', 'unique'],
            
            ['content', 'string'],
        ];
    }
    
    public function getCountry() {
        return $this->hasOne(LocCountries::className(), ['id' => 'country_id']);
    }
    
    public static function getFilterList($country_id, $full = false) {
        if (empty($country_id)) return [];
        
        $query = self::find()->byCountry($country_id)->ordering();
        if ($full) {
            $query->usage();
        } else {
            $query->active();
        }
        
        $list = $query->all();
        return $list ? ArrayHelper::map($list, 'id', 'name') : [];
    }
    
    public static function getFilterListWithCamps($country_id) {
        $ids = Camps::find()->joinWith('about')
            ->select('camp_camps_about.loc_region')
            ->active()->distinct()->column();

        $list = self::find()->where(['id' => $ids])
            ->byCountry($country_id)->active()->ordering()->all();
        
        return $list ? ArrayHelper::map($list, 'id', function(self $model){
            return $model->name . ' [' . Camps::find()->byRegion($model->id)->active()->count() .  ']';
        }) : [];
    }
    
    public static function getAliasById($region_id)
    {
        $model = self::findOne($region_id);
        return $model ? $model->alias : null;
    }
    
    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            $this->created = time();
        } else {
            $this->modified = time();
        }
    
        if (php_sapi_name() != "cli") $this->manager_id = Yii::$app->user->id;
        if (empty($this->alias)) $this->alias = Normalize::alias($this->name);
        
        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }
    
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return Normalize::withCommonLabels([
            'country_id' => 'Страна',
            'name' => 'Название',
            'name_in' => 'Название (где)',
        ]);
    }

    /**
     * @inheritdoc
     * @return LocRegionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LocRegionsQuery(get_called_class());
    }
}
