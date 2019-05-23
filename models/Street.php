<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "street".
 *
 * @property int $id
 * @property string $street_name 街道名
 */
class Street extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'street';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['street_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'street_name' => 'Street Name',
        ];
    }
}
