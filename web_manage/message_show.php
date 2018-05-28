<?
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';
$id=$_GET['id'];
$sql="select * from `{$tablepre}message` where id={$id}";
//echo $sql;
$result=$db->sql_query($sql);
if($bd=$db->sql_fetchrow($result)){
	$db->sql_query("update `{$tablepre}message` set isshow=1 where id='$id'");
}else{
	die("非法操作");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>询价服务详情</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/select.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div class="place">
	<span>位置：</span>
	<ul class="placeul">
		<li><a href="mains.php">首页</a></li>
		<li>询价服务详情</li>
	</ul>
</div>
    
<div class="formbody">

	<div id="usual1" class="usual">
	
		<div class="itab">
			<ul> 
				<li><a href="#tab1" class="selected">询价详情</a></li> 
				<li><a href="javascript:history.go(-1);">返回列表页</a></li> 
			</ul>
		</div> 
	
		<div id="tab1" class="tabson">
			
			<div class="formtext">Hi，<b><?=$_SESSION['Admin_UserName']?></b>，欢迎您使用信息发布功能！</div>
		
			<div class="place">
				<ul class="placeul">
					<li><b>询价详情</b></li>
				</ul>
			</div>
		
			<table class="tablelist">
			
				<tr>
					<td width="25%" align="right">Company Name：</td>
					<td align="left"><?=$bd['Company_Name']?></td>
				</tr>
				
				<tr>
					<td width="25%" align="right">Contact Name：</td>
					<td align="left"><?=$bd['Contact_Name']?></td>
				</tr>
				
				<tr>
					<td width="25%" align="right">Tel：</td>
					<td align="left"><?=$bd['tel']?></td>
				</tr>
				
				<tr>
					<td width="25%" align="right">Fax：</td>
					<td align="left"><?=$bd['fax']?></td>
				</tr>
				
				<tr>
					<td width="25%" align="right">Email：</td>
					<td align="left"><?=$bd['email']?></td>
				</tr>
				
				<tr>
					<td width="25%" align="right">Remark：</td>
					<td align="left"><?=$bd['Remark']?></td>
				</tr>
			
			</table>
		
			<div class="clear"></div>	
				
			<div class="place">
				<ul class="placeul">
					<li><b>商品列表</b></li>
				</ul>
			</div>
			
			<table class="tablelist">
				<tr>
					<th width="20%" align="center">Part No</th>
					<th width="20%" align="center">Brabd</th>
					<th width="15%" align="center">Package</th>
					<th width="15%" align="center">Date Code</th>
					<th width="15%" align="center">Qty</th>
					<th width="15%" align="center">Request Qty</th>
				</tr>
				<?
				$sql1="select * from `{$tablepre}message` where id='$id'";
				//echo $sql1;
				$result1=$db->sql_query($sql1);
				if($rs=$db->sql_fetchrow($result1)){	
					
					$prods=explode(",",$rs['prods'].",");
					$rid=explode(",",$rs['rid'].",");
	
					for($i=0;$i<=count($prods);$i++){
						$ids=$prods[$i];
						$rds=$rid[$i];
				?>      
				<tr>
					<td align="left"><?=get_zd_name("title","news"," and id={$ids} ")?></td>
					<td><?=get_zd_name("ftitle","news"," and id={$ids} ")?></td>
					<td><?=get_zd_name("linkurl","news"," and id={$ids} ")?></td>
					<td><?=get_zd_name("price","news"," and id={$ids} ")?></td>
					<td><?=get_zd_name("introduce","news"," and id={$ids} ")?></td>
					<td><?=$rds?></td>
				</tr>
				<? } } ?>
		  	</table>
				
			<div class="clear"></div>
		</div> 
		
	</div> 

</div>

</body>

</html>