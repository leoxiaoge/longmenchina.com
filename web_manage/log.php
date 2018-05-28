<?php
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';
$gourl=basename($PHP_SELF);

$checkid=opturl("checkid");
$action=opturl("action");
 
if ($action=="del"){
	$del_num=count($checkid);
	for($i=0;$i<$del_num;$i++){
		$db->delete("logs"," id=".$checkid[$i]."");
	}
	JsSucce("操作成功！","{$gourl}".$indexurl);
	exit();
}elseif ($action=="alldel"){
	$db->sql_query("truncate `{$tablepre}logs`");
	JsSucce("操作成功！","{$gourl}".$indexurl);
	exit();
}

$pagesize=15;
$page=(int)$_GET['page']==0?1:(int)$_GET['page'];
$sql="select * from `{$tablepre}logs` order  by id desc";
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
<title>操作日志</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="js/checkform.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
</head>


<body>

	<div class="place">
    <span>位置：</span>
    <ul class="placeul">
    <li><a href="mains.php">首页</a></li>
    <li>操作日志</li>
    </ul>
    </div>
    
    <div class="rightinfo">
    <form name="formlist" method="post" action="?action=del">

    <div class="tools">
    
    	<ul class="toolbar">
			<li>&nbsp;<a href="?action=alldel" onClick="return confirm('确定清空所有操作日志吗？\n\n清空后不能恢复！');" class="Botton"> 清空日志</a></li>
			<a href="?<?=$url?>"><img src="images/sx.jpg" /></a>
			<li>&nbsp;<input type="checkbox" name="all" onClick="CheckAll(this);"> 全选</li>
			<input type="image" src="images/del.jpg" onClick="return checkData();" name="ok" value="删 除">
        </ul>
    
    </div>
    
    
    <table class="tablelist">
    	<thead>
			<tr>
			<th width="6%">选择</th>
			<th width="7%">编号</th>
			<th width="15%">用户名</th>
			<th width="47%">操作内容</th>
			<th width="12%">IP</th>
			<th width="13%">操作时间</th>
			</tr>
        </thead>
        <tbody>
   <?
	while($bd=$db->sql_fetchrow($result)){	
	?>
		<tr>
        <td><input name="checkid[]" type="checkbox" id="checkid[]" value="<?=$bd['id']?>"></td>
        <td><?=$bd['id']?></td>
        <td><?=$bd['username']?></td>
        <td><?=$bd['content']?></td>
        <td><?=$bd['ip']?></td>
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
