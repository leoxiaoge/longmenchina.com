<?php
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';
$filenames=basename($PHP_SELF,".php");
 
$checkid=opturl("checkid");
$action=opturl("action");
$id=$_GET['id'];

$typeid=(int)opturl("typeid");
$key=trim(opturl("key"));

$showtype=get_showtype($pid,$ty,$tty,$ttty);
 
$path="../".$upload_picpath."/";
 
$gourl=get_tourl($pid,$ty,$tty,$ttty,"{$filenames}");
$classname=get_toclassname($pid,$ty,$tty,$ttty);
$cname=get_cname($pid,$ty,$tty,$ttty);

if ($action=="confirm"){
	if (empty($id)){
		JsError("参数提交错误");
	}
	$sql="update `{$tablepre}sell_temp` SET isstate=NOT(isstate) WHERE id=".$id;
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
		$sql="select img1,img2,img3,img4,img5,img6,file FROM `{$tablepre}sell_temp` where id=".$checkid[$i]."";
		$result=$db->sql_query($sql);
		if($bd=$db->sql_fetchrow($result)){
			@unlink($path.$bd['img1']);
			@unlink($path.$bd['img2']);
			@unlink($path.$bd['img3']);
			@unlink($path.$bd['img4']);
			@unlink($path.$bd['img5']);
			@unlink($path.$bd['img6']);
			@unlink($path.$bd['file']);
			
			$insertid=$db->delete("sell_temp"," id=".$checkid[$i]."");
 		}
		JsSucce("操作成功！","{$gourl}".$indexurl);
	}
	AddLog("删除{$cname}栏目内容",$_SESSION['Admin_UserName']);
	exit();
}

if ($key) $sqlkey.="where binary title like '%".$key."%'";
$pagesize=15;
$page=(int)$_GET['page']==0?1:(int)$_GET['page'];
$sql="select * FROM `{$tablepre}sell_temp` {$sqlkey} order by id desc,id desc";
//echo $sql;
$pagestr=page_list($sql,$page,$pagesize);
$sql.=" limit ".(($page-1)*$pagesize).",$pagesize";	
$result=$db->sql_query($sql);
$PageCount=$db->sql_numrows($result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新闻管理页面</title>
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
	
<table class="tablelist">
	
	<form action="<?=$filenames?>.php" method="get" name="formlists">

		<thead>
	
		<tbody>
			<tr>
				<td>
					<b>产品名称：</b><input name="key" type="text" class="dfinput" value="<?=$key?>" style="width:200px;"/>
				</td>
			</tr> 
		</tbody>
	
	</form>

</table>	

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
				<th width="4%">选择</th>
				<th width="4%">编号</th>
				<th width="8%">发布至</th>
				<th width="8%">缩略图</th>
				<th width="6%">发布人</th>
				<th width="15%">产品名称</th>
				<th width="6%">图片管理</th>
				<th width="6%">是否审核</th>
				<th width="10%">发布时间</th>
				<th width="5%">操作</th>
				</tr>
			</thead>
			<tbody>
			<?
			$i=0;
			while($bd=$db->sql_fetchrow($result)){	
				$i++;

				if ($bd['isgood']==1) $bj="<font color='red'>推荐</font>"; else $bj='';

				if(!empty($bd['img1'])) $Img="<img src=\"{$path}/{$bd['img1']}\" width=\"80\" />"; else $Img="暂未上传图片";

				if($bd['pid']==80)
					$a="自行发布";
				else
					$a="上门验货";
				
				if($bd['isstate']==1)
					$zt="<a href='?action=confirm&pid={$pid}&ty={$ty}&tty={$tty}&ttty={$ttty}&id=".$bd['id']."'><font color='red'>已审核</font></a>";
				else
					$zt="<a href='?action=confirm&pid={$pid}&ty={$ty}&tty={$tty}&ttty={$ttty}&id=".$bd['id']."'><font color=''>未审核</font></a>";
				
				if($bd['ispay']==1)
					$pay="<a href='?action=confirm2&id=".$bd['id']."'><font color='red'>已付款</font></a>";
				else
					$pay="<a href='?action=confirm2&id=".$bd['id']."'><font color=''>未付款</font></a>";
			?>
			<tr>
				<td><input name="checkid[]" type="checkbox" id="checkid[]" value="<?=$bd['id']?>"></td>
				<td><?=$i?></td>
				<td><?=get_zd_name("catname","prod_cats"," and id={$bd['xid']} ")?>-<?=get_zd_name("catname","prod_cats"," and id={$bd['lid']} ")?></td>
				<td><?=$Img?></td>
				<td><?=$bd['tel']?></td>
				<td><font color="red"><?=$a?></font> <?=$bd['title']?> <?=$bj?></td>
				<td>
					<a href="size.php?pid=<?=$bd['id']?>&indexurl=<?=$url?>" class="tablelink">外观</a> &nbsp;&nbsp;
					 <a href="size2.php?pid=<?=$bd['id']?>&indexurl=<?=$url?>" class="tablelink">内部</a>
				</td>
				<td><?=$zt?> <?=$pay?></td>
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
