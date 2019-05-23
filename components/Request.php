<?php


namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;


class Request extends Component
{
    /**
     * get获取值
     */
    public static function Get($value){
        if(Yii::$app->request->get($value)!==null ){
            return urldecode(Yii::$app->request->get($value));
        } else {
            return "";
        }
        
    }
     /**
     * post获取值
     */
    public static function Post($value){
        if(Yii::$app->request->post($value)!==null){
            return urldecode(Yii::$app->request->post($value));
        }else{
            return "";
        }
       
    }

    /**
     * 绑定数量
     */
    public static $bind_number =2;
}
