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
 	
	$sql="update `{$tablepre}size2` SET isstate=NOT(isstate) WHERE id=".$id;
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
		$insertid=$db->delete("size2"," id=".$checkid[$i]."");
 		JsSucce("操作成功！","{$gourl}".$indexurl);
	}
	AddLog("删除{$cname}栏目内容",$_SESSION['Admin_UserName']);
	exit();
}

$pagesize=15;
$page=(int)$_GET['page']==0?1:(int)$_GET['page'];
$sql="select * FROM `{$tablepre}size2` where 1=1 {$sqlkey} order by disorder desc,id asc";
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
<title>现金抵用券管理页面</title>
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
				<li><a href="<?=$filenames?>_add.php?pid=<?=$pid?>&ty=<?=$ty?>&tty=<?=$tty?>&ttty=<?=$ttty?>"><img src="images/add.jpg" /></a></li>
				<li><input type="image" src="images/del.jpg" onClick="return checkData();" name="ok" value="删 除"></li>
			</ul>
		
		</div>
	
		<table class="tablelist">
			<thead>
				<tr>
					<th width="5%">选择</th>
					<th width="5%">编号</th>
					<th width="8%">缩略图</th>
					<th width="20%">信息标题</th>
					<th width="8%">是否审核</th>
					<th width="12%">发布时间</th>
					<th width="7%">操作</th>
				</tr>
			</thead>
			<tbody>
			<?
			$i=0;
			while($bd=$db->sql_fetchrow($result)){
				$i++;
				if(!empty($bd['img1'])) $Img="<img src=\"{$path}/{$bd['img1']}\" width=\"80\" />"; else $Img="暂未上传图片";
				if($bd['isstate']==1)
					$zt="<a href='?action=confirm&pid={$pid}&ty={$ty}&tty={$tty}&ttty={$ttty}&id=".$bd['id']."'><font color='red'>未使用</font></a>";
				else
					$zt="<a href='?action=confirm&pid={$pid}&ty={$ty}&tty={$tty}&ttty={$ttty}&id=".$bd['id']."'><font color=''>已使用</font></a>";
			?>
			<tr>
				<td><input name="checkid[]" type="checkbox" id="checkid[]" value="<?=$bd['id']?>"></td>
				<td><?=$i?></td>
				<td><?=$Img?></td>
				<td><?=$bd['title']?></td>
				<td><?=$zt?></td>
				<td><?=date('Y-m-d H:i:s',$bd['sendtime'])?></td>
				<td><a href="<?=$filenames?>_edit.php?id=<?=$bd['id']?>&indexurl=<?=$url?>" class="tablelink">修改</a></td>
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
