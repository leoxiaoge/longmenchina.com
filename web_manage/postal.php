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
 	
	$sql="update `{$tablepre}postal` SET ispay=NOT(ispay) WHERE id=".$id;
	
	$balance=get_zd_name("balance","postal"," and id={$id} ")-get_zd_name("price","postal"," and id={$id} ");
	$uid=get_zd_name("uid","postal"," and id={$id} ");
	
	$sql2="update `{$tablepre}users` SET balance={$balance} WHERE id=".$uid;
	//exit($sql2);
	$db->sql_query($sql2);
	
	if($db->sql_query($sql)){

		AddLog("发放{$cname}申请提现",$_SESSION['Admin_UserName']);
		JsSucce("操作成功！","{$gourl}");

	}else{

		JsError("操作失败！");

	}

	exit();

}elseif ($action=="del"){
	$del_num=count($checkid);

	for($i=0;$i<$del_num;$i++){
		$insertid=$db->delete("postal"," id=".$checkid[$i]."");
		JsSucce("操作成功！","{$gourl}".$indexurl);
	}
	AddLog("删除{$cname}栏目内容",$_SESSION['Admin_UserName']);
	exit();
}

if ($key) $sqlkey.=" AND binary title like '%".$key."%'";

$pagesize=15;
$page=(int)$_GET['page']==0?1:(int)$_GET['page'];
$sql="SELECT * FROM `{$tablepre}postal` where isshow=1 order by id desc";
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
<title>提现页面</title>
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
					<th width="100">会员账号</th>
					<th width="100">姓名</th>
					<th width="100">余额</th>
					<th width="100">提现金额</th>
					<th width="200">提现银行</th>
					<th width="100">操作</th>
					<th width="130">提交时间</th>
				</tr>
			</thead>
			
			<tbody>
				<?
				while($bd=$db->sql_fetchrow($result)){	
				
				if($bd['ispay']==1)
					$ispay="<font color='red'>已发放</font>";
				else
					$ispay="<a href=\"?id={$bd['id']}&action=confirm\"><font color=''>未发放</font></a>";
		
				?>
				<tr>
					<td><input name="checkid[]" type="checkbox" id="checkid[]" value="<?=$bd['id']?>"></td>
					<td><?=$bd['id']?></td>
					<td><?=get_zd_name("username","users"," and id={$bd['uid']} ")?></td>
					<td><?=$bd['realname']?></td>
					<td><?=$bd['balance']?>元</td>
					<td><?=$bd['price']?>元</td>
					<td>
						<?=get_zd_name("title","news"," and id=".get_zd_name("title","bank"," and id={$bd['bank']} ")." ")?>
						(<?=get_zd_name("leixin","bank"," and id={$bd['bank']} ")?>)<br />
						<?=get_zd_name("kahao","bank"," and id={$bd['bank']} ")?>						
					</td>
					<td><?=$ispay?></td>
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
