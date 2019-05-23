<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "resume".
 *
 * @property int $id
 * @property string $name
 * @property string $url_id 目前做为唯一图片链接地址
 * @property string $username 用户名
 * @property int $sex 1:男，2:女
 * @property string $identitycard 学历
 * @property string $education 政治面貌
 * @property int $age 年龄
 * @property string $province 省份
 * @property string $city 市
 * @property string $county 县/区
 * @property string $place 户籍地
 * @property string $domicile 现住地
 * @property string $phone 手机
 * @property int $status 1:公开，2:关闭，3：企业可看，4:投递可看
 * @property string $remark 简历详情
 * @property int $userId 简历关联用户id
 */
class ResumeInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'resume';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'url_id', 'username', 'sex', 'identitycard', 'education', 'age', 'province', 'city', 'county', 'place', 'domicile', 'phone', 'status', 'remark', 'userId'], 'required'],
            [['sex', 'age', 'status', 'userId'], 'integer'],
            [['name', 'username', 'identitycard', 'education', 'province', 'city', 'county'], 'string', 'max' => 50],
            [['url_id', 'place', 'domicile'], 'string', 'max' => 200],
            [['phone'], 'string', 'max' => 11],
            [['remark'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'url_id' => 'Url ID',
            'username' => 'Username',
            'sex' => 'Sex',
            'identitycard' => 'Identitycard',
            'education' => 'Education',
            'age' => 'Age',
            'province' => 'Province',
            'city' => 'City',
            'county' => 'County',
            'place' => 'Place',
            'domicile' => 'Domicile',
            'phone' => 'Phone',
            'status' => 'Status',
            'remark' => 'Remark',
            'userId' => 'User ID',
        ];
    }
}
