<?php
require_once './include/common.incs.php';
/*
 *  Copyright (c) 2014 The CCP project authors. All Rights Reserved.
 *
 *  Use of this source code is governed by a Beijing Speedtong Information Technology Co.,Ltd license
 *  that can be found in the LICENSE file in the root of the web site.
 *
 *   http://www.yuntongxun.com
 *
 *  An additional intellectual property rights grant can be found
 *  in the file PATENTS.  All contributing project authors may
 *  be found in the AUTHORS file in the root of the source tree.
 */

include_once("CCPRestSDK.php");

//主帐号
$accountSid= '8aaf070859aa48b00159b0d3a57d048e';

//主帐号Token
$accountToken= '745cae1d3c874f84829faec266e51c28';

//应用Id
$appId='8aaf070859aa48b00159b0d3a6070494';

//请求地址，格式如下，不需要写https://
$serverIP='app.cloopen.com';

//请求端口 
$serverPort='8883';

//REST版本号
$softVersion='2013-12-26';

/**
  * 发送模板短信
  * @param to 手机号码集合,用英文逗号分开
  * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
  * @param $tempId 模板Id
  */       
function sendTemplateSMS($to,$datas,$tempId){
     // 初始化REST SDK
     global $accountSid,$accountToken,$appId,$serverIP,$serverPort,$softVersion;
     $rest = new REST($serverIP,$serverPort,$softVersion);
     $rest->setAccount($accountSid,$accountToken);
     $rest->setAppId($appId);
    
     // 发送模板短信
     //echo "Sending TemplateSMS to $to <br/>";
     $result = $rest->sendTemplateSMS($to,$datas,$tempId);
     if($result == NULL ) {
         //echo "result error!";
         break;
     }
     if($result->statusCode!=0) {
         //echo "error code :" . $result->statusCode . "<br>";
         //echo "error msg :" . $result->statusMsg . "<br>";
         //TODO 添加错误处理逻辑
     }else{
         //echo "Sendind TemplateSMS success!<br/>";
         // 获取返回信息
         $smsmessage = $result->TemplateSMS;
         //echo "dateCreated:".$smsmessage->dateCreated."<br/>";
         //echo "smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
         //TODO 添加成功处理逻辑
     }
}

//Demo调用,参数填入正确后，放开注释可以调用
$tel=$_POST['mobile'];

$yzm=random(6);

$result2 = $db->sql_query("SELECT * FROM `{$tablepre}users` WHERE phone='$tel' ");

if($bd2=$db->sql_fetchrow($result2)){

	$result=sendTemplateSMS($tel,array($yzm,'60秒'),"154131");
	
	if($result){
	
	}else{
		$db->sql_query("insert into `dy_yzm`(uid,tel,yzm) values('0','$tel','".$yzm."')");
		//exit("insert into `dy_yzm`(uid,tel,yzm) values('0','$tel','".$yzm."')");
		$msg="您的手机短信验证码已发送到您的手机上!";
	}
	
	echo $msg;	

}else{
	
	$msg="您的手机号还未注册。请先注册，再点击登录！";
	
	echo $msg;		
}


//sendTemplateSMS("手机号","验证码","模板id");
?>
