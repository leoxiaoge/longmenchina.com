<div class="m13 wow fadeInDown animated" data-wow-duration="1.5s" data-wow-delay="1s">
	<div class="c_t">
		<i></i>
		<span><?=get_catname(4)?></span>
		<p><?=get_catname2(4)?></p>
	</div>
	<div class="list w html5_w90">
		
			<div class="mob_show1 mob_show2">
			<script>
				$(function() {
					$('#owl-demo4').owlCarousel({
						singleItem: true,
						autoPlay: true,
						pagination: true,
						 
					});
				});
			</script>
			<!-- Demo -->
			<div id="owl-demo4" class="owl-carousel"> 
			 <div class="item" >
			 	
			   <li id="cont1"></li>
			 	
				</div>

				<div class="item" >
					
				 <li id="cont2"></li>
				 
				</div>

				<div class="item" >
					
					 <li id="cont3"></li>
				 
				</div>

			</div>
			<!-- Demo end -->
		</div>
		
		<ul class="cle">
			<?=get_link_img_list(1,4)?>
		</ul>


<script>
			$(function(){
				var cont1 = $('.m13 .list ul li').eq(0).html();
				$('#cont1').html(cont1);
				
				var cont2 = $('.m13 .list ul li').eq(1).html();
				$('#cont2').html(cont2);
				
				var cont3 = $('.m13 .list ul li').eq(2).html();
				$('#cont3').html(cont3);
				 
			})
		</script>
	</div>
</div>

<!-- <div class="m14 cle wow fadeInDown animated" data-wow-duration="1.5s" data-wow-delay="1.2s">
	<div class="w cle html5_w90">
		<div class="c_t c_t1">
			<i></i>
			<span><?=get_catname(5)?></span>
			<p><?=get_catname2(5)?></p>
		</div>
	</div>
	<div class="c w cle html5_w90">
		<div class="box">
			<?=get_link_img_list2(1,5)?>
			<img src="img/p10.png" />
		</div>
	</div>
</div> -->

<div class="m14 cle wow fadeInDown animated" data-wow-duration="1.5s" data-wow-delay="1.2s">
	<div class="w cle html5_w90">
		<div class="c_t c_t1">
			<i></i>
			<span><?=get_catname(5)?></span>
			<p><?=get_catname2(5)?></p>
		</div>
	</div>
	<div class="c w cle html5_w90">
		<div class="box">
			<?=get_link_img_list2(1,5)?>
		</div>
		<div class="c">
		<div class="socialAnimationWrapper">
	<ul class="socialAnimation">
		<li class="person"><img src="img/person.png" alt=""/></li>
		<li class="facebook">
			<div class="containerImg"></div>
			<div class="connector">
				<span class="one"></span>
				<span class="two"></span>
			</div>
		</li>
		<li class="googlePlus">
			<div class="containerImg"></div>
			<div class="connector">
				<span class="one"></span>
				<span class="two"></span>
			</div>
		</li>
		<li class="twitter">
			<div class="containerImg"></div>
			<div class="connector">
				<span class="one"></span>
				<span class="two"></span>
			</div>
		</li>
		<li class="linkedin">
			<div class="containerImg"></div>
			<div class="connector">
				<span class="one"></span>
				<span class="two"></span>
			</div>
		</li>
		<li class="yt">
			<div class="containerImg"></div>
			<div class="connector">
				<span class="one"></span>
				<span class="two"></span>
			</div>
		</li>
	</ul>
    </div>
    </div>
	</div>
	
</div>


<div class="m15 wow fadeInDown animated" data-wow-duration="1.5s" data-wow-delay="1.4s">
	<div class="w cle html5_w90">
		<p class="t">
			<?=get_catname(6)?>
		</p>
	</div>

	<div class="c html5_w90 cle">
		<?=get_news_time_list(1,6,11)?>
		<a href="#">
			more》
		</a>
	</div>
</div>

<div class="m16 wow fadeInDown animated" data-wow-duration="1.5s" data-wow-delay="1.6s">
	<div class="c_t c_t1">
		<i></i>
		<span><?=get_catname(7)?></span>
		<p><?=get_catname2(7)?></p>
	</div>
	<img src="img/img39.jpg" class="g1" />
	<div class="c w html5_w90">
		<ul class="cle">
			<?=get_link_img_list3(1,7,6)?>
		</ul>
	</div>
</div>

<div class="m16s wow fadeInDown animated" data-wow-duration="1.5s" data-wow-delay="1.6s">
	<div class="w html5_w90 cle">
		<img src="img/p11.png" class="g1" />
		<?=get_link_img_list4(1,8,23)?>
		<li class="fl">
			<a href="#"><img src="img/img64.jpg" /></a>
		</li>
	</div>
</div>

<div class="m16ss wow fadeInDown animated" data-wow-duration="1.5s" data-wow-delay="1.6s">
	<div class="c_t c_t1">
		<i></i>
		<span><?=get_catname(9)?></span>
		<p><?=get_catname2(9)?></p>
	</div>
	<div class="m3">
		<ul class="cle w html5_w90">
			<?=get_link_img_list5(1,9,8)?>
		</ul>
		<div class="hr">
			<a href="lists.php?pid=1&ty=9">了解更多</a>
		</div>
	</div>
</div>
