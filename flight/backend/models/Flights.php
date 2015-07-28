<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "flights".
 *
 * @property integer $id
 * @property integer $from
 * @property integer $to
 * @property integer $back
 * @property string $start
 * @property string $stop
 * @property integer $adult
 * @property integer $child
 * @property integer $infant
 * @property string $price
 */
class Flights extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'flights';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from', 'to', 'back', 'adult', 'child', 'infant'], 'integer'],
            [['start', 'stop'], 'safe'],
            [['price'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from' => 'From',
            'to' => 'To',
            'back' => 'Back',
            'start' => 'Start',
            'stop' => 'Stop',
            'adult' => 'Adult',
            'child' => 'Child',
            'infant' => 'Infant',
            'price' => 'Price',
        ];
    }

    /**
     * @inheritdoc
     * @return FlightsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FlightsQuery(get_called_class());
    }
    
    public function beforeSave($insert) {
        
        // разруливаем аэропорты
        
        
        
        
        parent::beforeSave($insert);
    }
}
