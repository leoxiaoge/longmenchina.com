<? 
require_once './include/common.incs.php';

if($System_Isstate=='1'){
	exit("{$System_Showinfo}");
}

$temp = strtoupper(urldecode(urldecode($_SERVER['REQUEST_URI'])));
if(strpos($temp, '<') !== false || strpos($temp, '"') !== false || strpos($temp, 'CONTENT-TRANSFER-ENCODING') !== false)
{
	exit('非法操作');
}

if((int)$_COOKIE['sys_guestid']){
	$guestid=(int)$_COOKIE['sys_guestid'];
	$cartnums=get_count("temp"," AND isstate=0 AND guestid={$guestid}");
}else{
	setcookie("sys_guestid", time(), time()+3600*24*365);  
	$cartnums=get_count("temp"," AND isstate=0 AND uid={$uid}");
}

$uid=(int)$_COOKIE['sys_uid'];
$PHP_URL=basename($PHP_SELF);

$id=(int)opturl("id");
$pid=(int)opturl("pid");
$ty=(int)opturl("ty");
$tty=(int)opturl("tty");
      
if($mypid) $pid=$mypid; else $pid=$pid;
if($myty) $ty=$myty; else $ty=$ty;
if($mytty) $tty=$mytty; else $tty=$tty;

if($Title) $System_seotitle=$Title; else $System_seotitle=$System_seotitle;
if($Keywords) $System_keywords=$Keywords; else $System_keywords=$System_seokeywords;
if($Info) $System_info=$Info; else $System_info=$System_seodescription;
if($System_seotitle) $System_seotitle="{$System_seotitle}_";else $System_seotitle="";

if($_POST['send']==3){
	$uri=$_POST['uri'];
  	$username=trim($_POST['username']);
	$userpwd=trim($_POST['userpwd']);
 	$result = $db->sql_query("SELECT * FROM `{$tablepre}users` WHERE (username='$username' or email='$username' or tel='$username') AND binary userpwd='$userpwd'");
 	if($bd=$db->sql_fetchrow($result)){
		if($uri) $tourl="{$uri}"; else $tourl="./main.php";
		if((int)$bd['isstate']==0){
			JsSucce('对不起，您的帐户暂时未经审核！\\r\\n请联系您的帐户管理员，以方便正常使用！','./');
			exit();
		}
		if($_POST['remember']) $n=30;else $n=1;
 		setcookie("sys_uid", $bd['id'], time()+3600*24*$n,"/");  
 		setcookie("sys_username", $bd['username'], time()+3600*24*$n,"/"); 
		setcookie("sys_lastlogintime", $bd['lastlogintime'], time()+3600*24*$n,"/"); 
    		
 		$sql="UPDATE `{$tablepre}users` SET lastlogintime='$PHP_TIME' WHERE username='".$username."'";
		$db->sql_query($sql);
 			JsGourl($tourl);
		exit();
	}else{
			JsError("对不起，您的用户名或密码错误！");
		exit();
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title><?=$System_seotitle?><?=$System_sitename?></title>
<meta name="keywords" content="<?=$System_keywords?>">
<meta name="description" content="<?=$System_info?>">

<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="format-detection" content="telephone=yes">

<link rel="stylesheet" href="css/base.css" />
<link rel="stylesheet" href="css/project.css" />
<link rel="stylesheet" href="css/animate.min.css" />
<link rel="stylesheet" href="css/owl.carousel.css" />
<link rel="stylesheet" href="css/css3.css" />
<script type="text/javascript" src="js/jQuery.js"></script>
<script type="text/javascript" src="js/owl.carousel.js"></script>

<script src="js/checkform.js"></script>

</head>
<body>

<div class="fanhui">
	<a href="javascript:history.back();"><img src="img/img98.jpg" /></a>
</div>

<style>
html,
body {
	height: 100%;
}
</style>

<div class="logs">
	<div class="box">
		<div class="form">
			<div class="title">
				<span class="on">个人版</span>
				<span>企业版</span>
				<span>政府机构</span>
			</div>
			<div class="listvest" style="padding-top: 20px;">
				<div class="list">
					<p style="display: block; text-align: center; margin-bottom: 30px;">
						<img src="./uploadfile/upload/<?=$System_img1?>" />
					</p>
					<form action="" method="post" name="formlist" onSubmit="return check_login(this);">
						<input type="hidden" name="send" value="3" />
						<div class="l"><input type="text" class="tel" placeholder="请输入您的手机号码" name="username"/></div>
						<div class="l"><input type="password" class="pass" placeholder="请输入您的密码" name="userpwd"></div>
						<div class="but"><input type="submit" value="登录"/></div>
						<div class="info">若无帐号？<a href="register.php">点击注册</a> <a href="getpwd.php">忘记密码</a></div>
					</form>
				</div>
				<div class="list">
					<p style="display: block; text-align: center; margin-bottom: 30px;">
						<img src="./uploadfile/upload/<?=$System_img1?>" />
					</p>
					<form action="" method="post" name="formlist" onSubmit="return check_login(this);">
						<input type="hidden" name="send" value="3" />
						<div class="l"><input type="text" class="tel" placeholder="请输入您的手机号码" name="username"/></div>
						<div class="l"><input type="password" class="pass" placeholder="请输入您的密码" name="userpwd"></div>
						<div class="but"><input type="submit" value="登录"/></div>
						<div class="info">没有帐号？<a href="register.php">点击注册</a> <a href="getpwd.php">忘记密码</a></div>
					</form>
				</div>
				<div class="list">
					<p style="display: block; text-align: center; margin-bottom: 30px;">
						<img src="./uploadfile/upload/<?=$System_img1?>" />
					</p>
					<form action="" method="post" name="formlist" onSubmit="return check_login(this);">
						<input type="hidden" name="send" value="3" />
						<div class="l"><input type="text" class="tel" placeholder="请输入您的手机号码" name="username"/></div>
						<div class="l"><input type="password" class="pass" placeholder="请输入您的密码" name="userpwd"></div>
						<div class="but"><input type="submit" value="登录"/></div>
						<div class="info">没有帐号？<a href="register.php">点击注册</a> <a href="getpwd.php">忘记密码</a></div>
					</form>
				</div>
				<script>
				$(function() {
					$(' .logs .listvest .list').eq(0).show();
					$(' .logs .title span').click(function() {
						$(this).addClass('on').siblings().removeClass('on');
						$('.logs .listvest .list').eq($(this).index()).fadeIn().siblings().hide();
					})
				})
				</script>
			</div>
		</div>
	</div>
</div>

</body>
</html>