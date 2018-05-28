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


if($pid&&$id==0){
 	if($ty) $ty=$ty; else $ty=get_nextid($pid);
 	$sql="select * from `{$tablepre}news_cats` where pid={$pid} and id=".$ty." order by id desc";
	// echo $sql;
   	$result=$db->sql_query($sql);
	if($bd=$db->sql_fetchrow($result)){
		$Title=$bd['catname'];
		$Seotitle=$bd['seotitle'];
		if($Seotitle) $Title=$Seotitle; else $Title=$Title;
		$Keywords=$bd['seokeywords'];
		$Info=$bd['seodescription'];
 		$showtype=$bd['showtype'];
 	}
}elseif($id==0){
	$System_seotitle=$System_seotitle;
	$Keywords=$System_keywords;
	$Info=$System_info;
}

if($Title) $System_seotitle=$Title; else $System_seotitle=$System_seotitle;
if($Keywords) $System_keywords=$Keywords; else $System_keywords=$System_seokeywords;
if($Info) $System_info=$Info; else $System_info=$System_seodescription;
if($System_seotitle) $System_seotitle="{$System_seotitle}_";else $System_seotitle="";

if($_POST['send']==2){

	if($_POST["tpyzm"] != $_SESSION['code'] || empty($_POST["tpyzm"])){ 
		JsError('验证码出错！');
		exit();
	}

	$username=trim($_POST['username']);
	$yzm=trim($_POST['yzm']);

	$result2 = $db->sql_query("SELECT * FROM `{$tablepre}yzm` WHERE tel='$username' AND yzm='$yzm' and isstate=0 ");

 	if($bd2=$db->sql_fetchrow($result2)){

		$db->sql_query("UPDATE `{$tablepre}yzm` SET isstate=1 where id={$bd2['id']}");

		//检查用户名
		$result = $db->sql_query("SELECT username FROM `{$tablepre}users` WHERE username='".$username."'");

		if($bd=$db->sql_fetchrow($result)){
			JsGourl("getpwd2.php?username={$username}");
			exit();
		}else{
			JsError("暂无该用户！");
			exit();
		}
	}else{
		JsError("手机验证码错误，请稍后再试！");
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

<body style="background: #f8f9fc;">

<div class="rest_warp">

	<i></i>

	<div class="dome dome1">

		<div class="box">
			<p class="title cle">
				<span>找回密码</span>
			</p>

			<div class="cle">
				<div class="form fl">
					
					<form action="" method="post" name="formlist" onSubmit="return check_reg(this);">

						<input name="send" type="hidden" value="2"/>
					
						<img src="img/img87.jpg" class="g3"  />

						<div class="li cle">
							<input type="text" placeholder="请输入常用手机号码" name="username" id="t_tel"/>
						</div>

						<div class="li cle">
							<input type="text" placeholder="请证明你不是机器人" class="yzm" name="tpyzm"/>
							<img src="include/yzm.php" onClick="this.src=this.src+'?'" id="vdimgck" style="cursor:pointer" class="fr yzm_but" />
						</div>

						<div class="li cle">
							<input type="text" placeholder="请输入验证码" class="yzm" name="yzm"/>
							<input type="button" class="fr hq_yzm" value="获取验证码" id="zphone" onClick="get_mobile_code();" />
						</div>

						<div class="but cle"><input type="submit" class="but" value="提交"/></div>

					</form>

				</div>

				<!--main end-->
				<script type="text/javascript">
				function get_mobile_code(){
					var tels=document.getElementById('t_tel').value;
					if (tels==''){
						alert("提示：请输入手机号！");
						return false;
					}
					var telcode=new RegExp(/^\d{11}$/);
					if (!document.getElementById('t_tel').value.match(telcode)) {
						alert("提示：请输入正确的手机号码!");
						return false;                
					}
					$.post('smsyzm2.php', {mobile:jQuery.trim($('#t_tel').val())}, function(msg) {
						alert(msg);
						RemainTime();
					});
				};
				var iTime = 59;
				var Account;
				function RemainTime(){
					document.getElementById('zphone').disabled = true;
					var iSecond,sSecond="",sTime="";
					if (iTime >= 0){
						iSecond = parseInt(iTime%60);
						iMinute = parseInt(iTime/60)
						if (iSecond >= 0){
							if(iMinute>0){
								sSecond = iMinute + "分" + iSecond + "秒";
							}else{
								sSecond = iSecond + "秒";
							}
						}
						sTime=sSecond;
						if(iTime==0){
							clearTimeout(Account);
							sTime='获取手机验证码';
							iTime = 59;
							document.getElementById('zphone').disabled = false;
						}else{
							Account = setTimeout("RemainTime()",1000);
							iTime=iTime-1;
						}
					}else{
						sTime='没有倒计时';
					}
					document.getElementById('zphone').value = sTime;
				}	
				</script>

				<div class="fl infos">
					想起密码:
					<p><a href="login.php">去登录</a></p>
				</div>

			</div>

		</div>

	</div>

</div>

</body>
</html>