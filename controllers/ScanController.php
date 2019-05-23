<?php

namespace app\controllers;
use app\models\Scan;
use app\components\Request;
use app\components\Output;

class ScanController extends \yii\web\Controller
{
    public function actionIndex()
    {
        
        return $this->render('index');
    }

   
    /**
     * 获取扫码信息
     */
    function data_scan(){
        $data = [
            "id" => Request::Post("id")?Request::Post("id"):"0",
            "openId" => Request::Post("openId"),
            "scan_number" => Request::Post("scan_number"),
            "company_number" => Request::Post("company_number")
        ];
        return $data;
    }

    /**
     * 扫码
     */
    public function actionSaveOrUpdateScan()
    {
        $data = $this->data_scan();
        if($data["openId"] != null && $data["openId"] != ""){
            if($this->get_Scan($data["openId"])){
                return Output::Code(500, $data, "用户已经扫码过，不能重复扫码");
            } else {
                $model = new Scan;
                $model->openId = $data["openId"];
                if($model->save() > 0){
                    return Output::Code(500, $model, "扫码成功");
                } else {
                    return Output::Code(500, $data, "扫码失败");
                }
            }
        }else {
            return Output::Code(500, $data, "用户标示不能为空");
        }
    }

    /**
     * 根据openid查询是否扫码过
     */
    function get_Scan($openId){
        $model= Scan::findBySql('SELECT * FROM Scan')->where(["openId" => $openId])->one();
        if($model != null && $model != ""){
            return true;
        } 
        return false;
    }


    public $coutt = 1;
    
    /**
     * 统计扫码数量
     */
    public function actionCountScan(){
        $this->coutt =  $this->coutt+1;
        $count= Scan::findBySql('SELECT * FROM Scan')->count();
       
        return $count + $this->coutt;
    }

}
