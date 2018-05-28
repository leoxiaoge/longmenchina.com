<?php
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';

$checkid=opturl("checkid");
$action=opturl("action");
$id=$_GET['id'];
$key=trim(opturl("key"));

$isstate=trim(opturl("isstate"));
$ispay=trim(opturl("ispay"));
$isship=trim(opturl("isship"));
$isreceipt=trim(opturl("isreceipt"));


$startdate=$_GET['startdate'];
$enddate=$_GET['enddate'];
$url=urlencode($_SERVER['QUERY_STRING']);
$indexurl=opturl("indexurl");

$path="../".$upload_picpath."/";
$gourl="orders.php?siteid={$siteid}";
$classname="<li>我的订单</li>";

if ($action=="isstate"){
	if (empty($id)){
		JsError("参数提交错误");
	}
	$sql="update `{$tablepre}orders` SET isstate=NOT(isstate) WHERE id=".$id;
	if($db->sql_query($sql)){
		AddLog("审核{$cname}栏目内容",$_SESSION['Admin_UserName']);
		JsSucce("状态更新成功！","{$gourl}");
	}else{
		JsError("操作失败！");
	}
	exit();
}
if ($action=="ispay"){
	if (empty($id)){
		JsError("参数提交错误");
	}
	$sql="update `{$tablepre}orders` SET ispay=NOT(ispay) WHERE id=".$id;
	if($db->sql_query($sql)){
		AddLog("审核{$cname}栏目内容",$_SESSION['Admin_UserName']);
		JsSucce("状态更新成功！","{$gourl}");
	}else{
		JsError("操作失败！");
	}
	exit();
}
if ($action=="isship"){
	if (empty($id)){
		JsError("参数提交错误");
	}
	$sql="update `{$tablepre}orders` SET isship=NOT(isship) WHERE id=".$id;
	if($db->sql_query($sql)){
		AddLog("审核{$cname}栏目内容",$_SESSION['Admin_UserName']);
		JsSucce("状态更新成功！","show.php?id=".$id."");
	}else{
		JsError("操作失败！");
	}
	exit();
}
if ($action=="isreceipt"){
	if (empty($id)){
		JsError("参数提交错误");
	}
	$sql="update `{$tablepre}orders` SET isreceipt=NOT(isreceipt) WHERE id=".$id;
	if($db->sql_query($sql)){
		AddLog("审核{$cname}栏目内容",$_SESSION['Admin_UserName']);
		JsSucce("状态更新成功！","{$gourl}");
	}else{
		JsError("操作失败！");
	}
	exit();
}
if ($action=="del"){
	$del_num=count($checkid);

	for($i=0;$i<$del_num;$i++){
		$db->sql_query("delete from `{$tablepre}orders` where id=".$checkid[$i]."");
		JsSucce("操作成功！","{$gourl}".$indexurl);
	}
	AddLog("删除我的订单",$_SESSION['Admin_UserName']);
	exit();
}

if ($key) $sqlkey.=" AND orderid='$key'";
if ($ispay==1) $sqlkey.=" AND ispay=1";elseif($ispay==2) $sqlkey.=" AND ispay=0";

$pagesize=15;
$page=(int)$_GET['page']==0?1:(int)$_GET['page'];
$sql="select * from `{$tablepre}orders` where 1=1 {$sqlkey} order by id desc";
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
<title>我的订单页面</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="js/checkform.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script language="javascript" type="text/javascript" src="../My97DatePicker/WdatePicker.js"></script>
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

<table class="tablelist">
	<form action="" method="get" name="formlists">
		<tbody>
			<tr>
				<td>
					<b>订单号：</b><input name="key" type="text" class="dfinput" value="<?=$key?>" style="width:200px;"/>
					<b>付款状态：</b>
					<select name="ispay" style="color:#FFFFFF; background-color:#0000FF; height:30px; line-height:30px;">
						<option value="0"<? if($ispay==0) echo 'selected'?>>付款状态</option>
						<option value="2"<? if($ispay==2) echo 'selected'?>>未付款</option>
						<option value='1'<? if($ispay==1) echo 'selected'?>>已付款</option>
					</select>		  
					<input name="search" type="submit" class="btn" value="搜索"/>
				</td>
			</tr> 
		</tbody>
	</form>
</table>

<div class="rightinfo">

	<form name="formlist" method="post" action="?action=del&siteid=<?=$siteid?>">
	
		<div class="tools">
		
			<ul class="toolbar">
				<li><a href="?<?=$url?>&siteid=<?=$siteid?>"><img src="images/sx.jpg" /></a></li>
				<li>&nbsp;<input type="checkbox" name="all" onClick="CheckAll(this);"> 全选</li>
				<li><input type="image" src="images/del.jpg" onClick="return checkData();" name="ok" value="删 除"></li>
			</ul>
		
		</div>

		<table class="tablelist">
			<thead>
				<tr>
				<th width="36">选择</th>
				<th width="50">编号</th>
				<th width="90">订单会员</th>
				<th width="120">订单号</th>
				<th width="100">订购时间</th>
				<th width="60">付款方式</th>
				<th width="74">总金额</th>
				<th width="106">订单状态</th>
				<th width="85">操作</th>
				</tr>
			</thead>
			<tbody>
			<?
			$i=0;
			while($row=$db->sql_fetchrow($result)){
				$i++;	
				if($row['isstate']) $isstate="<a href='?action=isstate&id=".$row['id']."'><font color=blue>已开启</font></a>";else $isstate="<a href='?action=isstate&id=".$row['id']."'><font color=red>已关闭</font></a>";
				if($row['ispay']) $ispay="<a href='?action=ispay&id=".$row['id']."'><font color=blue>已付款</font></a>";else $ispay="<a href='?action=ispay&id=".$row['id']."'><font color=red>未付款</font></a>";
				if($row['isship']) $isship="<a href='?action=isship&id=".$row['id']."'><font color=blue>已发货</font></a>";else $isship="<a href='?action=isship&id=".$row['id']."'><font color=red>未发货</font></a>";
				if($row['isreceipt']) $isreceipt="<a href='?action=isreceipt&id=".$row['id']."'><font color=blue>已收货</font></a>";else $isreceipt="<a href='?action=isreceipt&id=".$row['id']."'><font color=red>未收货</font></a>";
				
				
			?>
			<tr>
				<td><input name="checkid[]" type="checkbox" id="checkid[]" value="<?=$row['id']?>"></td>
				<td><?=$i?></td>
				<td><?=get_username($row['uid'])?></td>
				<td><?=$row['orderid']?></td>
				<td><?=date('Y-m-d H:m:s',$row['sendtime'])?></td>
				<td><?=$webarr["zid"][$row['zid']]?> </td>
				<td width="74"><?=$row['totalprice']+$row['payprice']?> 元</td>
				<td><?=$isstate?> <?=$ispay?> <?=$isship?> <?=$isreceipt?></td>
				<td>
					<?
						if($row['isstate']==1){
							echo("<a href='show.php?id=".$row['id']."'><font color='#FF00FF'>已查看</font></a>");
						}else{
							echo("<a href='show.php?id=".$row['id']."'><font color='#FF0000'>未查看</font></a>");
					} ?>
				</td>
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
