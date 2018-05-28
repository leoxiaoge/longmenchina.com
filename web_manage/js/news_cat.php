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
	
	//一级分类
	if($pid){
		//一级分类相关
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
	
	
	//二级分类
	if($ty){
		//二级分类相关
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

	}

	
	//三级分类
	if($tty){
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
	}

	JsSucce("操作成功！","news_cat.php");
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>s栏目管理</title>
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
            	<th width="5%">栏目ID</th>
                <th width="10%">栏目名称</th>
                <th width="10%">栏目类型</th>
                <th width="10%">数据量</th>
                <th width="10%">访问</th>
                <th width="35%">管理操作</th>
            </tr>
<?
$sql1="SELECT * FROM `{$tablepre}news_cats` WHERE pid=0  ORDER BY disorder ASC, id ASC";
//echo $sql;
$result1=$db->sql_query($sql1);
while($bd1=$db->sql_fetchrow($result1)){
	$pid=$bd1['id'];
?>

            <tr>
            	<td width="5%"><?=$pid?></td>
                <td width="10%" align="left"><?=$bd1['catname']?></td>
                <td width="10%"><?=$webarr["showtype"][$bd1['showtype']]?></td>
                <td width="10%"><?=get_count($pid,0,0)?></td>
                <td width="10%"><a href="../list.php?pid=<?=$pid?>" target="_blank">访问</a></td>
                <td width="35%"><? if($_SESSION['is_hidden']==true||$bd1['iscats']==1){?><a href="news_cat_add.php?pid=<?=$pid?>" style="color:red">添加子栏目</a>&nbsp;|&nbsp;<? }?><? if($_SESSION['is_hidden']==true){?><a href="news_cat_edit.php?id=<?=$bd1['id']?>">修改</a><? }?><? if($_SESSION['is_hidden']==true||$bd1['ishidden']==0){?>&nbsp;|&nbsp;<a href="?action=del&pid=<?=$pid?>&id=<?=$bd1['id']?>" class="click" onClick="return ConfirmDelBig();">删除</a><? }?></td>
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
            	<td width="5%"><?=$bd2['id']?></td>
                <td width="10%" align="left" class="tl"><span><img src="images/li2.jpg"></span><?=$bd2['catname']?></td>
                <td width="10%"><?=$showtype?></td>
                <td width="10%"><?=get_count($pid,$ty,0)?></td>
                <td width="10%"><a href="../list.php?pid=<?=$pid?>&ty=<?=$ty?>" target="_blank">访问</a></td>
                <td width="35%"><? if($_SESSION['is_hidden']==true||$bd2['iscats']==1){?><a href="news_cat_add.php?pid=<?=$pid?>&ty=<?=$ty?>" style="color:#0000FF">添加子栏目</a>&nbsp;|&nbsp;<? }?><a href="news_cat_edit.php?id=<?=$bd2['id']?>">修改</a>&nbsp;|&nbsp;<a href="?action=del&pid=<?=$pid?>&ty=<?=$ty?>&id=<?=$bd1['id']?>" onClick="return ConfirmDelSmall();">删除</a></td>
            </tr>
<? }}?>            
        </table>
    </div>
    
    <div class="clear"></div>
   
    <div class="tip">
    	<div class="tiptop"><span>提示信息</span><a></a></div>
        
      <div class="tipinfo">
        <span><img src="images/ticon.png" /></span>
        <div class="tipright">
        <p>是否确认对信息的修改 ？</p>
        <cite>如果是请点击确定按钮 ，否则请点取消。</cite>
        </div>
        </div>
        
        <div class="tipbtn">
        <input name="" type="button"  class="sure" value="确定" />&nbsp;
        <input name="" type="button"  class="cancel" value="取消" />
        </div>
    
    </div>
    
    
    
    
    </div>
    

</body>

</html>

