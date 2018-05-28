<? include_once('header.php');?>

<div class="m1 ">
	<div class="w box html5_w90">
		<div class="c_t">
			<i></i>
			<span>服务项目</span>
			<p>Service Items</p>
		</div>
		
		
		
		
		
		
		<ul class="cle">
			<div class="mob_show1">
			<script>
				$(function() {
					$('#owl-demo3').owlCarousel({
						singleItem: true,
						autoPlay: true,
						pagination: true,
						 
					});
				});
			</script>
			<!-- Demo -->
			<div id="owl-demo3" class="owl-carousel"> 
			 <div class="item" >
			 	
			 	<li class="wow fadeInDown animated" data-wow-duration="1.5s" data-wow-delay="1s">
					<div class="c">
						<img src="img/pngs1.png"  style="display: block; margin: 0px auto;" />
						<p class="t1">政务猎头<i></i></p>
						<a href="list.php?pid=1">了解更多</a>
					</div>
				</li>
			 	
				</div>

				<div class="item" >
					
					<li class="wow fadeInDown animated" data-wow-duration="1.5s" data-wow-delay="1.2s">
						<div class="c">
							<img src="img/pngs2.png"  style="display: block; margin: 0px auto;" />
							<p class="t1">职业转型<i></i></p>
							<a href="list.php?pid=2">了解更多</a>
						</div>
					</li>
				 
				</div>

				<div class="item" >
					
					<li class="wow fadeInDown animated" data-wow-duration="1.5s" data-wow-delay="1.4s">
						<div class="c">
							<img src="img/pngs3.png"  style="display: block; margin: 0px auto;" />
							<p class="t1">人才引进<i></i></p>
							<a href="list.php?pid=3">了解更多</a>
						</div>
					</li>
				 
				</div>

			</div>
			<!-- Demo end -->
		</div>
			
			<li class="wow fadeInDown animated" data-wow-duration="1.5s" data-wow-delay="1s">
				<div class="c">
					<img src="img/pngs1.png"  style="display: block; margin: 0px auto;" />
					<p class="t1">政务猎头<i></i></p>
					<a href="list.php?pid=1">了解更多</a>
				</div>
			</li>
			<li class="wow fadeInDown animated" data-wow-duration="1.5s" data-wow-delay="1.2s">
				<div class="c">
					<img src="img/pngs2.png"  style="display: block; margin: 0px auto;" />
					<p class="t1">职业转型<i></i></p>
					<a href="list.php?pid=2">了解更多</a>
				</div>
			</li>
			<li class="wow fadeInDown animated" data-wow-duration="1.5s" data-wow-delay="1.4s">
				<div class="c">
					<img src="img/pngs3.png"  style="display: block; margin: 0px auto;" />
					<p class="t1">人才引进<i></i></p>
					<a href="list.php?pid=3">了解更多</a>
				</div>
			</li>
		</ul>
	</div>
</div>

<div class="m2 wow fadeInUp animated" data-wow-duration="1.5s" data-wow-delay="1s">
	<div class="c_t c_t1">
		<i></i>
		<span>服务分布</span>
		<p>Service distribution</p>
	</div>
	
	
	
	<div class="mob_show1 mob_show2">
			<script>
				$(function() {
					$('#owl-demo5').owlCarousel({
						singleItem: true,
						autoPlay: true,
						pagination: true,
						 
					});
				});
			</script>
			<!-- Demo -->
			<div id="owl-demo5" class="owl-carousel"> 
			 <div class="item" >
			 	<ul>
			   <li id="cont1"></li>
			 	</ul>
				</div>

				<div class="item" >
					<ul>
				 <li id="cont2"></li>
				 </ul>
				</div>

				<div class="item" >
					<ul>
					 <li id="cont3"></li>
				 </ul>
				</div>

			</div>
			<!-- Demo end -->
		</div>
	
	
	<ul class="cle w html5_w90 uls">
		<?=get_ad_img_list(30,32)?>
	</ul>
	
	<script>
			$(function(){
				var cont1 = $('.m2 .uls li').eq(0).html();
				 
				$('#cont1').html(cont1);
				
				var cont2 = $('.m2 .uls li').eq(1).html();
				$('#cont2').html(cont2);
				
				var cont3 = $('.m2 .uls li').eq(2).html();
				$('#cont3').html(cont3);
				 
			})
		</script>
	
</div>

<div class="m3 wow fadeInDown animated" data-wow-duration="1.5s" data-wow-delay="1s">
	<div class="c_t c_t1">
		<i></i>
		<span>服务客户</span>
		<p>Service customer</p>
	</div>
	<ul class="cle w html5_w90">
		<?=get_link_img_list5(1,9,12)?>
	</ul>
	<div class="hr">
		<a href="lists.php?pid=1&ty=9">
			了解更多
		</a>
	</div>
</div>

<div class="m4">
	<div class="c_t c_t1">
		<i></i>
		<span>新闻</span>
		<p>News</p>
	</div>
	<div class="c wow fadeInLeft animated" data-wow-duration="1.5s" data-wow-delay="1s">
		<ul class="w html5_w90">
			<?=get_news_pic_index(22,26,2)?>
		</ul>
		<div class="hr_1">
			<a href="lists.php?pid=22">
				<img src="img/p3.png" />
			</a>
		</div>
	</div>
</div>

<? include_once('footer.php')?>

</body>
</html>