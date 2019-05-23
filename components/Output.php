<?php


namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;


class Output extends Component
{

    public static function Code($code,$data,$msg)
    {
        $arr=array(
            'code'=>$code,
            'data'=>$data,
            'msg'=>$msg
        );

        return json_encode($arr);
    }
    public function Test(){
        echo "success";
    }

    /**
     * 签到设置数
     */
    public static $sign = 0;

    public static function setSign($count){
        self::$sign= self::$sign + $count;
    }
    public static function getSign(){
        return self::$sign;
    }
}
