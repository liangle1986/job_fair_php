<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "company_fuli".
 *
 * @property int $company_id
 * @property int $fuli_id
 * @property int $id
 */
class CompanyFuli extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company_fuli';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'fuli_id'], 'required'],
            [['company_id', 'fuli_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'company_id' => 'Company ID',
            'fuli_id' => 'Fuli ID',
            'id' => 'ID',
        ];
    }
}
