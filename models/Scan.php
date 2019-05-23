<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "scan".
 *
 * @property int $id
 * @property string $openId
 * @property int $scan_number
 * @property int $company_number
 */
class Scan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['scan_number', 'company_number'], 'integer'],
            [['openId'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'openId' => 'Open ID',
            'scan_number' => 'Scan Number',
            'company_number' => 'Company Number',
        ];
    }
}
