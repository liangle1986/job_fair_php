<?php
//
namespace app\controllers;

use linslin\yii2\curl;
use app\models\User;
use app\components\Output;
use app\components\Request;

class CandidateController extends \yii\web\Controller
{
    public function actionIndex()
    {
    	if(isset($_GET["code"]) && isset($_GET["rawData"]) && isset($_GET["signature"]) && isset($_GET["encryptedData"]) && isset($_GET["iv"])){
    		$wxdata=array(
				"code"=>Request::Get("code"),
				"rawData"=>Request::Get("rawData"),
				"signature"=>Request::Get("signature"),
				"encryptedData"=>Request::Get("encryptedData"),
				"iv"=>Request::Get("iv")
			);
			       
	       	//$url='https://api.weixin.qq.com/sns/jscode2session?appid=wxb75642b42cc5707f&secret=d637acdfd6e4e98d884571c9276fec86&js_code='.$wxdata['code'].'&grant_type=authorization_code';//test
	       	$url='https://api.weixin.qq.com/sns/jscode2session?appid=wxf511d3207468cbd9&secret=c1dfa109f5236860bb3093df2251c4f7&js_code='.$wxdata['code'].'&grant_type=authorization_code';//mhjczx
		    $curl = new curl\Curl();
		    
		    // $response = $curl->reset()->setOption(
		    //     CURLOPT_POSTFIELDS,
		    //     http_build_query(array(
		    //             'text' => $value
		    //         )
		    //     ))->post($url);
		    // return $curl->response;
		    //get
		    
		    // $curl = new Curl();
		    $curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
		    $response = $curl->get($url);
		    $usermsg=json_decode($response);
		    
		    $check=User::find()->where(['openid' => $usermsg->openid])->one();
	    	if($check){
	    		//已存�?
	    		$out=array(
						'openid'=>$usermsg->openid,
						'linktype'=>$check->type//跳转目标
				);
	    		return Output::Code(200,$out,"success");
	    	}else{
	    		//不存在，执行插入动作
	    		$model = new User;
				$model->openid = $usermsg->openid;
				$model->session_key  = $usermsg->session_key;
				$model->type  = Request::Get('signtype');	
				$model->sign  = 0;				
				if($model->save() > 0){
					$out=array(
						'openid'=>$usermsg->openid,
						'linktype'=>Request::Get('signtype')//跳转目标
					);
					return Output::Code(200,$out,"success");
				}else{
					return Output::Code(500,$out,"fail");
				}
	    	}
    	}else{
    		return Output::test();
    	}
    	
    	
    }

}
