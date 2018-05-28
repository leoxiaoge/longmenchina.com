<? 
$Title="购买会员";
include_once('./include/checkuser.inc.php'); 
include_once('header.php');

$path="./".$upload_picpath."/";

if ($action=="del"){
	if (empty($id)){
		JsError("参数提交错误");
	}
	$sql="delete from `{$tablepre}size` where id=".$id;
	//exit($sql);
	if($db->sql_query($sql)){
		JsSucce("删除成功！","student.php");
	}else{
		JsError("操作失败！");
	}
	exit();
}


?>
<div class="user_warp">

	<div class="w cle html5_w90">

		<div class="weizhiss">
			<a href="./index.php">首页</a>><a>会员详情</a>
		</div>

		<div class="cle user_info_warp">
			
			<? include_once('ulefts.php');?>

			<div class="m20 fr cle common_ri">

				<div class="box">
                
					<p class="t">购买会员</p>

					<div class="mdb_tab">
						<!-- <div class="lists i1 fl">
							<div class="t">
								<?=get_catname(11)?><br> <?=get_catname2(11)?>
							</div>
							<div class="vv">
										            <? if($uid){ ?>
									                <a href="vip.php" class="fr">免费试用</a>
									                <? }else{ ?>
									                <a href="login.php" class="fr">免费试用</a>
									                <? } ?>
									                </div>
							<?=get_link_img_list6(2,10,11,11)?>
						</div> -->
						<a href="/yearvip.php">
						<div class="lists i2 fl">
			
							<div class="t">
								<?=get_catname(12)?><br> <?=get_catname2(12)?>
							</div>
							<div class="pp">
				            <? if($uid){ ?>
			                <a href="erweima.php" class="fr" id="gzh">欲购联系>></a>
			                <? }else{ ?>
			                <a href="login.php" class="fr">欲购联系>></a>
			                <? } ?>
			                </div>
							<!-- <?=get_link_img_list6(2,10,12,11)?> -->
						</div>
						</a>
						<a href="/onevip.php">
						<div class="lists i3 fl">
							<div class="t">
								<?=get_catname(13)?><br> <?=get_catname2(13)?>
							</div>
							<div class="mm">
				            <? if($uid){ ?>
			                <a href="erweima.php" class="fr" id="g">欲购联系>></a>
			                <? }else{ ?>
			                <a href="login.php" class="fr">欲购联系>></a>
			                <? } ?>
			            </div>
							<!-- <?=get_link_img_list6(2,10,13,11)?> -->
						</div>
						</a>
					</div>

					<!-- <div class="m17">
						<div class="but html5_w90 cle">
							<a href="erweima.php" class="fl">关注公众号</a>
							<a href="payprice.php" class="fr">加入会员</a>
						</div>
					</div>
					 -->
				</div>

			</div>

		</div>

	</div>

</div>

<? include_once('footer.php');?>
    
</body>
</html>