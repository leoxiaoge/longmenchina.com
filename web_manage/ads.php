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
    <li>广告位管理</li>
    </ul>
    </div>
    
    <div class="rightinfo">
    
    <div class="tools">
    
    	<ul class="toolbar2">
       		<li><a href="#" class="current">广告位管理</a></li>
        </ul>
      
    
    </div>
    
    
    <div>
    	<table width="100%" cellpadding="0" cellspacing="0" class="cast_list">
        	<tr>
            	<th width="5%">栏目ID</th>
                <th width="10%">栏目名称</th>
                <th width="10%">栏目类型</th>
                <th width="10%">数据量</th>
                <th width="35%">管理操作</th>
            </tr>
<?
$sql1="SELECT * FROM `{$tablepre}news_cats` WHERE pid<>0 and showtype=5 ORDER BY disorder ASC, id ASC";
//echo $sql;
$result1=$db->sql_query($sql1);
while($bd1=$db->sql_fetchrow($result1)){
	$pid=$bd1['pid'];
	$ty=$bd1['id'];
?>

            <tr>
            	<td width="5%"><?=$pid?></td>
                <td width="10%" align="left" class="tl">【<?=$bd1['catname']?>】</td>
                <td width="10%"><?=$webarr["showtype"][$bd1['showtype']]?></td>
                <td width="10%"><?=get_count(" and pid={$pid} and ty={$ty} ")?></td>
                <td width="35%"><a href="gg.php?pid=<?=$pid?>&ty=<?=$ty?>" style="color:red">广告管理</a>&nbsp;</td>
            </tr>
	<?
	$sql2="SELECT * FROM `{$tablepre}news_cats` WHERE pid=".$ty." ORDER BY disorder ASC,id ASC";
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
                <td width="10%" align="left" class="tl">&nbsp;├<?=$bd2['catname']?></td>
                <td width="10%"><?=$showtype?></td>
                <td width="10%"><?=get_count(" and pid={$pid} and ty={$ty} ")?></td>
                <td width="35%">&nbsp;</td>
            </tr>
			
			
<? }}?>            
        </table>
    </div>
    
    <div class="clear"></div>
   
     
    </div>
    

</body>

</html>

