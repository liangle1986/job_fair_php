<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "resume".
 *
 * @property int $id
 * @property string $url_id 目前做为唯一图片链接地址
 * @property string $username 用户名
 * @property string $sex 1:男，0:女
 * @property string $identitycard 身份证
 * @property int $birthday YYYYMMDD
 * @property int $education  0:初中或以下，1:高中，2:中专，3:大专、高职，4：本科，5：研究生，6:博士，7：博士后，8:其他
 * @property string $province 省份
 * @property string $city 市
 * @property string $county 县/区
 * @property int $place 户籍地0.本区，1本市非本区，2外省市
 * @property string $domicile 现住地
 * @property string $phone 手机
 * @property int $status 0:公开，1:关闭，2：企业可看，3:投递可看
 * @property string $remark 简历详情
 * @property string $userId 简历关联用户id
 * @property string $resume_code 简历编码
 * @property string $record_date 登记日期
 * @property string $nation 民族
 * @property int $marital_status 婚姻状态，0:未婚，1:已婚，2:已婚已育
 * @property string $home_phone 家庭电话
 * @property int $personnel_type 0:失业，1:征地，2:协保，3:下岗，4:退休，5:应期毕业生，6：外来媳妇，7:退伍军人
 * @property string $Job_intention 求职意向
 * @property string $expected_income 期望收入
 * @property int $political_status 政治面貌 0 群众 1:党员 2:团员
 * @property string $street 街道
 * @property string $person_height 身高
 * @property string $leparray_data
 * @property string $workarray_data
 */
class Resume extends \yii\db\ActiveRecord
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
            [['birthday', 'education', 'place', 'status', 'marital_status', 'personnel_type', 'political_status'], 'integer'],
            [['record_date'], 'safe'],
            [['leparray_data', 'workarray_data'], 'string'],
            [['url_id', 'domicile', 'Job_intention', 'street'], 'string', 'max' => 200],
            [['username', 'identitycard', 'province', 'city', 'county', 'expected_income'], 'string', 'max' => 50],
            [['sex'], 'string', 'max' => 1],
            [['phone', 'resume_code', 'home_phone'], 'string', 'max' => 20],
            [['remark'], 'string', 'max' => 1000],
            [['userId'], 'string', 'max' => 32],
            [['nation', 'person_height'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url_id' => 'Url ID',
            'username' => 'Username',
            'sex' => 'Sex',
            'identitycard' => 'Identitycard',
            'birthday' => 'Birthday',
            'education' => 'Education',
            'province' => 'Province',
            'city' => 'City',
            'county' => 'County',
            'place' => 'Place',
            'domicile' => 'Domicile',
            'phone' => 'Phone',
            'status' => 'Status',
            'remark' => 'Remark',
            'userId' => 'User ID',
            'resume_code' => 'Resume Code',
            'record_date' => 'Record Date',
            'nation' => 'Nation',
            'marital_status' => 'Marital Status',
            'home_phone' => 'Home Phone',
            'personnel_type' => 'Personnel Type',
            'Job_intention' => 'Job Intention',
            'expected_income' => 'Expected Income',
            'political_status' => 'Political Status',
            'street' => 'Street',
            'person_height' => 'Person Height',
            'leparray_data' => 'Leparray Data',
            'workarray_data' => 'Workarray Data',
        ];
    }
}
