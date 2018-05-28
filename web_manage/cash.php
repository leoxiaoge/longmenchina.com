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
  
$gourl=get_tourl($pid,$ty,$tty,$ttty,"{$filenames}");
$classname=get_toclassname($pid,$ty,$tty,$ttty);
$cname=get_cname($pid,$ty,$tty,$ttty);

if ($action=="confirm"){
	if (empty($id)){
		JsError("参数提交错误");
	}
	
	$money=get_zd_name("money","ucash"," and id={$id}");
	
	$uid=get_zd_name("uid","ucash"," and id={$id}");
	
 	$sql="update `{$tablepre}ucash` SET ispay=NOT(ispay) WHERE id=".$id;

	if($db->sql_query($sql)){
		AddLog("发放{$cname}栏目提现",$_SESSION['Admin_UserName']);
		JsSucce("操作成功！","{$gourl}".$indexurl);
	}else{
		JsError("操作失败！");
	}
	exit();

}elseif ($action=="confirm2"){
	if (empty($id)){
		JsError("参数提交错误");
	}
	
	$money=get_zd_name("money","ucash"," and id={$id}");
	
	$uid=get_zd_name("uid","ucash"," and id={$id}");
	
 	$sql="update `{$tablepre}ucash` SET isstate=NOT(isstate) WHERE id=".$id;
	
	$db->sql_query("update `{$tablepre}users` set balance=balance+".$money." where id={$uid}");
	
	if($db->sql_query($sql)){
		AddLog("发放{$cname}栏目提现",$_SESSION['Admin_UserName']);
		JsSucce("操作成功！","{$gourl}".$indexurl);
	}else{
		JsError("操作失败！");
	}
	exit();

}elseif ($action=="del"){
	$del_num=count($checkid);
	for($i=0;$i<$del_num;$i++){
		$insertid=$db->delete("ucash"," id=".$checkid[$i]."");
 		JsSucce("操作成功！","{$gourl}".$indexurl);
	}
	AddLog("删除{$cname}栏目提现",$_SESSION['Admin_UserName']);
	exit();
}

$pagecomments=100;
$page=(int)$_GET['page']==0?1:(int)$_GET['page'];
$sql="select * FROM `{$tablepre}ucash` where 1=1 {$sqlkey} order by id desc";
///echo $sql;
$pagestr=page_list($sql,$page,$pagecomments);
$sql.=" limit ".(($page-1)*$pagecomments).",$pagecomments";	
$result=$db->sql_query($sql);
//echo $sql;
$PageCount=$db->sql_numrows($result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>提现信息管理页面</title>
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
				<li>&nbsp;<input type="checkbox" name="all" onClick="CheckAll(this);"> 全选</li>
				<li><input type="image" src="images/del.jpg" onClick="return checkData();" name="ok" value="删 除"></li>
			</ul>
		
		</div>

		<table class="tablelist">
			<thead>
				<tr>
					<th width="5%">选择</th>
					<th width="5%">编号</th>
					<th width="10%">提现用户</th>
					<th width="10%">提现金额</th>
					<th width="15%">提现银行</th>
					<th width="8%">姓名</th>
					<th width="9%">是否审核</th>
					<th width="13%">发布时间</th>
				</tr>
			</thead>
			<tbody>
			<?
			$i=0;
			while($bd=$db->sql_fetchrow($result)){
				$i++;					
				if($bd['isstate']==1)
					$zt="<a href='?action=confirm2&id=".$bd['id']."&title=".$bd['title']."'><font color='red'>已提交</font></a>";
				else
					$zt="<font color=''>已拒绝</font>";
			
				if($bd['ispay']==1)
					$ispay="<a href='?action=confirm&id=".$bd['id']."&title=".$bd['title']."'><font color='red'>已发放</font></a>";
				else
					$ispay="<a href='?action=confirm&id=".$bd['id']."&title=".$bd['title']."'><font color=''>未发放</font></a>";
				$yid=get_zd_name("title","bank"," and id={$bd['bank']} ");
			?>
			<tr>
				<td><input name="checkid[]" type="checkbox" id="checkid[]" value="<?=$bd['id']?>"></td>
				<td><?=$i?></td>
				<td><?=get_zd_name("username","users"," and id={$bd['uid']} ")?></td>
				<td><?=$bd['money']?>元  手续费<?=$bd['moneys']?>元</td>
				<td><?=get_zd_name("kahao","bank"," and id={$bd['bank']} ")?><br />　<?=get_zd_name("title","news"," and id={$yid} ")?></td>
				<td><?=$bd['xm']?></td>
				<td><?=$zt?> <?=$ispay?></td>
				<td><?=date('Y-m-d H:i:s',$bd['sendtime'])?></td>
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
