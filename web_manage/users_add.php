<?
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';
$id= (int)$_GET['id'];
$indexurl=$_GET['indexurl'];
 
$sql="select * from `{$tablepre}users` where id=".$id."";
$result=$db->sql_query($sql);
if($row=$db->sql_fetchrow($result)){
}

if(isset($_POST['add'])){
	$referer=$_POST['referer'];
 
	$sqlvalues="";
	$fields=$_POST['fields'];

	$fields['login']=trim($_POST['login']);
	$fields['username']=trim($_POST['username']);
	$fields['userpwd']=trim($_POST['userpwd']);
	$fields['xm']=trim($_POST['xm']);

   	$fields['ip']=$PHP_IP;
 	$fields['isstate']=1;
	$fields['sendtime']=$PHP_TIME;

  	while(list ($key,$val) =each ($fields)){
		$sqlvalues.=",$key='$val'";
	}
	$sqlvalues=substr($sqlvalues,1);

	$sql="INSERT INTO `{$tablepre}users` SET ".$sqlvalues;

	//exit($sql);
	$db->sql_query($sql);
	
	AddLog("添加会员信息",$_SESSION['Admin_username']);
	
	JsSucce("操作成功！","users.php?".$referer);
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>会员信息编辑</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/select.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="js/checkform.js"></script>
<script language="javascript" src="js/selectarea.js"></script>
</head>

<body>

<div class="place">
	<span>位置：</span>
	<ul class="placeul">
		<li><a href="mains.php">首页</a></li>
		<li>会员管理</li>
		<li>信息编辑</li>
	</ul>
</div>

<div class="formbody">

	<div id="usual1" class="usual"> 

		<div class="itab">
			<ul> 
				<li><a href="#tab1" class="selected">会员信息编辑</a></li> 
			</ul>
		</div> 

		<div id="tab1" class="tabson">

			<div class="formtext">Hi，<b><?=$_SESSION['Admin_UserName']?></b>，欢迎您使用信息发布功能！</div>

			<ul class="forminfo">

				<form action="" method="post" name="formlist" onSubmit="return news_check(this);">

					<input type="hidden" name="referer" value="<?=$indexurl?>">

					<input type="hidden" name="login" value="1">
					
					<li><label>姓名<b>*</b></label><input name="xm" type="text" class="dfinput" value=""/></li>

					<li><label>用户名<b>*</b></label><input name="username" type="text" class="dfinput" value=""/></li>

					<li><label>登录密码<b>*</b></label><input name="userpwd" type="text" class="dfinput" value=""/></li>

					<li><label>&nbsp;</label><input name="add" type="submit" class="btn" value="马上修改"/></li>

				</form>

			</ul>

		</div> 

	</div> 

</div>


</body>

</html>
