<? 
$Title="加入会员";
include_once('header.php');
?>

<div class="weizhi">
	<div class="w html5_w90">
		<div class="box">
			<a href="./index.php">首页</a> > <a>加入会员</a>
		</div>
	</div>
</div>

<div class="m18">
	<div class="w html5_w90 cle">
		<div class="c_t3">
			加入会员
		</div>
	</div>

	<div class="cle box w html5_w90 box">
		<div class="fl but cle">
			<div class="fl bbb on">
				<p class="t">
					年度会员
				</p>
				<p class="t1">
					<span>6666</span>元
				</p>
				<p class="t2">
					专业服务
				</p>
			</div>

			<div class="fr bbb">
				<p class="t">
					VIP会员
				</p>
				<p class="t1">
					<span>首年年薪</span>
				</p>
				<p class="t2">
					8.3<span>%</span>
				</p>
			</div>
		</div>
		<script>
			$(function() {
				$('.m18 .box .but .bbb').click(function() {
					$(this).addClass('on').siblings().removeClass('on');
				})
			})
		</script>
		<div class="fr info cle">
			<div class="cle moye">
				<font class="fl">选择支付方式</font>
				<!-- <div class="fr">
					应付金额：<span>99</span>元
					<a href="#">《会员服务协议》</a>
				</div> -->
			</div>
			
			<div class="cle ">
				<div class="wx_paly fl">
					<img src="img/img74.jpg"  />
					<div class="log">
						<img src="img/img76.jpg"  />
					</div>
				</div>
				
				
				<!--<div class="zfb_paly fr">
					<img src="img/img75.jpg"  />
					<div class="log">
						<img src="img/img76.jpg"  />
					</div>
				</div>-->
			</div>

		</div>
	</div>

</div>

<? include_once('footer.php');?>
    
</body>
</html>