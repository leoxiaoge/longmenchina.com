<? include_once('header.php');?>

<div class="m11 ">

	<div class="main_box w html5_w90">

		<div class="weizhi w html5_w90">
			<div class="w html5_w90">
				<div class="box">
					<a href="./index.php">首页</a> > <a><?=get_catname($ty)?></a>
				</div>
			</div>
		</div>

		<div class="w html5_w90 cle">
			<div class="c_t3 ">
				<?=get_catname($ty)?>
			</div>
			<div class="cont">
				<p class="tit"><?=$Title?></p>
				<?=$Content?>
			</div>
		</div>

	</div>

</div>

<? include_once('footer.php');?>
    
</body>
</html>