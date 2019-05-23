<?php

namespace app\controllers;
use yii;
use yii\caching\DbDependency;
use app\components\Output;
use app\components\STS;
use app\components\Request;

class OsskeyController extends \yii\web\Controller
{
    public function actionIndex()
    {
    	/*$cache = \YII::$app->cache;
    	$key=false;
    	$time=time();

    	
    	if(null !==$cache->get('osskeytime')){
    		if($cache->get('osskeytime')>$time){
    			$key=true;
    		}
    	}else{
    		$cache->add('osskeytime',0);
    		$cache->add('tempSessionToken',0);
    		$cache->add('tmpSecretId',0);
    		$cache->add('tmpSecretKey',0);
    	}

    	if(!$key){

			$sts = new STS();
			// 配置参数
			$config = array(
			    'url' => 'https://sts.tencentcloudapi.com/',
			    'domain' => 'sts.tencentcloudapi.com',
			    'proxy' => '',
			    'secretId' =>"AKIDwfruiHopnWzJoxaSr2yjvxZcjcItDVaV", // 固定密钥
			    'secretKey' => "2uX87bzZLyQdyxmOtNehAzueAUp33s3C", // 固定密钥
			    'bucket' => 'res-1251120695', // 换成你的 bucket
			    'region' => 'ap-guangzhou', // 换成 bucket 所在园区
			    'durationSeconds' => 1800, // 密钥有效期
			    'allowPrefix' => 'mhjczx/*', // 这里改成允许的路径前缀，可以根据自己网站的用户登录态判断允许上传的目录，例子：* 或者 a/* 或者 a.jpg
			    // 密钥的权限列表。简单上传和分片需要以下的权限，其他权限列表请看 https://cloud.tencent.com/document/product/436/31923
			    'allowActions' => array (
			        // 所有 action 请看文档 https://cloud.tencent.com/document/product/436/31923
			        // 简单上传
			        'name/cos:PutObject',
			        'name/cos:PostObject',
			        // 分片上传
			        'name/cos:InitiateMultipartUpload',
			        'name/cos:ListMultipartUploads',
			        'name/cos:ListParts',
			        'name/cos:UploadPart',
			        'name/cos:CompleteMultipartUpload'
			    )
			);
			// 获取临时密钥，计算签名
			$tempKeys = $sts->getTempKeys($config);
	
			$cache->set('osskeytime',$tempKeys["expiredTime"]);
			$cache->set('tempSessionToken' , $tempKeys["credentials"]["sessionToken"]);
			$cache->set('tmpSecretId',$tempKeys["credentials"]["tmpSecretId"]);
			$cache->set('tmpSecretKey', $tempKeys["credentials"]["tmpSecretKey"]);

    	}

    	$arr=array(
    		// 'key'=>$key,
    		'sessionToken'=>$cache->get('tempSessionToken'),
    		'secretId'=>$cache->get('tmpSecretId'),
    		'secretKey'=>$cache->get('tmpSecretKey')
    	);

        return Output::Code(200, $arr , "success");*/
        $sts = new STS();
	// 配置参数
	$config = array(
	    'url' => 'https://sts.tencentcloudapi.com/',
	    'domain' => 'sts.tencentcloudapi.com',
	    'proxy' => '',
		// 'secretId' =>"AKIDwfruiHopnWzJoxaSr2yjvxZcjcItDVaV", // 用户固定密钥
	    // 'secretKey' => "2uX87bzZLyQdyxmOtNehAzueAUp33s3C", // 用户固定密钥
	    'secretId' =>"AKIDUXUVIroUu53lDRg7av0uaO2taFfvEsY1",
	    'secretKey' => "TjdWM4XoumPdotnTEnBJm02yqWxCC2lI",
	    'bucket' => 'res-1251120695', // 换成你的 bucket
	    'region' => 'ap-shanghai', // 换成 bucket 所在园区
	    'durationSeconds' => 1800, // 密钥有效期
	    'allowPrefix' => 'mhjczx/*', // 这里改成允许的路径前缀，可以根据自己网站的用户登录态判断允许上传的目录，例子：* 或者 a/* 或者 a.jpg
	    // 密钥的权限列表。简单上传和分片需要以下的权限，其他权限列表请看 https://cloud.tencent.com/document/product/436/31923
	    'allowActions' => array (
	        // 所有 action 请看文档 https://cloud.tencent.com/document/product/436/31923
	        // 简单上传
	        'name/cos:PutObject',
	        'name/cos:PostObject',
	        // 分片上传
	        'name/cos:InitiateMultipartUpload',
	        'name/cos:ListMultipartUploads',
	        'name/cos:ListParts',
	        'name/cos:UploadPart',
	        'name/cos:CompleteMultipartUpload'
	    )
	);
	// 获取临时密钥，计算签名
	$tempKeys = $sts->getTempKeys($config);
	// 返回数据给前端
	header('Content-Type: application/json');
	header('Access-Control-Allow-Origin: http://127.0.0.1'); // 这里修改允许跨域访问的网站
	header('Access-Control-Allow-Headers: origin,accept,content-type');
	return json_encode($tempKeys);
    	
    }

}
