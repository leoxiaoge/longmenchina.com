<?
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';
$id=$_GET['id'];
$path="../".$upload_picpath."/";

$sql="select * from `{$tablepre}orders` where id={$id}";
$result=$db->sql_query($sql);
if($bd=$db->sql_fetchrow($result)){
	$uid=$bd['uid'];
	$orderid=$bd['orderid'];
	$payprice=$bd['payprice'];
	$ispay=$bd['ispay'];
	$oid=$bd['id'];
	
	if($bd['isstate']) $isstate="<font color=blue>已开启</font>";else $isstate="<font color=red>未开启</font>";
	if($bd['ispay']) $ispay="<font color=blue>已付款</font>";else $ispay="<font color=red>未付款</font>";
	if($bd['isship']) $isship="<font color=blue>已发货</font>";else $isship="<font color=red>未发货</font>";
	if($bd['isreceipt']) $isreceipt="<font color=blue>已收货</font>";else $isreceipt="<font color=red>未收货</font>";
	
	
}else{
	die("非法操作");
}
 
if(isset($_POST['update'])){
  	$referer=$_POST['referer'];
 	$sqlvalues="";
	$fields=$_POST['fields'];
	$fields['titleems']=$_POST['titleems'];
	$fields['ems']=$_POST['ems'];

  	while(list ($key,$val) =each ($fields)){
		$sqlvalues.=",$key='$val'";
	}

	$sqlvalues=substr($sqlvalues,1);

	$sql="update `{$tablepre}orders` SET ".$sqlvalues." where id ='$id'";
	//echo $sql;
	//exit();
  	if($db->sql_query($sql)){

 		$sql2="update `{$tablepre}sell_temp` SET price={$_POST['totalprice']} where id='{$bd['cid']}'";

  		if($db->sql_query($sql2)){
			JsSucce("操作成功！","orders.php");
			//exit();
		}

	}else{

		JsError("操作失败！");

	}

	exit();

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>我的订单详情</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/select.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="../My97DatePicker/WdatePicker.js"></script>
</head>

<body>

<div class="place">
	<span>位置：</span>
	<ul class="placeul">
		<li><a href="mains.php">首页</a></li>
		<li>我的订单详情</li>
	</ul>
</div>
    
<div class="formbody">

	<div id="usual1" class="usual"> 
	
		<div class="itab">
			<ul> 
				<li><a href="#tab1" class="selected">订单详情</a></li> 
				<li><a href="javascript:void()" onclick="javascript:history.back(-1);">返回列表页</a></li> 
			</ul>
		</div> 
	
		<div id="tab1" class="tabson">
			
			<div class="formtext">Hi，<b><?=$_SESSION['Admin_UserName']?></b>，欢迎您使用信息发布功能！</div>
		
			<div class="place">
				<ul class="placeul">
					<li><b>订单状态</b></li>
				</ul>
			</div>
		
			<table class="tablelist">
				<tr>
					<td width="15%" align="right">订单号：</td>
					<td align="left"><?=$bd['orderid']?></td>
				</tr>
				<tr>
					<td align="right">订单状态：</td>
					<td align="left"><?=$isstate?> <?=$ispay?> <?=$isship?> <?=$isreceipt?></td>
				</tr>
				<tr>
					<td align="right">支付方式：</td>
					<td align="left"><?=$webarr["zid"][$bd['zid']]?></td>
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
					<th width="12%" align="center">商品图片</th>
					<th width="36%" align="center">商品名称</th>
					<th width="14%" align="center">卖家价格</th>
					<th width="20%" align="center">小计</th>
				</tr>
				<?
				$sql2="select * from `{$tablepre}sell_temp` where id={$bd['cid']}";
				$result2=$db->sql_query($sql2);
				if($bd2=$db->sql_fetchrow($result2)){
					
				}
				?>    
				<tr>
					<td><img src="../<?=$upload_picpath?>/<?=$bd2['img1']?>" width="50" height="50" class="bor"/></td>
					<td align="left"><a target="_blank" href="../show.php?id=<?=$bd2['cid']?>"><?=$bd2['title']?></a></td>
					<td>￥<?=number_format($bd2['price'],2,".","")?></td>
					<td>￥<?=number_format($bd2['price'],2,".","")?></td>
				</tr>
		  	</table>
				
			<div class="clear"></div>
		
			<div class="place">
				<ul class="placeul">
					<li><b>费用总计</b></li>
				</ul>
			</div>
			
			<table class="tablelist">
			
				<tr>
					<td align="right">
					商品总价: ￥<?=number_format($bd2['price'],2,".","")?></td>
				</tr>
				
				<tr>
					<td align="right">付款金额: ￥<?=number_format($bd['payprice'],2,".","")?></td>
				</tr>
				
				<tr>
					<td align="right">管理员备注: <?=$bd['message']?></td>
				</tr>
		  	</table>	
				
			<div class="clear"></div>
				
			<div class="place">
				<ul class="placeul">
					<li><b>收货人信息</b></li>
				</ul>
			</div>
			<table class="tablelist">
				<tr>
					<td width="15%" align="right">收货人姓名： </td>
					<td width="35%" align="left"><?=$bd['realname']?></td>
					<td align="right">手机：</td>
					<td align="left"><?=$bd['tel']?></td>
				</tr>
				<tr>
					<td align="right">详细地址：</td>
					<td align="left"><?=$bd['province']?><?=$bd['city']?><?=$bd['town']?><?=$bd['address']?></td>
					<td width="15%" align="right">电子邮箱： </td>
					<td width="35%" align="left"><?=$bd['email']?></td>
				</tr>
			</table>	 
		
			<div class="clear"></div>
			
			<div class="place">
				<ul class="placeul">
					<li><b>发货信息</b></li>
				</ul>
			</div>
		  
			<table class="tablelist">
			
				<form name="form1" method="post" action="">
				
					<tr>
						<td width="15%" align="right">订单金额：</td>
						<td align="left">
							<input name="totalprice" type="text" id="totalprice" size="8" class="dfinput" value="<?=get_zd_name("price","sell_temp"," and id={$bd['cid']} ")?>"/>元
						</td>
					</tr>
					<tr>
						<td align="right">快递商家：</td>
						<td align="left">
							<input name="titleems" type="text" id="titleems" value="<?=$bd['titleems']?>" class="dfinput" />
						</td>
					</tr>
					<tr>
						<td align="right">快递单号：</td>
						<td align="left">
							<input name="ems" type="text" id="ems" value="<?=$bd['ems']?>" class="dfinput" />
						</td>
					</tr>
					<tr>
						<td align="right"> </td>
						<td align="left">
							<input name="update" type="submit" id="update" value=" 确定提交 " class="btn"> 
							<input name="orderid" type="hidden" value="<?=$orderid?>">
						</td>
					</tr>
				</form>
				
			</table>
				
		</div> 

	</div> 

</div>

</body>

</html>