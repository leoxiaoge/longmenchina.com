
<?
if($row['login']==1)
	$login="人才用户";
elseif($row['login']==2)
	$login="企业用户";
elseif($row['login']==3||$row['login']==0)
	$login="政府用户";
?>

<div class="user_nav fl">
	<div class="tit cle">
		<img src="img/img90.jpg" class="fl g1" />
		<div class="fl infosss">
			<p class="t">用户名：<?=$row['xm']?></p>
			<p class="t1"><span><?=$login?></span></p>
		</div>
	</div>
	<ul>
		<p><img src="img/img91.jpg" /> 账号设置</p>
		<li <? if($PHP_URL=="main.php"){ ?>class="on"<? } ?>><a href="main.php">个人资料</a></li>
		<li <? if($PHP_URL=="updatepwd.php"){ ?>class="on"<? } ?>><a href="updatepwd.php">修改密码</a></li>
		<li><a href="logout.php">退出账号</a></li>
		
	</ul>
	<ul>
		<p>会员购买</p>
		<li <? if($PHP_URL=="vip.php"){ ?>class="on"<? } ?>><a href="vip.php">会员信息</a></li>
	</ul>
</div>
