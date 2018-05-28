<? 
require_once './include/common.incs.php';

if($System_Isstate=='1'){
	exit("{$System_Showinfo}");
}

$temp = strtoupper(urldecode(urldecode($_SERVER['REQUEST_URI'])));
if(strpos($temp, '<') !== false || strpos($temp, '"') !== false || strpos($temp, 'CONTENT-TRANSFER-ENCODING') !== false)
{
	exit('非法操作');
}

if((int)$_COOKIE['sys_guestid']){
	$guestid=(int)$_COOKIE['sys_guestid'];
	$cartnums=get_count("temp"," AND isstate=0 AND guestid={$guestid}");
}else{
	setcookie("sys_guestid", time(), time()+3600*24*365);  
	$cartnums=get_count("temp"," AND isstate=0 AND uid={$uid}");
}

$uid=(int)$_COOKIE['sys_uid'];
$PHP_URL=basename($PHP_SELF);

$id=(int)opturl("id");
$pid=(int)opturl("pid");
$ty=(int)opturl("ty");
$tty=(int)opturl("tty");
      
if($mypid) $pid=$mypid; else $pid=$pid;
if($myty) $ty=$myty; else $ty=$ty;
if($mytty) $tty=$mytty; else $tty=$tty;

if($id&&($PHP_URL=='view.php'||$PHP_URL=='show.php'||$PHP_URL=='mains_show.php')){

 	$bd=get_detail_view($id,$_GET['action']);

	if($bd){
		$db->sql_query("UPDATE `{$tablepre}news` SET hits=hits +1 WHERE isstate=1 AND id=".$id);
 			$pid=$bd['pid'];
			$ty=$bd['ty'];
			$tty=$bd['tty'];
			$ttty=$bd['ttty'];
			$img1=$bd['img1'];
			$img2=$bd['img2'];
			$img3=$bd['img3'];
			$img4=$bd['img4'];
			$img5=$bd['img5'];
			$img6=$bd['img6'];
 			$Sendtime=date('Y-m-d',$bd['sendtime']);
			$Title=$bd['title'];
			$Hits=$bd['hits'];
			$Keywords=$bd['seokeywords'];
			$disorder=$bd['disorder'];
			$file=$bd['file'];
			if($bd['seodescription']) $Info=$bd['seodescription'];else  $Info=cutstr_html($bd['content'],140);
			
 			$Introduce=new_stripslashes(new_html_entity_decode($bd['introduce']));
			$Sm=new_stripslashes(new_html_entity_decode($bd['seodescription']));
			$Content=new_stripslashes(new_html_entity_decode($bd['content']));
			$Content2=new_stripslashes(new_html_entity_decode($bd['content2']));
			$Content3=new_stripslashes(new_html_entity_decode($bd['content3']));

			//上一个
 			if($disorder) $ss1=" and disorder>{$disorder} ";else $ss1=" and id>{$id}";
 			$sql1="select id,title from `{$tablepre}news` where pid={$pid} and ty={$ty} and tty={$tty}{$ss1} and isstate=1 order by disorder asc,id asc limit 1";

			$result1=$db->sql_query($sql1);

			$rs1=$db->sql_fetchrow($result1);

			$total1=$db->sql_numrows($result1);

			if($total1>0){
				$url1="{$PHP_URL}?id={$rs1['id']}";
				$str1="<a href=\"{$url1}\">上一篇:{$rs1['title']}</a>";
			}else{
				$str1="<a>上一篇:没有了</a>";
			}

			//下一个
			if($disorder) $ss2=" and disorder<{$disorder} ";else $ss2=" and id<{$id}";
			
			$sql2="select id,title from `{$tablepre}news` where pid={$pid} and ty={$ty} and tty={$tty}{$ss2} and isstate=1 order by disorder desc,id desc limit 1";
			$result2=$db->sql_query($sql2);

			$rs2=$db->sql_fetchrow($result2);

			$total2=$db->sql_numrows($result2);

			if($total2>0){
				$url2="{$PHP_URL}?id={$rs2['id']}";
				$str2="<a href=\"{$url2}\">下一篇:{$rs2['title']}</a>";
			}else{
				$str2="<a>下一篇:没有了</a>";
			}
			
 	}else{
		ErrorHtml('Sorry,No Result.');
		exit();
	}

}elseif($id&&($PHP_URL=='temp.php')){

 	$bd=get_detail_view($id,$_GET['action'],"size");

	if($bd){
		$db->sql_query("UPDATE `{$tablepre}size` SET hits=hits +1 WHERE isstate=1 AND id=".$id);
 			$Title=$bd['xm'];
 	}else{
		ErrorHtml('Sorry,No Result.');
		exit();
	}

}

if($pid&&$id==0){
 	if($ty) $ty=$ty; else $ty=get_nextid($pid);
 	$sql="select * from `{$tablepre}news_cats` where pid={$pid} and id=".$ty." order by id desc";
	// echo $sql;
   	$result=$db->sql_query($sql);
	if($bd=$db->sql_fetchrow($result)){
		$Title=$bd['catname'];
		$Seotitle=$bd['seotitle'];
		if($Seotitle) $Title=$Seotitle; else $Title=$Title;
		$Keywords=$bd['seokeywords'];
		$Info=$bd['seodescription'];
 		$showtype=$bd['showtype'];
 	}
}elseif($id==0){
	$System_seotitle=$System_seotitle;
	$Keywords=$System_keywords;
	$Info=$System_info;
}

