<?php

namespace app\controllers;
use Yii;
use app\models\User;
use app\components\Output;
use app\components\Request;
/**
 * 签到点信息
 */
class RecruitsignController extends \yii\web\Controller
{
    /**
     * 统计签到数申请一次加一次
     */
   private static $signCount = 0;

    public function init(){
        $this->enableCsrfValidation = false;
    }

    public function actionIndex()
    {   
        
        if(!(time()>strtotime(date('2019-05-11')))){
            $openid = Yii::$app->request->get("openid");
            $count = User::find()->where(['openid'=>$openid,'sign'=>0])->count();
            
            if($count!=0){
                $sql="update `user` set sign=1 where openid='".$openid."';";
                Yii::$app->db->createCommand($sql)->query(); 
                
                return Output::Code(200, "", "签到成功。");
                
            } else {
                return Output::Code(400, "", "已经签到过，不能重复签到。");
              
            }
        }else{
            return Output::Code(401,'','签到未开始');
        }
        
     
    }

    /**
     * 获取招聘会用户签到数量
     */
    public function actionCountSign() {
        $type = Request::Get("type");
        try{
            $count = User::find()->where(["sign"=> 1])->count();
             Output::setSign(10);
            if($type == 1){
               
                echo Output::getSign();
                $count =  $count + Output::getSign();
            }
           return Output::Code(200, $count, "success");
        }catch(Exception $e) {
            return Output::Code(500, "查询签到数量失败。", "fail");
        }
    }

}
