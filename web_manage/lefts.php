<?php
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>网站信息</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="js/jquery.js"></script>
<script type="text/javascript">
$(function(){	
	//导航切换
	$(".menuson li").click(function(){
		$(".menuson li.active").removeClass("active")
		$(this).addClass("active");
	});
	
	$('.title').click(function(){
		var $ul = $(this).next('ul');
		$('dd').find('ul').slideUp();
		if($ul.is(':visible')){
			$(this).next('ul').slideUp();
		}else{
			$(this).next('ul').slideDown();
		}
	});
})	
</script>


</head>
<body style="background:#f0f9fd;">
	<div class="lefttop"><span></span>网 站 信 息</div>
    
    <dl class="leftmenu" style="height:420px; overflow-y:auto; padding-bottom:0px; width:220px;">
	
	<?
	if ($_SESSION['Admin_BigMyMenu']=="super"){
		$sql1="SELECT * FROM `{$tablepre}news_cats` WHERE pid=0 and isstate=1 ORDER BY disorder ASC, id ASC";
	}else{
		$sql1="SELECT * FROM `{$tablepre}news_cats` WHERE  pid=0 AND id in (".$_SESSION['Admin_BigMyMenu'].") AND isstate=1 ORDER BY disorder ASC, id ASC";
	}
	$result1=$db->sql_query($sql1);
	while($bd1=$db->sql_fetchrow($result1)){
		$pid=$bd1['id'];
	?>
		<dd>
			<div class="title"><span><img src="images/leftico01.png" /></span><?=$bd1['catname']?></div>
			<ul class="menuson">
   		<?
		
		if ($_SESSION['Admin_BigMyMenu']=="super"){
			$sql2="SELECT * FROM `{$tablepre}news_cats` WHERE pid=".$pid." AND isstate=1 ORDER BY disorder ASC,id ASC";
		}else{
			$sql2="SELECT * FROM `{$tablepre}news_cats` WHERE  pid=".$pid." AND id in (".$_SESSION['Admin_SmallMyMenu'].") AND isstate=1 ORDER BY disorder ASC, id ASC";
		}
		$result2=$db->sql_query($sql2);
		$m=0;
		while($bd2=$db->sql_fetchrow($result2)){
			$m++;
			//echo $showtype;
			$ppid=$bd2['id'];
			$showtype=$bd2['showtype'];
 				if(!empty($bd2['linkurl'])){
					$linkurl="{$bd2['linkurl']}?pid={$bd2['pid']}&ty={$bd2['id']}";
				}else{
					if($showtype==2){
						$linkurl="content.php?pid={$bd2['pid']}&ty={$bd2['id']}";
					}elseif($showtype==4){
						$linkurl="link.php?pid={$bd2['pid']}&ty={$bd2['id']}";
					}elseif($showtype==5){
						$linkurl="gg.php?pid={$bd2['pid']}&ty={$bd2['id']}";
					}elseif($showtype==6){
						$linkurl="prod.php?pid={$bd2['pid']}&ty={$bd2['id']}";
					}elseif($showtype==7){
						$linkurl="case.php?pid={$bd2['pid']}&ty={$bd2['id']}";
					}elseif($showtype==8){
						$linkurl="job.php?pid={$bd2['pid']}&ty={$bd2['id']}";
					}elseif($showtype==9){
						$linkurl="file.php?pid={$bd2['pid']}&ty={$bd2['id']}";
					}elseif($showtype==10){
						$linkurl="kc.php?pid={$bd2['pid']}&ty={$bd2['id']}";
					}elseif($showtype==11){
						$linkurl="file.php?pid={$bd2['pid']}&ty={$bd2['id']}";
					}elseif($showtype==12){
						$linkurl="case.php?pid={$bd2['pid']}&ty={$bd2['id']}";
					}elseif($showtype==14){
						$linkurl="jc.php?pid={$bd2['pid']}&ty={$bd2['id']}";
					}elseif($showtype==13){
						$linkurl="team.php?pid={$bd2['pid']}&ty={$bd2['id']}";
					}elseif($showtype==14){
						$linkurl="team.php?pid={$bd2['pid']}&ty={$bd2['id']}";
					}else{
						$linkurl="news.php?pid={$bd2['pid']}&ty={$bd2['id']}";
					}
				}
				
				$counts=get_son_count($ppid);
				if ($counts>0) {
					if($pid==1||$pid==3){
						$linkurl="content.php?pid={$bd2['pid']}&ty={$bd2['id']}"; 
					}else{
						$linkurl="javascript:void()"; 
					}
				}else{
					 $linkurl=$linkurl;
				} 
				$counts=get_son_count($ppid);
				if ($counts>0) $linkurl="javascript:void()"; else $linkurl=$linkurl;
 		?>
			
			<li style="margin-left:-20px;"><cite></cite><a href="<?=$linkurl?>" target="rightFrame"><?=$bd2['catname']?></a><i></i></li>
			
   			<?
			$sql3="SELECT * FROM `{$tablepre}news_cats` WHERE pid=".$ppid." AND isstate=1 ORDER BY disorder ASC,id ASC";
  			//echo $ppsql;
			$result3=$db->sql_query($sql3);
			while($bd3=$db->sql_fetchrow($result3)){
				$pppid=$bd3['id'];
				$showtype2=$bd3['showtype'];
				if(!empty($bd3['linkurl'])){
					$linkurl2="{$bd3['linkurl']}?pid={$pid}&ty={$ppid}&tty={$bd3['id']}";
				}else{
					if($showtype2==2){
						$linkurl2="content.php?pid={$pid}&ty={$ppid}&tty={$bd3['id']}";
					}elseif($showtype2==4){
						$linkurl2="link.php?pid={$pid}&ty={$ppid}&tty={$bd3['id']}";
					}elseif($showtype2==5){
						$linkurl2="gg.php?pid={$pid}&ty={$ppid}&tty={$bd3['id']}";
					}elseif($showtype2==6){
						$linkurl2="prod.php?pid={$pid}&ty={$ppid}&tty={$bd3['id']}";
					}elseif($showtype2==7){
						$linkurl2="prod.php?pid={$pid}&ty={$ppid}&tty={$bd3['id']}";
					}elseif($showtype2==8){
						$linkurl2="job.php?pid={$pid}&ty={$ppid}&tty={$bd3['id']}";
					}elseif($showtype2==9){
						$linkurl2="file.php?pid={$pid}&ty={$ppid}&tty={$bd3['id']}";
					}elseif($showtype2==10){
						$linkurl2="cy.php?pid={$pid}&ty={$ppid}&tty={$bd3['id']}";
					}elseif($showtype2==11){
						$linkurl2="cy.php?pid={$pid}&ty={$ppid}&tty={$bd3['id']}";
					}else{
						$linkurl2="news.php?pid={$pid}&ty={$ppid}&tty={$bd3['id']}";
					}
				}
			?>
			<li style="margin-left:-20px;" class="pro_type2"><a href="<?=$linkurl2?>" target="rightFrame"><?=$bd3['catname']?></a></li>
			<?
			$sql4="SELECT * FROM `{$tablepre}news_cats` WHERE pid=".$pppid." AND isstate=1 ORDER BY disorder ASC,id ASC";
 			//echo $ppsql;
			$result4=$db->sql_query($sql4);
			while($bd4=$db->sql_fetchrow($result4)){
				$showtype3=$bd4['showtype'];
				if(!empty($bd4['linkurl'])){
					$linkurl3=$bd4['linkurl'];
				}else{
					if($showtype3==2){
						$linkurl3="content.php?pid={$pid}&ty={$ppid}&tty={$bd3['id']}&ttty={$bd4['id']}";
					}elseif($showtype2==4){
						$linkurl3="link.php?pid={$pid}&ty={$ppid}&tty={$bd3['id']}&ttty={$bd4['id']}";
					}elseif($showtype3==5){
						$linkurl3="gg.php?pid={$pid}&ty={$ppid}&tty={$bd3['id']}&ttty={$bd4['id']}";
					}elseif($showtype3==6){
						$linkurl3="prod.php?pid={$pid}&ty={$ppid}&tty={$bd3['id']}&ttty={$bd4['id']}";
					}elseif($showtype3==7){
						$linkurl3="prod.php?pid={$pid}&ty={$ppid}&tty={$bd3['id']}&ttty={$bd4['id']}";
					}elseif($showtype3==8){
						$linkurl3="job.php?pid={$pid}&ty={$ppid}&tty={$bd3['id']}&ttty={$bd4['id']}";
					}elseif($showtype3==9){
						$linkurl3="file.php?pid={$pid}&ty={$ppid}&tty={$bd3['id']}&ttty={$bd4['id']}";
					}elseif($showtype3==10){
						$linkurl3="cy.php?pid={$pid}&ty={$ppid}&tty={$bd3['id']}&ttty={$bd4['id']}";
					}elseif($showtype3==11){
						$linkurl3="cy.php?pid={$pid}&ty={$ppid}&tty={$bd3['id']}&ttty={$bd4['id']}";
					}else{
						$linkurl3="news.php?pid={$pid}&ty={$ppid}&tty={$bd3['id']}&ttty={$bd4['id']}";
					}
				}
			?>
			<li class="pro_type2"><a href="<?=$linkurl3?>" target="rightFrame"><?=$bd4['catname']?></a></li>
			
			<? }?>
			<? }?>
			<? }?>	
			
 			</ul>    
		</dd>
    <? }?>    
	
		<? if($_SESSION['is_hidden']==true){?>
		<dd>
		<div class="title"><span><img src="images/leftico02.png" /></span>系统功能</div>
		<ul class="menuson">
 			<li><cite></cite><a href="manager.php" target="rightFrame">管理员信息</a><i></i></li>
			<li><cite></cite><a href="log.php" target="rightFrame">操作日志</a><i></i></li>
			<li><cite></cite><a href="class_cat.php" target="rightFrame">栏目管理</a><i></i></li>
			<li><cite></cite><a href="delete.php" target="rightFrame">信息删除</a><i></i></li>
			</ul>     
		</dd> 
		<? }?>
    </dl>
    
</body>
</html>