if($Title) $System_seotitle=$Title; else $System_seotitle=$System_seotitle;
if($Keywords) $System_keywords=$Keywords; else $System_keywords=$System_seokeywords;
if($Info) $System_info=$Info; else $System_info=$System_seodescription;
if($System_seotitle) $System_seotitle="{$System_seotitle}_";else $System_seotitle="";

//获取会员信息
$sql="select * from `{$tablepre}users` where id={$uid}";
//echo $sql;
$result=$db->sql_query($sql);
if($row=$db->sql_fetchrow($result)){

}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title><?=$System_seotitle?><?=$System_sitename?></title>
<meta name="keywords" content="<?=$System_keywords?>">
<meta name="description" content="<?=$System_info?>">

<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="format-detection" content="telephone=yes">
<link rel="shortcut icon" href="img/favicon.ico"/>

<link rel="stylesheet" href="css/base.css" />
<link rel="stylesheet" href="css/project.css" />
<link rel="stylesheet" href="css/animate.min.css" />
<link rel="stylesheet" href="css/owl.carousel.css" />
<link rel="stylesheet" href="css/css3.css" />
<script type="text/javascript" src="js/jQuery.js"></script>
<script type="text/javascript" src="js/owl.carousel.js"></script>
<script type="text/javascript" src="js/wow.min.js"></script>

<script type="text/javascript" src="js/area.js"></script>

<script src="js/checkform.js"></script>

</head>
<body>
	<script>
			//检测导航固定
			$(window).scroll(function() {
				if(jQuery(this).scrollTop() > 70) {
					$('.guding').css({
						"position": "fixed",
						"box-shadow":"0 15px 30px rgba(0, 0, 0, 0.2)"
					});
					
					$('.footer .top').show();

				} else {
					$('.guding').css({
						"position": "relative",
					});
					$('.footer .top').hide();
				};
			});
		</script>
		
		
<div class="guding">
	<div class="head cle w">
		<i class="mob_nav">
			<em class="em1"></em>
			<em class="em2"></em>
			<em class="em3"></em>
		</i>
		<a href="./index.php" class="fl logo">
			<img src="./uploadfile/upload/<?=$System_img1?>" />
		</a>

		<div class="fl nav" >
			<ul class="cle">
				<li class="fl"><a href="./index.php">首页</a>|</li>
				<li class="fl"><a href="list.php?pid=1">政务猎头</a>|</li>
				<li class="fl"><a href="list.php?pid=2">职业转型</a>|</li>
				<li class="fl"><a href="list.php?pid=3">人才引进</a>|</li>
				<li class="fl"><a href="list.php?pid=78">政府服务</a></li>
				
			</ul>
		</div>

		<div class="fr log cle">

			<div class="form cle">
				<form action="lists.php" method="get">
					<input type="hidden" name="pid" value="22" />
					<input type="text" class="fl text" placeholder="请输入你要搜索的内容！" name="q" value="<?=$q?>"/>
					<input type="submit" class="fr sub" value="提交" />
				</form>
			</div>

			<? if($uid){ ?>

			<a href="main.php" class="but fr">会员</a>

			<? }else{ ?>

			<a href="login.php" class="but fr">登录</a>

			<? } ?>

			<span class="sevr fr">
				<img src="img/img1.jpg"  />
			</span>

		</div>

		<script>
			$(function() {
				$(' .head .sevr').click(function() {
					$(this).parent('.log').toggleClass('log_on');
				})
			})
		</script>

	</div>
	</div>

	<script>
	$(function() {
		var i = 0;
		$('.mob_nav').click(function() {
			if(i == 0) {
				$(this).addClass('mob_nav_on');

				$('.head .nav').show();
				i++;
			} else if(i == 1) {
				$(this).removeClass('mob_nav_on');

				$('.head .nav').hide();
				i--;
			}

		})
	})
	</script>
	
	<script>
			if(!(/msie [6|7|8|9]/i.test(navigator.userAgent))) {
				new WOW().init();
			};
		</script>
	
	
	<? if($PHP_URL=="lists.php"||$PHP_URL=="view.php"||$PHP_URL=="links.php"){ ?>
		
		<? if($ty==17||$ty==18){ ?>
		<div class="child_banner">
			<img src="./uploadfile/upload/<?=get_zd_name("img1","news_cats"," and id={$ty} ")?>"  />
		</div>
		<? }else{ ?>
		<div class="child_banner">
			<img src="./uploadfile/upload/<?=get_zd_name("img1","news_cats"," and id={$pid} ")?>"  />
		</div>
		<? } ?>

	<? }elseif($PHP_URL=="main.php"||$PHP_URL=="updatepwd.php"||$PHP_URL=="vip.php"){ ?>

	<? }else{ ?>

	<!--<div class="banner">
		<img src="./uploadfile/upload/<?=get_zd_name("img1","news"," and id=142 ")?>" width="100%" />
	</div>-->
	<div class="banner" id="top">

			<script>
				$(function() {
					$('#owl-demo').owlCarousel({
						singleItem: true,
						autoPlay: 6000,
						pagination: true,
						 
					});
				});
			</script>
			<!-- Demo -->
			<div id="owl-demo" class="owl-carousel">
				<?=get_ad_img_list3(30,31)?>
			</div>
			<!-- Demo end -->
		</div>

	<? } ?>