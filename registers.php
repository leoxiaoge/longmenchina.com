<? 
$Title="欢迎注册";
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

//echo $uid;

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

	$fields['login']=trim($_POST['login']);
	$fields['xm']=trim($_POST['xm']);
	$fields['tel']=trim($_POST['tel']);
	$fields['email']=trim($_POST['email']);
	$fields['yxfw']=trim($_POST['yxfw']);
	$fields['yxhy']=trim($_POST['yxhy']);
	$fields['province']=trim($_POST['province']);
	$fields['szxt']=trim($_POST['szxt']);
	$fields['gsname']=trim($_POST['gsname']);
	$fields['province']=trim($_POST['province']);

	while(list ($key,$val) =each ($fields)){
		$sqlvalues.=",$key='$val'";
	}

	$sqlvalues=substr($sqlvalues,1);

	$sql="UPDATE `{$tablepre}users` SET ".$sqlvalues." where id={$uid}";
	//exit($sql);

	if($db->sql_query($sql)){
		JsSucce("完善成功","./main.php");
	}else{
		JsError("完善失败，请稍后再试！");
	}
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
<body style="background: #b31c27;">

<form action="" method="post" name="formlist" onSubmit="return check_reg2(this);">
	<input name="send" type="hidden" value="2"/>

	<? if($_GET['login']==1){ ?>
	
	<input name="login" type="hidden" value="1"/>

	<div class="rest_info">
	
		<img src="img/p13.png" class="user_img" />
	
		<p class="t1">个人基本信息</p>
	
		<div class="li"><input type="text" placeholder="姓名" name="xm"/></div>
	
		<div class="li"><input type="text" placeholder="手机" name="tel"/></div>
	
		<div class="li"><input type="text" placeholder="邮箱" name="email"/></div>
	
		<div class="li cle">
			<select class="fl" name="yxfw">
				<option value="">意向服务</option>
				<option value="政务猎头">政务猎头</option>
				<option value="职业转型">职业转型</option>
				<option value="政务沙龙">政务沙龙</option>
			</select>
			<select class="fr" name="yxhy">
				<option value="">意向行业</option>
				<option value="大地产">大地产</option>
				<option value="大金融">大金融</option>
				<option value="大健康">大健康</option>
				<option value="IT互联网">IT互联网</option>
				<option value="大文娱">大文娱</option>
				<option value="新兴产业">新兴产业</option>
				<option value="其它行业">其它行业</option>
			</select>
		</div>
		
		<div class="li map">
			<select class="fr" name="province" id="province" onChange="changepro('city','province');">
				<option value="" selected="selected">所在城市</option>
				<option value='北京市'>北京市</option>
				<option value='天津市'>天津市</option>
				<option value='河北省'>河北省</option>
				<option value='山西省'>山西省</option>
				<option value='内蒙古区'>内蒙古区</option>
				<option value='辽宁省'>辽宁省</option>
				<option value='吉林省'>吉林省</option>
				<option value='黑龙江省'>黑龙江省</option>
				<option value='上海市'>上海市</option>
				<option value='江苏省'>江苏省</option>
				<option value='浙江省'>浙江省</option>
				<option value='安徽省'>安徽省</option>
				<option value='福建省'>福建省</option>
				<option value='江西省'>江西省</option>
				<option value='山东省'>山东省</option>
				<option value='河南省'>河南省</option>
				<option value='湖北省'>湖北省</option>
				<option value='湖南省'>湖南省</option>
				<option value='广东省'>广东省</option>
				<option value='广西区'>广西区</option>
				<option value='海南省'>海南省</option>
				<option value='重庆市'>重庆市</option>
				<option value='四川省'>四川省</option>
				<option value='贵州省'>贵州省</option>
				<option value='云南省'>云南省</option>
				<option value='西藏区'>西藏区</option>
				<option value='陕西省'>陕西省</option>
				<option value='甘肃省'>甘肃省</option>
				<option value='青海省'>青海省</option>
				<option value='宁夏区'>宁夏区</option>
				<option value='新疆区'>新疆区</option>
			</select>
		</div>
		
		<div class="li"><input type="text" placeholder="所在系统" name="szxt"/></div>
		
		<div class="but"><input type="submit" value="完成" /></div>
	
	</div>
	
	<? }elseif($_GET['login']==2){ ?>
	
	<input name="login" type="hidden" value="2"/>

	<div class="rest_info">

		<img src="img/p13.png" class="user_img" />

		<p class="t1">企业基本信息</p>

		<div class="li"><input type="text" placeholder="姓名" name="xm" /></div>

		<div class="li"><input type="text" placeholder="手机" name="tel" /></div>

		<div class="li"><input type="text" placeholder="公司名称" name="gsname" /></div>

		<div class="li cle">
			<select class="fl" name="yxfw">
				<option value="">意向服务</option>
				<option value="政务猎头">政务猎头</option>
				<option value="职业转型">职业转型</option>
				<option value="政务沙龙">政务沙龙</option>
			</select>
			<select class="fr" name="yxhy">
				<option value="">意向行业</option>
				<option value="大地产">大地产</option>
				<option value="大金融">大金融</option>
				<option value="大健康">大健康</option>
				<option value="IT互联网">IT互联网</option>
				<option value="大文娱">大文娱</option>
				<option value="新兴产业">新兴产业</option>
				<option value="其它行业">其它行业</option>
			</select>
		</div>
		
		<div class="li map">
			<select class="fr" name="province" id="province" onChange="changepro('city','province');">
				<option value="" selected="selected">所在城市</option>
				<option value='北京市'>北京市</option>
				<option value='天津市'>天津市</option>
				<option value='河北省'>河北省</option>
				<option value='山西省'>山西省</option>
				<option value='内蒙古区'>内蒙古区</option>
				<option value='辽宁省'>辽宁省</option>
				<option value='吉林省'>吉林省</option>
				<option value='黑龙江省'>黑龙江省</option>
				<option value='上海市'>上海市</option>
				<option value='江苏省'>江苏省</option>
				<option value='浙江省'>浙江省</option>
				<option value='安徽省'>安徽省</option>
				<option value='福建省'>福建省</option>
				<option value='江西省'>江西省</option>
				<option value='山东省'>山东省</option>
				<option value='河南省'>河南省</option>
				<option value='湖北省'>湖北省</option>
				<option value='湖南省'>湖南省</option>
				<option value='广东省'>广东省</option>
				<option value='广西区'>广西区</option>
				<option value='海南省'>海南省</option>
				<option value='重庆市'>重庆市</option>
				<option value='四川省'>四川省</option>
				<option value='贵州省'>贵州省</option>
				<option value='云南省'>云南省</option>
				<option value='西藏区'>西藏区</option>
				<option value='陕西省'>陕西省</option>
				<option value='甘肃省'>甘肃省</option>
				<option value='青海省'>青海省</option>
				<option value='宁夏区'>宁夏区</option>
				<option value='新疆区'>新疆区</option>
			</select>
		</div>
		
		<div class="li">
			<textarea placeholder="人才招聘需求" name="szxt"></textarea>
		</div>
		
		<div class="but">
			<input type="submit" value="完成" />
		</div>
	</div>
	
	<? }elseif($_GET['login']==3){ ?>

	<input name="login" type="hidden" value="3"/>

	<div class="rest_info">

		<img src="img/p13.png" class="user_img" />

		<p class="t1">基本信息</p>

		<div class="li"><input type="text" placeholder="姓名" name="xm"/></div>

		<div class="li"><input type="text" placeholder="手机" name="tel" /></div>

		<div class="li"><input type="text" placeholder="单位名称" name="gsname"/></div>

		<div class="li"><textarea placeholder="招才引智需求" name="szxt"></textarea></div>

		<div class="but"><input type="submit" value="完成" /></div>
	</div>

	<? } ?>

</form>
 
</body>
</html>