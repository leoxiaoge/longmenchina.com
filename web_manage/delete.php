<?php
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';

$checkid=opturl("checkid");
$action=opturl("action");
$id=$_GET['id'];
$sort=$_GET['sort'];
$key=trim(opturl("key"));
$url=urlencode($_SERVER['QUERY_STRING']);
$indexurl=opturl("indexurl");

$path="../".$upload_picpath."/";

if ($action=="confirm"){
	if (empty($id)){
		JsError("参数提交错误");
	}
	$sql="update `{$tablepre}news` SET isstate=NOT(isstate) WHERE id=".$id;
	$db->sql_query($sql);
	AddLog("审核新闻内容",$_SESSION['Admin_UserName']);
	JsSucce("操作成功！","delete.php");
	exit();
}elseif ($action=="del"){
	$del_num=count($checkid);
	if($_POST['cats']) $pp=explode('|',$_POST['cats']);
	$npid=$pp[0];
	$nty=$pp[1];

	for($i=0;$i<$del_num;$i++){
		$sql="select img1,img2,img3,file FROM `{$tablepre}news` where id=".$checkid[$i]."";
		$result=$db->sql_query($sql);
		if($bd=$db->sql_fetchrow($result)){
			@unlink($path.$bd['img1']);
			@unlink($path.$bd['img2']);
			@unlink($path.$bd['img3']);
			@unlink($path.$bd['file']);
			$db->sql_query("delete FROM `{$tablepre}news` where id=".$checkid[$i]."");
		}
		JsSucce("操作成功！","delete.php".$indexurl);
 	}
	AddLog("删除新闻内容",$_SESSION['Admin_UserName']);
	exit();
}

if ($key) $sqlkey.=" AND binary title like '%".$key."%'";

$pagesize=15;
$page=(int)$_GET['page']==0?1:(int)$_GET['page'];
$sql="select * FROM `{$tablepre}news` where 1=1 {$sqlkey} order by sendtime desc,id desc";
$pagestr=page_list($sql,$page,$pagesize);
$sql.=" limit ".(($page-1)*$pagesize).",$pagesize";	
$result=$db->sql_query($sql);
//echo $sql;
$PageCount=$db->sql_numrows($result);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>信息删除管理页面</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="js/checkform.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
</head>


<body>

	<div class="place">
    <span>位置：</span>
    <ul class="placeul">
    <li><a href="mains.php">首页</a></li>
    <li>信息删除</li>
    </ul>
    </div>
	<table class="tablelist">
	 <form action="" method="get" name="formlists">
		<input type="hidden" name="pid" value="<?=$pid?>">
		<input type="hidden" name="ty" value="<?=$ty?>">
		<input type="hidden" name="tty" value="<?=$tty?>">
		<input type="hidden" name="ttty" value="<?=$ttty?>">
    	<thead>
         <tbody>
 		<tr>
          <td>
 		  <b>标题关键字：</b><input name="key" type="text" class="dfinput" value="<?=$key?>"/>
          <input name="search" type="submit" class="btn" value="搜索"/></td>
 		</tr> 
       </tbody>
	   </form>
    </table>	
    <div class="rightinfo">
    <form name="formlist" method="post" action="?action=del">

    <div class="tools">
    
    	<ul class="toolbar">
			<a href="?<?=$url?>"><img src="images/sx.jpg" /></a>
			<li>&nbsp;<input type="checkbox" name="all" onClick="CheckAll(this);"> 全选</li>
			<input type="image" src="images/del.jpg" onClick="return checkData();" name="ok" value="删 除">
        </ul>
    
    </div>
    
    
    <table class="tablelist">
    	<thead>
			<tr>
			<th width="6%">选择</th>
			<th width="9%">编号</th>
			<th width="10%">一级栏目</th>
			<th width="9%">二级栏目</th>
			<th width="14%">三级栏目</th>
			<th width="24%">标题</th>
			<th width="8%">是否审核</th>
			<th width="6%">预览</th>
			<th width="14%">发布时间</th>
			</tr>
        </thead>
        <tbody>
   <?
	while($bd=$db->sql_fetchrow($result)){	
		if(!empty($bd['img1'])) $Img=$bd['img1']; else $Img="nopic.jpg";
		if($bd['isstate']==1)
			$zt="<a href='?action=confirm&id=".$bd['id']."'><font color='red'>已审核</font></a>";
		else
			 $zt="<a href='?action=confirm&id=".$bd['id']."'><font color=''>未审核</font></a>";
	?>
		<tr>
        <td><input name="checkid[]" type="checkbox" id="checkid[]" value="<?=$bd['id']?>"></td>
        <td><?=$bd['id']?></td>
        <td><?=get_catname($bd['pid'],"news_cats")?></td>
        <td><?=get_catname($bd['ty'],"news_cats")?></td>
        <td><?=get_catname($bd['tty'],"news_cats")?></td>
        <td><?=$bd['title']?></td>
        <td><?=$zt?></td>
        <td><a href="../view.php?action=a&id=<?=$bd['id']?>" target="_blank">预览</a></td>
        <td><?=date('Y-m-d H:i:s',$bd['sendtime'])?></td>
		</tr> 
	<? }?>	
        </tbody>
    </table>
   
    <?=$pagestr;?>
    
    </form>
    
    
    </div>
    
    <script type="text/javascript">
	$('.tablelist tbody tr:odd').addClass('odd');
	</script>

</body>

</html>
