<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Flights]].
 *
 * @see Flights
 */
class FlightsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Flights[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Flights|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}