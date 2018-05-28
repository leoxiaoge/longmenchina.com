<?php
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';

$checkid=opturl("checkid");
$action=opturl("action");
$id=$_GET['id'];
$key=trim(opturl("key"));
$url=urlencode($_SERVER['QUERY_STRING']);
$indexurl=opturl("indexurl");

if ($action=="confirm"){
	if (empty($id)){
		JsError("参数提交错误");
	}
	$sql="update `{$tablepre}users` SET isstate=NOT(isstate) WHERE id=".$id;
	$db->sql_query($sql);
	AddLog("审核会员信息",$_SESSION['Admin_UserName']);
	JsSucce("操作成功！","users.php?".$indexurl);
	exit();
}elseif ($action=="del"){
	$del_num=count($checkid);
	for($i=0;$i<$del_num;$i++){
		$db->sql_query("delete FROM `{$tablepre}users` where id=".$checkid[$i]."");
		JsSucce("操作成功！","users.php?".$indexurl);
	}
	AddLog("删除会员信息",$_SESSION['Admin_UserName']);
	exit();
}
if ($key) $sqlkey.=" AND (username like '%".$key."%' or email like '%".$key."%')";
$pagesize=15;
$page=(int)$_GET['page']==0?1:(int)$_GET['page'];
$sql="select * from `{$tablepre}users` where 1=1 ".$sqlkey." order by id desc";
$pagestr=page_list($sql,$page,$pagesize);
$sql.=" limit ".(($page-1)*$pagesize).",$pagesize";	
$result=$db->sql_query($sql);
$PageCount=$db->sql_numrows($result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>会员管理页面</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="js/checkform.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
</head>
 
<body>

<div class="place">
	<span>位置：</span>
	<ul class="placeul">
		<li><a href="mains.php">首页</a></li>
		<li>会员管理</li>
		<li>信息管理</li>
	</ul>
</div>
	
<table class="tablelist">
	<form action="" method="post" name="formlist1">
		<tbody>
			<tr>
				<td>
					<b>用户名、邮箱：</b><input name="key" type="text" class="dfinput" id="key" value="<?=$key?>"/>
					<input name="search" type="submit" class="btn" value="搜索"/>
				</td>
			</tr>
		</tbody>
	</form>
</table>	

<div class="rightinfo">
	<form name="formlist" method="post" action="?action=del">
		<div class="tools">
			<ul class="toolbar">
				<li><a href="users.php?<?=$url?>"><img src="images/sx.jpg" /></a></li>
				<li>&nbsp;<input type="checkbox" name="all" onClick="CheckAll(this);"> 全选</li>
				<li><a href="users_add.php"><img src="images/add.jpg" /></a></li>
				<li><input type="image" src="images/del.jpg" onClick="return checkData();" name="ok" value="删 除"></li>
			</ul>
		</div>
		<table class="tablelist">
			<thead>
				<tr>
					<th width="50">选择</th>
					<th width="50">编号</th>
					<th width="100">用户名</th>
					<th width="100">密码</th>
					<th width="140">注册时间</th>
					<th width="50">状态</th>
					<th width="50">操作</th>
				</tr>
			</thead>
			<tbody>
			<?
			while($bd=$db->sql_fetchrow($result)){	
			if($bd['isstate']==1)
				$zt="<a href='?action=confirm&id=".$bd['id']."'><font color='red'>已验证</font></a>";
			else
				$zt="<a href='?action=confirm&id=".$bd['id']."'><font color=''>未验证</font></a>";
				
			if($bd['login']==1)
				$login="人才";
			elseif($bd['login']==2)
				$login="企业";
			elseif($bd['login']==3||$bd['login']==0)
				$login="政府";
			?>
			<tr>
				<td><input name="checkid[]" type="checkbox" id="checkid[]" value="<?=$bd['id']?>"></td>
				<td><?=$bd['id']?></td>
				<td><?=$bd['username']?><font color="red">(<?=$login?> )</font></td>
				<td><?=$bd['userpwd']?></td>
				<td><?=date('Y-m-d H:i',$bd['sendtime'])?></td>
				<td><?=$zt?></td>
				<td><a href="users_edit.php?id=<?=$bd['id']?>&login=<?=$bd['login']?>&indexurl=<?=$url?>" class="tablelink">修改</a></td>
			</tr> 
			<? } ?>	
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
