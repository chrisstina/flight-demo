<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "airports".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property integer $contry
 */
class Airports extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'airports';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contry'], 'integer'],
            [['code'], 'string', 'max' => 3],
            [['name'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'contry' => 'Contry',
        ];
    }

    /**
     * @inheritdoc
     * @return AirportsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AirportsQuery(get_called_class());
    }
}
