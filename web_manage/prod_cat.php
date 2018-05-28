<?php
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';
$action=$_GET['action'];
$id=(int)$_GET['id'];
$pid=(int)$_GET['pid'];
$ty=(int)$_GET['ty'];
$tty=(int)$_GET['tty'];
$mtypeid=(int)$_GET['mtypeid'];
$path="../".$upload_picpath."/";

if ($action=="confirm"){
	if (empty($id)){
		die("参数提交错误");
	}
	$sql="UPDATE `{$tablepre}prod_cats` SET isstate=NOT(isstate) WHERE id=".$id;
	$db->sql_query($sql);
	JsSucce("操作成功！","prod_cat.php?mtypeid=$mtypeid");
	exit();
}elseif ($action=="del"){
	if (empty($id)){
		JsError("参数提交错误");
	}

	//删除当前分类
	$sql="delete from `{$tablepre}prod_cats` WHERE id=".$id;
 	$db->sql_query($sql);
	
	//一级分类
	if($pid&&$ty==0){
 		
 		//删除二级分类
		$sql="delete from `{$tablepre}prod_cats` WHERE pid=".$pid;
		$db->sql_query($sql);
 	}
	
	
	//二级分类
	if($pid&&$ty==0){
 		//删除三级分类
		$sql="delete from `{$tablepre}prod_cats` WHERE id=".$ty;
 		$db->sql_query($sql);

	}
 	JsSucce("操作成功！","prod_cat.php?mtypeid=$mtypeid");
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>栏目管理</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="js/checkform.js"></script>
</head>

<body>

<div class="place">
	<span>位置：</span>
	<ul class="placeul">
		<li><a href="mains.php">首页</a></li>
		<li><?=$webarr["mtypeid"][$mtypeid]?></li>
		<li>分类管理</li>
	</ul>
</div>
    
<div class="rightinfo">

	<div class="tools">
	
		<ul class="toolbar2">
			<li><a href="#" class="current">管理分类</a></li>
			<li><a href="prod_cat_add.php?mtypeid=<?=$mtypeid?>">添加地区分类</a></li>
		</ul>
	
	</div>
	
	<div>
		<table width="100%" cellpadding="0" cellspacing="0" class="cast_list">
			<tr>
				<th width="5%">栏目ID</th>
				<th width="20%">栏目名称</th>
				<th width="10%">审核</th>
				<th width="15%">管理操作</th>
			</tr>
			<?
			$sql1="SELECT * FROM `{$tablepre}prod_cats` WHERE pid=0 and typeid={$mtypeid} ORDER BY disorder ASC, id desc";
			//echo $sql1;
			$result1=$db->sql_query($sql1);
			while($bd1=$db->sql_fetchrow($result1)){
			$pid=$bd1['id'];
			if ($bd1['isgood']==1) $bj="&nbsp;<font color=red>推荐</font>"; else $bj='';
			?>
			<tr>
				<td width="5%"><?=$pid?></td>
				<td width="10%" align="left" class="tl">【 <?=get_zd_name("catname","prod_cats"," and id={$bd1['catname2']} ")?> <?=$bd1['catname']?>】 <?=$bj?></td>
				<td width="10%"><?
			if($bd1['isstate']=="1"){
				 echo("<a href='?action=confirm&mtypeid={$mtypeid}&id=".$bd1['id']."'><font color='#FF00FF'>已审核</font></a>");
			}else{
				 echo("<a href='?action=confirm&mtypeid={$mtypeid}&id=".$bd1['id']."'><font color='#FF0000'>未审核</font></a>");
			 }
			?></td>
				<td width="35%"><? if($mtypeid==1){?><a href="prod_cat_add.php?pid=<?=$pid?>&mtypeid=<?=$mtypeid?>" style="color:red">添加商圈管理</a>&nbsp;|&nbsp;<? }?><a href="prod_cat_edit.php?id=<?=$bd1['id']?>">修改</a>&nbsp;|&nbsp;<a href="?action=del&pid=<?=$pid?>&id=<?=$bd1['id']?>&mtypeid=<?=$mtypeid?>" onClick="return ConfirmDelBig();">删除</a></td>
			</tr>
	<?
	$sql2="SELECT * FROM `{$tablepre}prod_cats` WHERE pid=".$pid." and typeid={$mtypeid} ORDER BY disorder ASC,id desc";
	$result2=$db->sql_query($sql2);
	while($bd2=$db->sql_fetchrow($result2)){
		$ty=$bd2['id'];
		if ($bd2['isgood']==1) $bj2="&nbsp;<font color=red>推荐</font>"; else $bj2='';
	?>
			<tr>
				<td width="5%"><?=$bd2['id']?></td>
				<td width="10%" align="left" class="tl">&nbsp;├<?=$bd2['catname']?>  <?=$bj2?></td>
				<td width="10%"><?
			if($bd2['isstate']=="1"){
				 echo("<a href='?action=confirm&mtypeid={$mtypeid}&&id=".$bd2['id']."'><font color='#FF00FF'>已审核</font></a>");
			}else{
				 echo("<a href='?action=confirm&mtypeid={$mtypeid}&&id=".$bd2['id']."'><font color='#FF0000'>未审核</font></a>");
			 }
			?></td>
				<td width="35%"><? if($mtypeid==1111){?><a href="prod_cat_add.php?pid=<?=$pid?>&ty=<?=$ty?>&mtypeid=<?=$mtypeid?>" style="color:blue">添加三级分类</a>&nbsp;|&nbsp;<? }?><a href="prod_cat_edit.php?id=<?=$bd2['id']?>">修改</a>&nbsp;|&nbsp;<a href="?action=del&pid=<?=$pid?>&ty=<?=$ty?>&id=<?=$bd2['id']?>&mtypeid=<?=$mtypeid?>" onClick="return ConfirmDelSmall();">删除</a></td>
			</tr>
	
			<? }}?>            
		</table>
	</div>
	
	<div class="clear"></div>

</div>

</body>

</html>

