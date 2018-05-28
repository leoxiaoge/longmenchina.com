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
 
	$sqlvalues="";
	$fields=$_POST['fields'];

	$fields['userpwd']=trim($_POST['password1']);

	while(list ($key,$val) =each ($fields)){
		$sqlvalues.=",$key='$val'";
	}

	$sqlvalues=substr($sqlvalues,1);

	$sql="UPDATE `{$tablepre}users` SET ".$sqlvalues." where username='{$_GET['username']}'";
	//exit($sql);
	$db->sql_query($sql);

	JsSucce("密码修改成功 ！","login.php");
	exit();

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
				<span>密码修改</span>
				 
				<!-- <a href="#" class="fr">我要找人></a> -->
			</p>

			<div class="cle">
				<div class="form fl">
					
					<form action="" method="post" name="formlist" onSubmit="return check_getpwd2(this);">

						<input name="send" type="hidden" value="2"/>
					
						<img src="img/img87.jpg" class="g3"  />

						<div class="li cle">
							<input type="password" placeholder="请输入新密码" name="password1" />
						</div>
	
						<div class="li cle">
							<input type="password" placeholder="请输入确认新密码" name="password2" />
						</div>

						<div class="but cle"><input type="submit" class="but" value="提交"/></div>

					</form>

				</div>

			 	<div class="fl infos">想起密码:<p><a href="login.php">去登录</a></p></div>

			</div>

		</div>

	</div>

</div>

</body>
</html>