<? 
include_once('header.php');
?>

<? if($ty==9){ ?>

<div class="weizhi">
	<div class="w html5_w90">
		<div class="box">
			<a href="./index.php">首页</a> >
			<a ><?=get_catname($ty)?></a>
		</div>
	</div>
</div>

<div class="m5">
	<div class="w html5_w90 cle">
		<div class="c_t3"><?=get_catname($ty)?></div>
		<div class="m3">
			<?=get_linkurl_pic_list_fy($pid,$ty)?>
		</div>
	</div>
</div>

<? }elseif($ty==17||$ty==18){ ?>

<div class="weizhi">
	<div class="w html5_w90">
		<div class="box">
			<a href="./index.php">首页</a> >
			<a ><?=get_catname($ty)?></a>
		</div>
	</div>
</div>

<div class="m8">

	<div class="c_t3 w html5_w90"><?=get_catname($ty)?></div>

	<?=get_news_pic_list_fy($pid,$ty)?>

</div>

<? }elseif($ty==15){ ?>

<div class="weizhi">
	<div class="w html5_w90">
		<div class="box">
			<a href="./index.php">首页</a> >
			<a><?=get_catname($ty)?></a>
		</div>
	</div>
</div>

<div class="m6">
	<div class="c_t3 w html5_w90">
		<?=get_catname($ty)?>
	</div>
	<img src="./uploadfile/upload/<?=get_zd_name("img1","news"," and id=70 ")?>" />
</div>

<? }elseif($pid==19){ ?>

<div class="weizhi">
	<div class="w html5_w90">
		<div class="box">
			<a href="./index.php">首页</a> >
			<a><?=get_catname($ty)?></a>
		</div>
	</div>
</div>

<div class="m7">
	<div class="w html5_w90 cle">
		<img src="./uploadfile/upload/<?=$System_img1?>" class="g1" />
		<div class="c_t4">
			<p><?=get_catname($pid)?></p>
			<?=get_catname2($pid)?>
		</div>
		<div class="c">
			<?=get_single_content($pid,$ty)?>
		</div>
	</div>

	<div class="listvest">
		<div class="w html5_w90 cle">
			<?=get_link_img_list8(19,27)?>
		</div>
	</div>
</div>

<? }elseif($pid==20){ ?>

<div class="weizhi">
	<div class="w html5_w90">
		<div class="box">
			<a href="./index.php">首页</a> >
			<a><?=get_catname($ty)?></a>
		</div>
	</div>
</div>

<div class="m7">
	<div class="w html5_w90 cle">
		<img src="./uploadfile/upload/<?=$System_img1?>" class="g1" />
		<div class="c_t4">
			<p><?=get_catname($pid)?></p>
			<?=get_catname2($pid)?>
		</div>
		<div class="c">
			<?=get_single_content($pid,$ty)?>
		</div>
	</div>
</div>

<? }elseif($pid==21){ ?>

<div class="weizhi">
	<div class="w html5_w90">
		<div class="box">
			<a href="./index.php">首页</a> >
			<a><?=get_catname($ty)?></a>
		</div>
	</div>
</div>

<div class="m9">
	<div class="w html5_w90 cle">
		<div class="c_t3"><?=get_catname($pid)?></div>
		<div class="cont">
			<!-- <img src="./uploadfile/upload/<?=$System_img1?>" class="g1"  /> -->
			<ul class="cle">
				<li class="fl">
					<img src="img/img025.png"  />
					<p class="t">地址</p>
					<div class="c">深圳市宝安区西乡街道前进二路21号<br>流塘商务大厦A座18层A区03</div>
					<!-- <div class="c"><?=$System_contact?></div> -->
				</li>
				<li class="fl">
					<img src="img/call.png"  />
					<p class="t">联系电话</p>
					<div class="c">0755-88374382</div>
				</li>
				<li class="fl">
					<img src="img/img027.png"  />
					<p class="t">邮箱</p>
					<div class="c"><?=$System_email?></div>
				</li>
				<li class="fl">
					<img src="img/img026.png"  />
					<p class="t">微信公众号</p>
					<!-- <div class="c">
						<img src="./uploadfile/upload/<?=$System_img3?>"  />
					</div> -->
				</li>
			</ul>
		</div>
	</div>
</div>

<? }elseif($pid==22){ ?>

<div class="weizhi">
	<div class="w html5_w90">
		<div class="box">
			<a href="./index.php">首页</a> >
			<a><?=get_catname($ty)?></a>

		</div>
	</div>
</div>

<div class="m12">

	<div class="c_t3 w html5_w90"><?=get_catname($ty)?></div>

	<div class="cont w html5_w90 cle">
		<script>
		$(function() {
			$('#owl-demo').owlCarousel({
				singleItem: true,
				autoPlay: true,
				pagination: true,
				navigation: true,
				navigationText: ["<img src='img/img34.jpg' />", "<img src='img/img35.jpg' />"]
			});
		});
		</script>
		<!-- Demo -->
		<div id="owl-demo" class="owl-carousel fl">
			<div class="item">
				<img src="img/img31.jpg" />
			</div>
			<div class="item">
				<img src="img/img31.jpg" />
			</div>
	
			<div class="item">
				<img src="img/img31.jpg" />
			</div>
		</div>
		<!-- Demo end -->
	
		<?=get_pic_news_lists_isgood($pid,$ty)?>
	
	</div>

</div>

<div class="m12s">

	<?=get_news_pic_list_fy2($q,$pid,$ty)?>

</div>

<? } elseif($pid==79){ ?>
<!-- 政府服务  -->

<div class="weizhi">
	<div class="w html5_w90">
		<div class="box">
			<a href="/">首页</a> >
			<a >政府服务</a>
		</div>
	</div>
</div>

<div class="m6">

	<div class="w html5_w90">

		<div class="c_t3">政府服务</div>

		<div class="tab">
			<?=get_nav_links($pid,$ty)?>
		</div>

		<div class="livest">

			<ul class="cle"><?=get_nav_links2(78,79)?> </ul>
			<ul><?=get_nav_links2(78,80)?></ul>
			<ul><?=get_nav_links2(78,79)?></ul>
			<ul><?=get_nav_links2(78,79)?></ul>
			<ul><?=get_nav_links2(78,79)?></ul>
			<script>
			$(function(){
				$('.m6 .livest ul').eq(0).show();
				$('.m6 .tab span').click(function(){
					$(this).addClass('on').siblings().removeClass('on');
					$('.m6 .livest ul').eq($(this).index()).fadeIn().siblings().hide();
				})
			})
			</script>

		</div>

	</div>

</div>

<? } elseif($pid==80){ ?>
<!-- 企业服务  -->

<div class="weizhi">
	<div class="w html5_w90">
		<div class="box">
			<a href="/">首页</a> >
			<a >企业服务</a>
		</div>
	</div>
</div>

<div class="m6">

	<div class="w html5_w90">

		<div class="c_t3">企业服务</div>

		<div class="tab">
			<?=get_nav_links($pid,$ty)?>
		</div>

		<div class="livest">

			<ul class="cle"><?=get_nav_links2(78,80)?> </ul>
			<ul><?=get_nav_links2(78,80)?></ul>
			<ul><?=get_nav_links2(78,79)?></ul>
			<ul><?=get_nav_links2(78,79)?></ul>
			<ul><?=get_nav_links2(78,79)?></ul>
			
			<script>
			$(function(){
				$('.m6 .livest ul').eq(0).show();
				$('.m6 .tab span').click(function(){
					$(this).addClass('on').siblings().removeClass('on');
					$('.m6 .livest ul').eq($(this).index()).fadeIn().siblings().hide();
				})
			})
			</script>

		</div>

	</div>

</div>

<? } ?>

<? include_once('footer.php');?>
    
</body>
</html>