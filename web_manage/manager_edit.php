<?
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';
$id= (int)$_GET['id'];

$sql="select * FROM `{$tablepre}manager` where id=".$id."";
$result=$db->sql_query($sql);
if($row=$db->sql_fetchrow($result)){
	$pid=$row['pid'];
	$ty=$row['ty'];
	$tty=$row['tty'];
	$smallmymenu=$row['smallmymenu'];
}

if(isset($_POST['update'])){
	$oldpwd=trim($_POST['oldpwd']);
	$newpwd=md5($_POST['password']);

	$sqlvalues="";
	$fields=$_POST['fields'];
	$typeid=(int)$_POST['typeid'];

	if($typeid==2) {
		$bigmymenu="super";
		$smallmymenu="super";
	}else {
		$smallmymenu=implode(",",$_POST['smallmymenu']);
	}
	
	$fields['smallmymenu']=$smallmymenu;
	
	if ($typeid==1){
		$k=0;
		$sql="SELECT pid FROM `{$tablepre}news_cats` WHERE isstate=1 AND id in (".$smallmymenu.") group by pid";
 		$result=$db->sql_query($sql);
		while($bd=$db->sql_fetchrow($result)){
			$k=$k+1;
			if($k==1) $bigmymenu=$bd['pid']; else $bigmymenu.=",".$bd['pid'];
		}
	}	
	$fields['bigmymenu']=$bigmymenu;
	
	$fields['realname']=trim($_POST['realname']);
	$fields['username']=trim($_POST['username']);
	$fields['sendtime ']=$PHP_TIME;
	if($oldpwd<>$newpwd and $newpwd<>"d41d8cd98f00b204e9800998ecf8427e") $fields['password']=$newpwd;
	
	while(list ($key,$val) =each ($fields)){
		$sqlvalues.=",$key='$val'";
	}
	$sqlvalues=substr($sqlvalues,1);

	$sql="update `{$tablepre}manager` SET ".$sqlvalues." where id=".$id."";
	
	//exit($sql);
	if($db->sql_query($sql)){
		AddLog("编辑管理员帐号",$_SESSION['Admin_username']);
		
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
<title>管理员编辑</title>
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
    <li>信息修改</li>
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
			<form name="formlist" method="post" action="" onSubmit="return checkmanager_edit(this);">
			<li><label>管理姓名<b>*</b></label><input name="realname" type="text" class="dfinput" value="<?=$row['realname']?>"/></li>
  			<li><label>管理帐号<b>*</b></label><input name="username" type="text" class="dfinput" value="<?=$row['username']?>"/></li>
  			<li><label>登陆密码<b>*</b></label><input name="password" type="password" class="dfinput" value=""/> (不修改则为空)</li>
  			<li><label>确认密码<b>*</b></label><input name="password1" type="password" class="dfinput" value=""/></li>
  			<li><label>管理员类型<b>*</b></label>	   <input name="typeid" type="radio" onClick="show(0)" value="2" <? if($row['bigmymenu']=="super") echo 'checked'?>> 超级管理员
       <input type="radio" name="typeid" value="1" onClick="show(2)" <? if($row['bigmymenu']<>"super") echo 'checked'?>> 普通管理员</td>
</li>
 			
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
	 
		  $AdvQx=explode(',',$smallmymenu);
		  $ck="";
		  foreach($AdvQx as $mn)
		  if(trim($mn)==$row2['id']) $ck="checked";
		 
		 $i=$i+1;
		 if($i%5==0)$bj="<br>"; else $bj="";
	?>
   <?=$bj?><input name="smallmymenu[]" type="checkbox" id="smallmymenu[]" value="<?=$row2['id']?>"  <?=$ck?>>
   <?=$row2['catname']?>&nbsp;&nbsp;
	<? }?><br>
	<br></td>
    </tr>
<? }?>
  </table>			
			</li>
			
			
			<li><label>&nbsp;</label><input name="update" type="submit" class="btn" value="马上修改"/></li>
			</form>
		</ul>
    
    </div> 
    
  	  
       
	</div> 
    
    </div>


</body>

</html>
