<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "demo".
 *
 * @property int $id
 * @property string $keya
 * @property string $keyb
 * @property string $keyc
 * @property string $keyd
 */
class Demo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'demo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['keya', 'keyb', 'keyc', 'keyd'], 'required'],
            [['keya', 'keyb', 'keyc', 'keyd'], 'string', 'max' => 11],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'keya' => 'Keya',
            'keyb' => 'Keyb',
            'keyc' => 'Keyc',
            'keyd' => 'Keyd',
        ];
    }
}
