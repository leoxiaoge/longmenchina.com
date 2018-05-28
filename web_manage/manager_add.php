<?php
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';
$typeid=opturl("typeid");

if($typeid==2) {
	$BigMymenu="super";
	$smallmymenu="super";
}else {
	$smallmymenu=implode(",",$_POST['smallmymenu']);
}

if (isset($_POST['dosubmit'])) {
	$sqlvalues="";
	$fields=$_POST['fields'];
	$fields['username']=trim($_POST['username']);
	$fields['password']=md5($_POST['password']);
	$fields['realname']=trim($_POST['realname']);
	$fields['smallmymenu']=$smallmymenu;
	$fields['sendtime']=$PHP_TIME;
	$fields['isstate']=1;
	
	//检查用户名的唯一性
	$result = $db->sql_query("SELECT id FROM `{$tablepre}manager` WHERE username='".$fields['username']."'");
	if($bd=$db->sql_fetchrow($result)){
		JsError("对不起该用户名 ".$fields['username']." 已有人使用，请重新输入！");
		exit();
	}
	
 	if ($typeid==1){
		$k=0;
		$sql="SELECT pid FROM `{$tablepre}news_cats` WHERE isstate=1 AND id in (".$smallmymenu.") group by pid";
		$result=$db->sql_query($sql);
		while($bd=$db->sql_fetchrow($result)){
			$k=$k+1;
			if($k==1) $BigMymenu=$bd['pid']; else $BigMymenu.=",".$bd['pid'];
		}
	}	
	$fields['BigMymenu']=$BigMymenu;
	
	while(list ($key,$val) =each ($fields)){
		$sqlvalues.=",$key='$val'";
	}
	$sqlvalues=substr($sqlvalues,1);
	$sql="INSERT INTO `{$tablepre}manager` SET ".$sqlvalues;
	
	//exit($sql);
	if($db->sql_query($sql)){
		AddLog("添加管理员帐号",$_SESSION['Admin_username']);
		
		JsSucce("操作成功！","manager.php");
	}else{
		JsError("操作失败！");
	}
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理员</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/select.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="js/checkform.js"></script>
</head>

<body>

	<div class="place">
    <span>位置：</span>
    <ul class="placeul">
    <li><a href="mains.php">首页</a></li>
    <li>管理员列表</li>
    <li>信息添加</li>
	</ul>
    </div>
    
    <div class="formbody">
    
    
    <div id="usual1" class="usual"> 
    
    <div class="itab">
  	<ul> 
    <li><a href="#tab1" class="selected">管理员</a></li> 
  	</ul>
    </div> 
    
  	<div id="tab1" class="tabson">
    
    <div class="formtext">Hi，<b><?=$_SESSION['Admin_UserName']?></b>，欢迎您使用信息发布功能！</div>
    
		<ul class="forminfo">
			<form name="formlist" method="post" action="" onSubmit="return checkmanager_add(this);">
			<li><label>管理姓名<b>*</b></label><input name="realname" type="text" class="dfinput" value=""/></li>
  			<li><label>管理帐号<b>*</b></label><input name="username" type="text" class="dfinput" value=""/></li>
  			<li><label>登陆密码<b>*</b></label><input name="password" type="password" class="dfinput" value=""/></li>
  			<li><label>确认密码<b>*</b></label><input name="password1" type="password" class="dfinput" value=""/></li>
  			<li><label>管理员类型<b>*</b></label><input name="typeid" type="radio" onClick="show(0)" value="2" checked> 超级管理员
  <input type="radio" name="typeid" value="1" onClick="show(2)" > 普通管理员</li>
 			
			<li id="dlqy" style="display:none"><label>管理员权限<b>*</b></label>
			
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bordercolorlight="#A1BC7A" bordercolordark="#FFFFFF">
<? 
	$sql="SELECT id,catname FROM `{$tablepre}news_cats` WHERE isstate=1 AND pid=0  ORDER BY disorder ASC,id ASC";
    $result=$db->sql_query($sql);
    while($row=$db->sql_fetchrow($result)){
	$pid=$row['id'];
?>
   <tr bgcolor="#FFFFFF"> 
   <td width=2% height="25" align="center"></td>
    <td width=98% height="25">
	<b><?=$row['id']?>:<?=$row['catname']?></b><br>
	<?
	$sql2="SELECT id,catname FROM `{$tablepre}news_cats` WHERE isstate=1 AND pid=".$pid."  ORDER BY disorder ASC,id ASC";
    $result2=$db->sql_query($sql2);
	$i=0;
	while($row2=$db->sql_fetchrow($result2)){
		 $i++;
		 if($i%5==0)$bj="<br>"; else $bj="";
	?>
   <?=$bj?><input name="smallmymenu[]" type="checkbox" id="smallmymenu[]" value="<?=$row2['id']?>">
   <?=$row2['catname']?>&nbsp;&nbsp;
	<? }?><br>
	<br></td>
    </tr>
<? }?>
  </table>			
			</li>
			
			
			<li><label>&nbsp;</label><input name="dosubmit" type="submit" class="btn" value="马上发布"/></li>
			</form>
		</ul>
    
    </div> 
    
  	  
       
	</div> 
    
    </div>


</body>

</html>
