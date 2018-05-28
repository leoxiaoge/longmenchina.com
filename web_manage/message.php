<?php
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';
$filenames=basename($PHP_SELF,".php");
 
$checkid=opturl("checkid");
$action=opturl("action");
$id=$_GET['id'];
$pid=(int)opturl("pid");
$ty=(int)opturl("ty");
$tty=(int)opturl("tty");
$ttty=(int)opturl("ttty");
$key=trim(opturl("key"));
 
$path="../".$upload_picpath."/";

if($pid) $sqlkey.=" AND pid={$pid}";
if($ty) $sqlkey.=" AND ty={$ty}";
if($tty) $sqlkey.=" AND tty={$tty}";
if($ttty) $sqlkey.=" AND ttty={$ttty}";
 
$gourl=get_tourl($pid,$ty,$tty,$ttty,"{$filenames}");
$classname=get_toclassname($pid,$ty,$tty,$ttty);
$cname=get_cname($pid,$ty,$tty,$ttty);

if ($action=="confirm"){
	if (empty($id)){
		JsError("参数提交错误");
	}
 	
	$sql="update `{$tablepre}message` SET isstate=NOT(isstate) WHERE id=".$id;
	if($db->sql_query($sql)){
		AddLog("审核{$cname}栏目内容",$_SESSION['Admin_UserName']);
		JsSucce("操作成功！","{$gourl}");
	}else{
		JsError("操作失败！");
	}
	exit();
}elseif ($action=="del"){
	$del_num=count($checkid);

	for($i=0;$i<$del_num;$i++){
		$insertid=$db->delete("message"," id=".$checkid[$i]."");
		JsSucce("操作成功！","{$gourl}".$indexurl);
	}
	AddLog("删除{$cname}栏目内容",$_SESSION['Admin_UserName']);
	exit();
}

if ($key) $sqlkey.=" AND binary title like '%".$key."%'";

$pagesize=15;
$page=(int)$_GET['page']==0?1:(int)$_GET['page'];
$sql="SELECT * FROM `{$tablepre}message` order by id desc";
//echo $sql;
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
<title>调换服务页面</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="js/checkform.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>

</head>


<body>

<div class="place">
	<span>位置：</span>
	<ul class="placeul">
		<li><a href="mains.php">首页</a></li>
		<?=$classname?>
		<li>信息管理</li>
	</ul>
</div>
    
<div class="rightinfo">

	<form name="formlist" method="post" action="<?=$filenames?>.php?action=del&pid=<?=$pid?>&ty=<?=$ty?>&tty=<?=$tty?>&ttty=<?=$ttty?>">
	
		<div class="tools">
		
			<ul class="toolbar">
				<li><a href="<?=$filenames?>.php?pid=<?=$pid?>&ty=<?=$ty?>&tty=<?=$tty?>&ttty=<?=$ttty?>&<?=$url?>"><img src="images/sx.jpg" /></a></li>
				<li><input type="checkbox" name="all" onClick="CheckAll(this);"> 全选</li>
				<li><input type="image" src="images/del.jpg" onClick="return checkData();" name="ok" value="删 除"></li>
			</ul>
		
		</div>
	
	
		<table class="tablelist">
		
			<thead>
				<tr>
				<th width="50">选择</th>
				<th width="50">编号</th>
				<th width="200">信息标题</th>
				<th width="200">期望价格</th>
				<th width="200">电话号码</th>
				<th width="130">操作</th>
				</tr>
			</thead>
			
			<tbody>
				<?
				while($bd=$db->sql_fetchrow($result)){	

				$show="<a href=\"message_show.php?id={$bd['id']}\"><font color='red'>查看</font></a>";

				if($bd['isstate']==1)
					$zt="<a href='?action=confirm&id=".$bd['id']."'><font color='red'>已联系</font></a>";
				else
					$zt="<a href='?action=confirm&id=".$bd['id']."'><font color=''>未联系</font></a>";
					
				?>
				<tr>
					<td><input name="checkid[]" type="checkbox" id="checkid[]" value="<?=$bd['id']?>"></td>
					<td><?=$bd['id']?></td>
					<td><?=$bd['title']?></td>
					<td><?=$bd['prices']?></td>
					<td><?=$bd['tel']?></td>
					<td><?=$show?> <?=$zt?></td>
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
