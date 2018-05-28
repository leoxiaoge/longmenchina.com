<? 
include_once('header.php');
?>

<div class="user_warp">
	<div class="w cle html5_w90">
		<div class="weizhiss">
			<a href="/list.php?pid=2">职业转型 </a>>
			<a href="#">年度会员</a>
		</div>

		<div class="zhifu_info" style="margin-top: 20px;">
			<div class="vip">
			<div class="lists i2 fl">
				<div class="t">
					<?=get_catname(12)?> 
						<!--<?=get_catname2(12)?>-->
				</div>
				<?=get_link_img_list6(2,10,12,11)?>
			</div>
		</div>
		<!-- <div class="m17">
		<div class="but html5_w90 cle">
			<a href="erweima.php" class="fl" >关注公众号</a>
			
			<? if($uid){ ?>
			<a href="payprice.php" class="fr">加入会员</a>
			<? }else{ ?>
			<a href="login.php" class="fr">加入会员</a>
			<? } ?>
		</div>
		</div> -->
		</div>
	</div>
</div>

<? include_once('footer.php');?>
    
</body>
</html>