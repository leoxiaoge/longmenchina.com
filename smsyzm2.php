<?php
require_once './include/common.incs.php';
//载入ucpass类
require_once('./lib/Ucpaas.class.php');
require_once('serverSid.php');

//Demo调用,参数填入正确后，放开注释可以调用
$tel=$_POST['mobile'];

$yzm=random(6);

$appid = "421cb038c7ab45a59f6dac86bb57979a";	//应用的ID，可在开发者控制台内的短信产品下查看
$templateid = "270126";    //可在后台短信产品→选择接入的应用→短信模板-模板ID，查看该模板ID
$param = $yzm; //多个参数使用英文逗号隔开（如：param=“a,b,c”），如为参数则留空
$mobile = $tel;
$uid = "";

//70字内（含70字）计一条，超过70字，按67字/条计费，超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。

$result2 = $db->sql_query("SELECT * FROM `{$tablepre}users` WHERE username='$tel' ");

if($bd2=$db->sql_fetchrow($result2)){

	$ucpass->SendSms($appid,$templateid,$param,$mobile,$uid);
	
	$db->sql_query("insert into `dy_yzm`(tel,yzm) values('$tel','".$yzm."')");
	//exit("insert into `dy_yzm`(uid,tel,yzm) values('0','$tel','".$yzm."')");
	echo $msg="您的手机短信验证码已发送到您的手机上!";

}else{
	
	$msg="您的手机号还未注册。请先注册，再点击登录！";
	
	echo $msg;		
}