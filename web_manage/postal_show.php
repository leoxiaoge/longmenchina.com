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
<title>留言详情</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/select.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div class="place">
	<span>位置：</span>
	<ul class="placeul">
		<li><a href="mains.php">首页</a></li>
		<li>留言详情</li>
	</ul>
</div>
    
<div class="formbody">

	<div id="usual1" class="usual">
	
		<div class="itab">
			<ul> 
				<li><a href="#tab1" class="selected">留言详情</a></li> 
				<li><a href="javascript:history.go(-1);">返回列表页</a></li> 
			</ul>
		</div> 
	
		<div id="tab1" class="tabson">
			
			<div class="formtext">Hi，<b><?=$_SESSION['Admin_UserName']?></b>，欢迎您使用信息发布功能！</div>
		
			<div class="place">
				<ul class="placeul">
					<li><b>留言详情</b></li>
				</ul>
			</div>
		
			<table class="tablelist">
			
				<tr>
					<td width="25%" align="right">姓名：</td>
					<td align="left"><?=$bd['realname']?></td>
				</tr>
				
				<tr>
					<td width="25%" align="right">电话：</td>
					<td align="left"><?=$bd['tel']?></td>
				</tr>
				
				<tr>
					<td width="25%" align="right">qq：</td>
					<td align="left"><?=$bd['qq']?></td>
				</tr>
				
				<tr>
					<td width="25%" align="right">微信：</td>
					<td align="left"><?=$bd['wx']?></td>
				</tr>
				
				<tr>
					<td width="25%" align="right">你是从什么时候开始接触桂林米粉的？你心目中的桂林米粉是什么样的？：</td>
					<td align="left"><?=$bd['content']?></td>
				</tr>
				
				<tr>
					<td width="25%" align="right">选择学习桂林米粉的原因是什么？：</td>
					<td align="left"><?=$bd['content1']?></td>
				</tr>
				
				<tr>
					<td width="25%" align="right">你为什么选择来聚口福学手艺？：</td>
					<td align="left"><?=$bd['content2']?></td>
				</tr>
				
				<tr>
					<td width="25%" align="right">来聚口福学手艺，你最希望聚口福能够帮你解决什么问题？：</td>
					<td align="left"><?=$bd['content3']?></td>
				</tr>
				
				<tr>
					<td width="25%" align="right">你希望到聚口福学成这套手艺之后，对你会带来什么样的改变？：</td>
					<td align="left"><?=$bd['content4']?></td>
				</tr>
				
				<tr>
					<td width="25%" align="right">从1到10，对你来说，这样的改变对你有多迫切？为什么？（10为最迫切）：</td>
					<td align="left"><?=$bd['content5']?></td>
				</tr>
				
				<tr>
					<td width="25%" align="right">你计划来学手艺的时间是什么时间？：</td>
					<td align="left"><?=$bd['content6']?></td>
				</tr>
				
				<tr>
					<td width="25%" align="right">如果你此次预约提交报名名额已满，你是否愿意自动排期到下一期？：</td>
					<td align="left"><?=$bd['content7']?></td>
				</tr>
			
			</table>
		
			<div class="clear"></div>		

		</div> 
		
	</div> 

</div>

</body>

</html>