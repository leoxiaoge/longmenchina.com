<?php
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';

$checkid=opturl("checkid");
$action=opturl("action");
$id=$_GET['id'];
$key=trim(opturl("key"));
$url=urlencode($_SERVER['QUERY_STRING']);
$indexurl=opturl("indexurl");
$gourl="payment.php";
$classname="<li>配送方式</li>";

if ($action=="confirm"){
	if (empty($id)){
		JsError("参数提交错误");
	}
	$sql="update `{$tablepre}payment` SET isstate=NOT(isstate) WHERE id=".$id;
	$db->sql_query($sql);
	AddLog("审核邮费内容",$_SESSION['Admin_UserName']);
	JsSucce("操作成功！","{$gourl}");
	exit();
}elseif ($action=="del"){
	$del_num=count($checkid);

	for($i=0;$i<$del_num;$i++){
		$db->sql_query("delete FROM `{$tablepre}payment` where id=".$checkid[$i]."");
		JsSucce("操作成功！","{$gourl}".$indexurl);
 	}
	AddLog("删除邮费内容",$_SESSION['Admin_UserName']);
	exit();
}

if ($key) $sqlkey.=" AND binary title like '%".$key."%'";

$pagesize=15;
$page=(int)$_GET['page']==0?1:(int)$_GET['page'];
$sql="select * FROM `{$tablepre}payment` where 1=1 {$sqlkey} order by disorder desc,id desc";
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
<title>配送方式页面</title>
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
    <form name="formlist" method="post" action="payment.php?action=del">

    <div class="tools">
    
    	<ul class="toolbar">
			<a href="payment.php?<?=$url?>"><img src="images/sx.jpg" /></a>
			<li>&nbsp;<input type="checkbox" name="all" onClick="CheckAll(this);"> 全选</li>
			<a href="payment_add.php"><img src="images/add.jpg" /></a>
			<input type="image" src="images/del.jpg" onClick="return checkData();" name="ok" value="删 除">
        </ul>
    
    </div>
    
    
    <table class="tablelist">
    	<thead>
			<tr>
			<th width="83">选择</th>
			<th width="63">编号</th>
			<th width="235">名称</th>
			<th width="310">说明</th>
			<th width="310">邮费</th>
			<th width="124">是否审核</th>
			<th width="190">发布时间</th>
			<th width="56">操作</th>
			</tr>
        </thead>
        <tbody>
   <?
	while($bd=$db->sql_fetchrow($result)){	
 		if($bd['isstate']==1)
			$zt="<a href='?action=confirm&id=".$bd['id']."'><font color='red'>已审核</font></a>";
		else
			 $zt="<a href='?action=confirm&id=".$bd['id']."'><font color=''>未审核</font></a>";
	?>
		<tr>
        <td><input name="checkid[]" type="checkbox" id="checkid[]" value="<?=$bd['id']?>"></td>
        <td><?=$bd['id']?></td>
        <td><?=$bd['title']?></td>
        <td><?=$bd['ftitle']?></td>
        <td><?=$bd['price']?> 元</td>
        <td><?=$zt?></td>
        <td><?=date('Y-m-d H:i:s',$bd['sendtime'])?></td>
		<td><a href="payment_edit.php?id=<?=$bd['id']?>&indexurl=<?=$url?>" class="tablelink">修改</a></td>
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
