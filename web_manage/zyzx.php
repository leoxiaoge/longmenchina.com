<div class="m17 wow fadeInDown animated" data-wow-duration="1.5s" data-wow-delay="1s">
	<div class="c_t c_t1">
		<i></i>
		<span><?=get_catname(10)?></span>
		<p>
			<?=get_catname2(10)?>
		</p>
	</div>
	<div class="w cle html5_w90">
		<div class="mdb_tab">
			<div class="lists i1 fl">
				<div class="t">
					<?=get_catname(11)?><br>
						<?=get_catname2(11)?>
				</div>
				<?=get_link_img_list6(2,10,11,11)?>
			</div>
			<div class="lists i2 fl">

				<div class="t">
					<?=get_catname(12)?><br>
						<?=get_catname2(12)?>
				</div>
				<?=get_link_img_list6(2,10,12,11)?>
			</div>
			<div class="lists i3 fl">
				<div class="t">
					<?=get_catname(13)?><br>
						<?=get_catname2(13)?>
				</div>
				<?=get_link_img_list6(2,10,13,11)?>
			</div>
		</div>

		<div class="but html5_w90 cle">
			<a href="javascript:;" class="fl" id="gzh">
				关注公众号》
			</a>
			<script>
				$(function() {
					var str = '';
					$('#gzh').click(function() {
						str += '<div class="ewm_box cle"><p class="cle">请扫描关注<span class="fr close" id="close" onclick="bigs()">×</span></p><div class="c"><img src="img/img11.jpg" ></div></div>';
						$('body').append(str);
					});

				})

				function bigs() {
					$(".ewm_box").hide();
				}
			</script>

			<? if($uid){ ?>
				<a href="payprice.php" class="fr">加入会员》</a>
				<? }else{ ?>
					<a href="login.php" class="fr">加入会员》</a>
					<? } ?>
		</div>

	</div>

</div>

<!--<div class="m17s">
	<div class="c_t c_t1  html5_w90 ">
		<i></i>
		<span><?=get_catname(14)?></span>
		<p><?=get_catname2(14)?></p>
	</div>
	<div class="listvest">
		<div class="wbox html5_w90 cle">
			<?=get_link_img_list7(2,14,4)?>
		</div>
	</div>

	<div class="info">
		<span class="text1">30+</span>顾问及搜寻员工<span class="text2">20+</span>支持团队员工
	</div>

</div>-->

<div class="m17s wow fadeInDown animated" data-wow-duration="1.5s" data-wow-delay="1s">
	<div class="c_t c_t1  html5_w90 ">
		<i></i>
		<span><?=get_catname(14)?></span>
		<p><?=get_catname2(14)?></p>
	</div>

	<div class="listvest" id="siles">
		<div class="wbox html5_w90 cle bd">
			<script>
				$(function() {
					$('#owl-demos').owlCarousel({
						items: 4,
						autoPlay: 2000

					});
				});
			</script>
			<!-- Demo -->
			<div id="owl-demos" class="owl-carousel">
				
				<?=get_link_img_list7(2,14,50)?>
			
			</div>

		</div>

	</div>

	<div class="info">
		<span class="text1">30+</span>顾问及搜寻员工<span class="text2">20+</span>支持团队员工
	</div>

</div>

<div class="m17ss wow fadeInDown animated" data-wow-duration="1.5s" data-wow-delay="1s">
	<div class="c_t c_t1  html5_w90 ">
		<i></i>
		<span><?=get_catname(15)?></span>
		<p>
			<?=get_catname2(15)?>
		</p>
	</div>

	<a href="<?=get_zd_name(" linkurl ","news "," and id=70 ")?>" target="_blank"><img src="./uploadfile/upload/<?=get_zd_name("img1","news"," and id=70 ")?>" width="100%" /></a>
</div>