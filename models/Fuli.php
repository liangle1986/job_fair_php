<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fuli".
 *
 * @property int $id
 * @property string $fuli
 */
class Fuli extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fuli';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fuli'], 'required'],
            [['fuli'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fuli' => 'Fuli',
        ];
    }
}
