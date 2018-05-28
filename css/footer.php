
<div class="footer">
	<a class="top" href="#top">
		置顶
	</a>
	<div class="w html5_w90 cle">
	<div class="cle wws html5_w90 fl">
		<!--<div class="ewm">
			<img src="./uploadfile/upload/<?=$System_img2?>" width=" 75px" height="70px"/>
		</div>-->
		 
		<div class="list1">
			<a href="lists.php?pid=19" style="margin-left: 0px;">关于我们</a> |
			<a href="lists.php?pid=20">加入我们</a> |
			<a href="lists.php?pid=21">联系我们</a> |
			<a href="lists.php?pid=22">新闻报道</a>
		</div>

		<div class="info">
			<?=$System_copyright?>
		</div>

	</div>
	
	<div class="fr weixin">
	 <img src="img/werweima.jpg"  width="100" /><p style=" font-size: 0.8rem; color: #fff;">
			龙门网微信:
		</p>
		
	</div>
</div>
</div>
<div class="copy">
	粤ICP备16015268号-2
</div>

<script>
			$(function() {
				//锚点跳转滑动效果  
				$('a[href*=#],area[href*=#]').click(function() {
					console.log(this.pathname)
					if(location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
						var $target = $(this.hash);
						$target = $target.length && $target || $('[name=' + this.hash.slice(1) + ']');
						if($target.length) {
							var targetOffset = $target.offset().top;
							$('html,body').animate({
									scrollTop: targetOffset
								},
								1000);
							return false;
						}
					}
				});
			})
		</script>
