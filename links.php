<? 
include_once('header.php');
?>

<div class="weizhi">
	<div class="w html5_w90">
		<div class="box">
			<a href="./">首页</a> >
			<a >高层次人才引进</a>
		</div>
	</div>
</div>

<div class="m6">

	<div class="w html5_w90">

		<div class="c_t3">高层次人才引进</div>

		<div class="tab">
			<?=get_nav_links($pid,$ty)?>
		</div>

		<div class="livest">

			<ul class="cle"><?=get_nav_links2($pid,$ty)?></ul>
			
			<ul><?=get_nav_links2($pid,38)?></ul>
			
			<ul><?=get_nav_links2($pid,39)?></ul>
			
			<ul><?=get_nav_links2($pid,40)?></ul>
			
			<ul><?=get_nav_links2($pid,41)?></ul>
			
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

<? include_once('footer.php');?>
    
</body>
</html>