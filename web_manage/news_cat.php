<?php
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';
$action=$_GET['action'];
$id=(int)$_GET['id'];
$pid=(int)$_GET['pid'];
$ty=(int)$_GET['ty'];
$tty=(int)$_GET['tty'];
$path="../".$upload_picpath."/";

if ($action=="confirm"){
	if (empty($id)){
		die("参数提交错误");
	}
	$sql="UPDATE `{$tablepre}news_cats` SET isstate=NOT(isstate) WHERE id=".$id;
	$db->sql_query($sql);
	JsSucce("操作成功！","news_cat.php");
	exit();
}elseif ($action=="del"){
	if (empty($id)){
		JsError("参数提交错误");
	}
 
	//删除当前分类
	$sql="delete from `{$tablepre}news_cats` WHERE id=".$id;
	$db->sql_query($sql);
 	
	//四级分类
	if($pid&&$ty&&$tty&&$ttty){
		$sql="select img1,img2,img3,file FROM `{$tablepre}news` where ttty={$ttty}";
		$result=$db->sql_query($sql);
		while($bd=$db->sql_fetchrow($result)){
			@unlink($path.$bd['img1']);
			@unlink($path.$bd['img2']);
			@unlink($path.$bd['img3']);
			@unlink($path.$bd['file']);
		}
		//删除新闻
		$sql="delete from `{$tablepre}news` WHERE ttty=".$ttty;
		$db->sql_query($sql);
	}elseif($pid&&$ty&&$tty&&$ttty==0){
		//三级分类
		$sql="select img1,img2,img3,file FROM `{$tablepre}news` where tty={$tty}";
		$result=$db->sql_query($sql);
		while($bd=$db->sql_fetchrow($result)){
			@unlink($path.$bd['img1']);
			@unlink($path.$bd['img2']);
			@unlink($path.$bd['img3']);
			@unlink($path.$bd['file']);
		}
		//删除新闻
		$sql="delete from `{$tablepre}news` WHERE tty=".$tty;
		$db->sql_query($sql);
		
		//删除四级分类
		$sql="delete from `{$tablepre}news_cats` WHERE pid=".$tty;
		$db->sql_query($sql);
	}elseif($pid&&$ty&&$tty==0&&$ttty==0){
		//二级分类
		$sql="select img1,img2,img3,file FROM `{$tablepre}news` where ty={$ty}";
		$result=$db->sql_query($sql);
		while($bd=$db->sql_fetchrow($result)){
			@unlink($path.$bd['img1']);
			@unlink($path.$bd['img2']);
			@unlink($path.$bd['img3']);
			@unlink($path.$bd['file']);
		}
		//删除新闻
		$sql="delete from `{$tablepre}news` WHERE ty=".$ty;
		$db->sql_query($sql);
		
		//删除三级分类
		$sql="delete from `{$tablepre}news_cats` WHERE pid=".$ty;
		$db->sql_query($sql);

	}elseif($pid&&$ty==0&&$tty==0&&$ttty==0){
		//一级分类
		$sql="select img1,img2,img3,file FROM `{$tablepre}news` where pid={$pid}";
		$result=$db->sql_query($sql);
		while($bd=$db->sql_fetchrow($result)){
			@unlink($path.$bd['img1']);
			@unlink($path.$bd['img2']);
			@unlink($path.$bd['img3']);
			@unlink($path.$bd['file']);
		}
		
		//删除新闻
		$sql="delete from `{$tablepre}news` WHERE pid=".$pid;
		$db->sql_query($sql);
		
		
		//删除二级分类
		$sql="delete from `{$tablepre}news_cats` WHERE pid=".$pid;
		$db->sql_query($sql);
 	}
 
 
	JsSucce("操作成功！","class_cat.php");
	exit();
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
    <li>栏目管理</li>
    </ul>
    </div>
    
    <div class="rightinfo">
    
    <div class="tools">
    
    	<ul class="toolbar2">
       		<li><a href="#" class="current">管理栏目</a></li>
        </ul>
      
    
    </div>
    
    
    
    <div>
    	<table width="100%" cellpadding="0" cellspacing="0" class="cast_list">
        	<tr>
            	<th width="11%">栏目ID</th>
                <th width="31%">栏目名称</th>
                <th width="14%">栏目类型</th>
                <th width="12%">访问</th>
                <th width="32%">管理操作</th>
            </tr>
<?
$sql1="SELECT * FROM `{$tablepre}news_cats` WHERE isstate=1 AND pid=0 and catname<>'辅助栏目' ORDER BY disorder ASC, id ASC";
//echo $sql;
$result1=$db->sql_query($sql1);
while($bd1=$db->sql_fetchrow($result1)){
	$pid=$bd1['id'];
?>

            <tr>
            	<td width="11%"><?=$pid?></td>
                <td width="31%" align="left" class="tl">【<?=$bd1['catname']?>】</td>
                <td width="14%"><?=$webarr["showtype"][$bd1['showtype']]?></td>
                <td width="12%"> </td>
                <td width="32%"><? if($_SESSION['is_hidden']==true||$bd1['iscats']==1){?><a href="news_cat_add.php?pid=<?=$pid?>" style="color:red">添加子栏目</a>&nbsp;|&nbsp;<a href="news_cat_edit.php?id=<?=$bd1['id']?>">修改</a><? }?> <? if($_SESSION['is_hidden']==true){?>&nbsp;|&nbsp;<a href="?action=del&pid=<?=$pid?>&id=<?=$bd1['id']?>" class="click" onClick="return ConfirmDelBig();">删除</a><? }?></td>
            </tr>
	<?
	$sql2="SELECT * FROM `{$tablepre}news_cats` WHERE pid=".$pid." ORDER BY disorder ASC,id ASC";
	$result2=$db->sql_query($sql2);
    while($bd2=$db->sql_fetchrow($result2)){
		if($bd2['linkurl']){
			$showtype="自定义";
		}else{
			$showtype=$webarr["showtype"][$bd2['showtype']];
		}
		$ty=$bd2['id'];
	?>
            <tr>
            	<td width="11%"><?=$bd2['id']?></td>
                <td width="31%" align="left" class="tl">&nbsp;├<?=$bd2['catname']?></td>
                <td width="14%"><?=$showtype?></td>
                <td width="12%"><?
		  	if($bd2['isstate']=="1"){
				 echo("<a href='?action=confirm&id=".$bd2['id']."'><font color='#FF00FF'>已审核</font></a>");
			}else{
				 echo("<a href='?action=confirm&id=".$bd2['id']."'><font color='#FF0000'>未审核</font></a>");
			 }
			?></td>
                <td width="32%"><? if($_SESSION['is_hidden']==true||$bd2['iscats']==1){?><a href="news_cat_add.php?pid=<?=$pid?>&ty=<?=$ty?>" style="color:blue">添加子栏目</a>&nbsp;|&nbsp;<? }?><a href="news_cat_edit.php?id=<?=$bd2['id']?>">修改</a>&nbsp;|&nbsp;<a href="?action=del&pid=<?=$pid?>&ty=<?=$ty?>&id=<?=$bd2['id']?>" onClick="return ConfirmDelSmall();">删除</a></td>
            </tr>
			
	<?
	$sql3="SELECT * FROM `{$tablepre}news_cats` WHERE pid=".$ty." ORDER BY disorder ASC,id ASC";
	$result3=$db->sql_query($sql3);
    while($bd3=$db->sql_fetchrow($result3)){
		if($bd3['linkurl']){
			$showtype="自定义";
		}else{
			$showtype=$webarr["showtype"][$bd3['showtype']];
		}
		$tty=$bd3['id'];
	?>
            <tr>
            	<td width="11%"><?=$bd3['id']?></td>
                <td width="31%" align="left" class="tl">&nbsp;&nbsp;└└<?=$bd3['catname']?></td>
                <td width="14%"><?=$showtype?></td>
                <td width="12%"><?
		  	if($bd3['isstate']=="1"){
				 echo("<a href='?action=confirm&id=".$bd3['id']."'><font color='#FF00FF'>已审核</font></a>");
			}else{
				 echo("<a href='?action=confirm&id=".$bd3['id']."'><font color='#FF0000'>未审核</font></a>");
			 }
			?></td>
                <td width="32%"><? if($bd2['iscats']==1){?><a href="news_cat_add.php?pid=<?=$pid?>&ty=<?=$ty?>&tty=<?=$tty?>" style="color:green">添加子栏目</a>&nbsp;|&nbsp;<? }?><a href="news_cat_edit.php?id=<?=$bd3['id']?>">修改</a>&nbsp;|&nbsp;<a href="?action=del&pid=<?=$pid?>&ty=<?=$ty?>&tty=<?=$tty?>&id=<?=$bd3['id']?>" onClick="return ConfirmDelSmall1();">删除</a></td>
            </tr>
	<?
	$sql4="SELECT * FROM `{$tablepre}news_cats` WHERE pid=".$tty." ORDER BY disorder ASC,id ASC";
	$result4=$db->sql_query($sql4);
    while($bd4=$db->sql_fetchrow($result4)){
		if($bd4['linkurl']){
			$showtype="自定义";
		}else{
			$showtype=$webarr["showtype"][$bd4['showtype']];
		}
		$ttty=$bd4['id'];
	?>
		    <tr>
            	<td width="5%"><?=$bd4['id']?></td>
                <td width="10%" align="left" class="tl">&nbsp;&nbsp;&nbsp;&nbsp;└└└<?=$bd4['catname']?></td>
                <td width="10%"><?=$showtype?></td>
			  <td width="10%"><?
		  	if($bd4['isstate']=="1"){
				 echo("<a href='?action=confirm&id=".$bd4['id']."'><font color='#FF00FF'>已审核</font></a>");
			}else{
				 echo("<a href='?action=confirm&id=".$bd4['id']."'><font color='#FF0000'>未审核</font></a>");
			 }
			?></td>
			    <td width="35%"><a href="class_cat_edit.php?id=<?=$bd4['id']?>">修改</a>&nbsp;|&nbsp;<a href="?action=del&pid=<?=$pid?>&ty=<?=$ty?>&tty=<?=$tty?>&ttty=<?=$ttty?>&id=<?=$bd4['id']?>" onClick="return ConfirmDelSmall1();">删除</a></td>
            </tr>
<? }}}}?>            
        </table>
    </div>
    
    <div class="clear"></div>
   
    
    </div>
    

</body>

</html>

