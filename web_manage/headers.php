<?
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理页面</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="js/jquery.js"></script>
<script type="text/javascript">
$(function(){	
	//顶部导航切换
	$(".nav li a").click(function(){
		$(".nav li a.selected").removeClass("selected")
		$(this).addClass("selected");
	})	
})	
</script>


</head>

<body style="background:url(images/topbg.gif) repeat-x;">

    <div class="topleft">
    <a href="mains.php" target="rightFrame"><img src="images/logo.png" border="0" title="系统首页" /></a>    </div>
        
    <ul class="nav">
	<? if ($_SESSION['Admin_BigMyMenu']=="super"){?>
    <li><a href="mains.php" target="rightFrame" class="selected"><img src="images/icon01.png" title="工作台" /><h2>工作台</h2></a></li>
    <li><a href="news_cat.php" target="rightFrame"><img src="images/icon02.png" title="栏目管理" /><h2>栏目管理</h2></a></li>
    <li><a href="ads.php"  target="rightFrame"><img src="images/icon03.png" title="模块设计" /><h2>广告管理</h2></a></li>
    <li><a href="seo.php"  target="rightFrame"><img src="images/icon09.png" title="修改密码" /><h2>SEO设置</h2></a></li>
    <li><a href="manager.php" target="rightFrame"><img src="images/icon05.png" title="用户管理" /><h2>用户管理</h2></a></li>
    <li><a href="db_expore.php" target="rightFrame"><img src="images/icon07.png" title="用户管理" /><h2>数据备份</h2></a></li>
    <li><a href="log.php" target="rightFrame"><img src="images/icon07.png" title="用户管理" /><h2>操作日志</h2></a></li>
    <li><a href="config.php"  target="rightFrame"><img src="images/icon06.png" title="系统设置" /><h2>系统设置</h2></a></li>
	<? }else{?>
    <li><a href="mains.php" target="rightFrame" class="selected"><img src="images/icon01.png" title="工作台" /><h2>工作台</h2></a></li>
    <li><a href="person.php"  target="rightFrame"><img src="images/icon09.png" title="修改密码" /><h2>修改密码</h2></a></li>
	<? } ?>    
	</ul>
            
    <div class="topright">    
    <ul>
    <li><a href="person.php"  target="rightFrame">修改密码</a></li>
    <li><a href="../" target="_blank">网站首页</a></li>
    <li><a href="logout.php?action=logout" target="_top" onClick="return confirm('确定退出系统吗？\n\n退出后自动关闭窗口！');">退出</a></li>
    </ul>
     
    <div class="user">
    <span>→ 管理员：  <?=$_SESSION['Admin_UserName']?>  管理员级别：<?  if($_SESSION['Admin_BigMyMenu']=="super") echo "超级管理员";else echo "普通管理员";?></span>
    </div>    
    
    </div>

</body>
</html>
