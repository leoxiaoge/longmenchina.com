<?
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';

if(isset($_POST['update'])){
	$newpwd=md5($_POST['pwd_new']);
	$oldpwd=md5($_POST['pwd_old']);
	
	$sql="select Id FROM `{$tablepre}manager` where username='".$_SESSION['Admin_UserName']."' and password='".$oldpwd."'";
	//exit($sql);
	$result=$db->sql_query($sql);
	$PageCount=$db->sql_numrows($result);
	if($PageCount==0){
		JsError("信息提示:原密码错误!");
	}else{
		$sql="update `{$tablepre}manager` SET password='".$newpwd."' where username='".$_SESSION['Admin_UserName']."'";
		$db->sql_query($sql);
		
		AddLog("编辑普通管理员密码",$_SESSION['Admin_UserName']);
		
		JsSucce("操作成功！","person.php");
	}
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>密码修改</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/select.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="js/checkform.js"></script>
</head>

<body>

	<div class="place">
    <span>位置：</span>
    <ul class="placeul">
    <li><a href="mains.php">首页</a></li>
    <li>密码修改</li>
	</ul>
    </div>
    
    <div class="formbody">
    
    
    <div id="usual1" class="usual"> 
    
    <div class="itab">
  	<ul> 
    <li><a href="#tab1" class="selected">密码修改</a></li> 
  	</ul>
    </div> 
    
  	<div id="tab1" class="tabson">
    
    <div class="formtext">Hi，<b><?=$_SESSION['Admin_UserName']?></b>，欢迎您使用信息发布功能！</div>
    
		<ul class="forminfo">
			<form name="frm" method="post" action="" onSubmit="return checkperson(this);">
			<li><label>原密码<b>*</b></label><input name="pwd_old" type="password" class="dfinput" value=""/></li>
			<li><label>新密码<b>*</b></label><input name="pwd_new" type="password" class="dfinput" value=""/></li>
			<li><label>确认密码<b>*</b></label><input name="pwd_new1" type="password" class="dfinput" value=""/></li>
			<li><label>&nbsp;</label><input name="update" type="submit" class="btn" value="马上修改"/></li>
			</form>
		</ul>
    
    </div> 
    
  	  
       
	</div> 
    
    </div>


</body>

</html>
