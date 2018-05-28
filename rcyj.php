<script type="text/javascript" src="js/lib/raphael-min.js"></script>
<script type="text/javascript" src="js/res/chinaMapConfig.js"></script>
<script type="text/javascript" src="js/res/worldMapConfig.js"></script>
<script type="text/javascript" src="js/map.js"></script>

<style type="text/css">
	.stateTip,#StateTip {
		position: absolute;
		padding: 8px;
		background: #fff;
		border: 2px solid #e32f02;
		-moz-border-radius: 4px;
		-webkit-border-radius: 4px;
		border-radius: 4px;
		font-size: 12px;
		font-family: Tahoma;
		color: red;
	}
	.mapInfo i {
		display: inline-block;
		width: 15px;
		height: 15px;
		margin-top: 5px;
		line-height: 15px;
		font-style: normal;
		background: #aeaeae;
		color: #fff;
		font-size: 11px;
		font-family: Tahoma;
		-webkit-border-radius: 15px;
		border-radius: 15px;
		text-align: center
	}
	.mapInfo i.active {
		background: #E27F21;
	}
	.mapInfo span {
		padding: 0 5px 0 3px;
	}
	.mapInfo b {
		font-weight: normal;
		color: #2770B5
	}
	#ChinaMap6 path{
		position: relative;
	}
</style>
<script type="text/javascript">
$(function() {
	$('#ChinaMap6').SVGMap({
		mapWidth: 814,
		mapHeight: 683,
		clickCallback: function(stateData, obj) {
			if(obj.name == '四川'){
				$('#conts').html('幺妹');
			}
			else if(obj.name == '北京'){
				$('#conts').html('首都');
			}
			else{
				$('#conts').html(obj.name);
			}
			
		}
	});
});
</script>

<div class="m10 wow fadeInDown animated" data-wow-duration="1.5s" data-wow-delay="1s">

	<div class="c_t">
		<i></i>
		<span><?=get_catname(16)?></span>
		<p><?=get_catname2(16)?></p>
	</div>

	<style>
		.m10 .box i{
			position: absolute;
			font-size: 0.7rem;
			z-index: 999;
			padding-left: 10px;
			color: #fff;
		}
		.m10 .box i::before{
			content: '';
			position: absolute;
			width: 5px;
			height: 5px;
			border-radius: 50%;
			left: 0px;
			background: #f73e3e;
			top: 7px;
		}
		.m10 .box .i1{
			    left: 150px;
			    top: 37%;
					}
		.m10 .box .i2{
	left: 300px;
	top: 40%;
		}
		.m10 .box .i3{
	left: 300px;
	top: 50%;
		}
		.m10 .box .i4{
	   left: 425px;
top: 37%;
		}
		.m10 .box .i5{
	   left: 436px;
top: 47%;
		}
		.m10 .box .i6{
	   left: 65%;
top: 47%;

		}
		.m10 .box .i7{
		  left: 60%;
top: 55%;
		}
		.m10 .box .i8{
		  left: 68%;
top: 54%;
		}
		.m10 .box .i9{
		 left: 79%;
top: 54%;
		}
		.m10 .box .i10{
	   left: 86%;
top: 15%;
		}
		.m10 .box .i11{
		  left: 82%;
top: 31%;
		}
		.m10 .box .i12{
		 left: 75%;
top: 47%;
		}
		.m10 .box .i13{
	   left: 73%;
top: 72%;
		}
		.m10 .box .i14{
		  left: 69%;
top: 62%;
		}
		.m10 .box .i15{
		   left: 77%;
top: 62%;
		}
		.m10 .box .i16{
			left: 82%;top: 66%;
		}
		.m10 .box .i17{
		 left: 79%;
top: 75%;
		}
		.m10 .box .i18{
			 left: 66%;
top: 71%;

		}
		.m10 .box .i19{
		  left: 48%;
top: 65%;
		}
		.m10 .box .i20{
			  left: 75%;
top: 40%;
		}
		.m10 .box .i21{
		   left: 87%;
top: 25%;
		}
		.m10 .box .i22{
		   left: 17%;
top: 62%;
		}
		.m10 .box .i23{
		   left: 45%;
top: 83%;
		}
		.m10 .box .i24{
left: 71%;
top: 42%;
		}
		.m10 .box .i25{
left: 73%;
top: 37%;
		}
		.m10 .box .i26{
left: 62%;
top: 80%;
		}
		.m10 .box .i27{
left: 73%;
top: 81%;
		}
		.m10 .box .i28{
left: 57%;
top: 75%;
		}
		.m10 .box .i29{
left: 86%;
top: 59%;
		}
		.m10 .box .i29 span{
			color: #666;
		}
		.m10 .box .i20 span{
			color: #666;
		}
		.m10 .box .i30 {
				left: 87%;
top: 79%;
		}
		.m10 .box .i30 span{
			color: #666;
		}
		.m10 .box .i31 {
			left: 65%;
top: 94%;
		}
		.m10 .box .i31 span{
			color: #666;
		}
	</style>
	
	<div class="c">
		<div class="box">
			<i class="i1"><span>新疆</span></i>
			<i class="i2"><span>甘肃</span></i>
			<i class="i3"><span>青海</span></i>
			<i class="i4"><span>内蒙古</span></i>
			<i class="i5"><span>宁夏</span></i>
			<i class="i6"><span>山西</span></i>
			<i class="i7"><span>陕西</span></i>
			<i class="i8"><span>河南</span></i>
			<i class="i9"><span>江苏</span></i>
			<i class="i10"><span>黑龙江</span></i>
			<i class="i11"><span>辽宁</span></i>
			<i class="i12"><span>山东</span></i>
			<i class="i13"><span>江西</span></i>
			<i class="i14"><span>湖北</span></i>
			<i class="i15"><span>安徽</span></i>
			<i class="i16"><span>浙江</span></i>
			<i class="i17"><span>福建</span></i>
			<i class="i18"><span>湖南</span></i>
			<i class="i19"><span>四川</span></i>
			<i class="i20"><span>天津</span></i>
			<i class="i21"><span>吉林</span></i>
			<i class="i22"><span>西藏</span></i>
			<i class="i23"><span>云南</span></i>
			<i class="i24"><span>河北</span></i>
			<i class="i25"><span>北京</span></i>
			<i class="i26"><span>广西</span></i>
			<i class="i27"><span>广东</span></i>
			<i class="i28"><span>贵州</span></i>
			<i class="i29"><span>上海</span></i>
			<i class="i30"><span>台湾</span></i>
			<i class="i31"><span>海南</span></i>
			<div id="ClickCallback"></div>
			<div id="ChinaMap6">
			</div>
			<link rel="stylesheet" type="text/css" href="css/SyntaxHighlighter.css">
			<script type="text/javascript" src="js/lib/SyntaxHighlighter.js"></script>
			<!--<img src="img/p5.png" />-->
			<div class="map" style="display: none;">
				<i class="i1"></i>
				<i class="i2"></i>
				<i class="i3"></i>
				<i class="i4"></i>
				<i class="i5"></i>
				<i class="i6"></i>
				<i class="i7"></i>
				<i class="i8"></i>
				<i class="i9"></i>
				<i class="i10"></i>
				<i class="i11"></i>
				<i class="i12"></i>
				<i class="i13"></i>
				<i class="i14"></i>
				<i class="i15"></i>
				<i class="i16"></i>
				<i class="i17"></i>
				<i class="i18"></i>
				<i class="i19"></i>
				<i class="i20"></i>
				<i class="i21"></i>
				<i class="i22"></i>
				<i class="i23"></i>
				<i class="i24"></i>
				<i class="i25"></i>
				<i class="i26"></i>
				<i class="i27"></i>
				<i class="i28"></i>
				<i class="i29"></i>
				<i class="i30"></i>
				<i class="i31"></i>
				<i class="i32"></i>
				<i class="i33"></i>
			</div>
			<script>
			$(function() {
				var str = '';
				$('.m10 .map i').each(function() {
					//判断第几个 就给第几个加上代码;
					<?
					$sql= "SELECT * FROM `{$tablepre}news` WHERE isstate=1 and pid=3 and ty=16 order by disorder desc,id asc limit 0,50";
					$result=$db->sql_query($sql);
					$i=0;
					while($bd=$db->sql_fetchrow($result)){
						$i++;
					?>
					if($(this).index() == <?=$i?>) {
						str += '<div class="dome_show"><div class="text"><?=cutstr($bd['seodescription'],30)?></div></div>';
						$(this).append(str);
					}
					<? } ?>
				})
				$(' .m10 .map i').hover(function() {
					$(this).addClass('on').siblings().removeClass('on');
				})
			})
			</script>

			<!--<div class="tip" id="conts">
				提示：点击省份或直辖市查看地方引才计划
			</div>-->

			

		</div>
		<div class="w cle html5_w90 moble_info">
		<i id="clickss"></i>
		
		<?
		$sql2= "SELECT * FROM `{$tablepre}news` WHERE isstate=1 and pid=3 and ty=16 order by disorder desc,id asc limit 0,50";
		$result2=$db->sql_query($sql2);
		$i=0;
		while($bd2=$db->sql_fetchrow($result2)){
			$i++;
		?>	
		<div class="list">
			<?=nl2br($bd2['seodescription'])?>
		</div>
		<? } ?>
	</div>
		<div class="hr" style="margin-bottom: 0px;">
			<a href="links.php?pid=36&ty=37">了解更多</a>
		</div>

	</div>
 
	
	
</div>

<script>
	$(function(){
		$('#clickss').click(function(){
			$('.m10 .list').show();
		})
	})
</script>

<div class="m10_1">
	<div class="c_t c_t1">
		<i></i>
		<span><?=get_catname(17)?></span>
		<p><?=get_catname2(17)?></p>
	</div>
	<ul class="cle w html5_w90">
		<?=get_news_pic_list(3,17,12)?>
	</ul>
	<div class="hr" style="margin-bottom: 0px;">
		<a href="lists.php?pid=3&ty=17">了解更多</a>
	</div>
</div>

<div class="m10_1 m10_1s">
	<div class="c_t c_t1">
		<i></i>
		<span><?=get_catname(18)?></span>
		<p><?=get_catname2(18)?></p>
	</div>

	<ul class="cle w html5_w90">
		<?=get_news_pic_list(3,18,8)?>
	</ul>
	<div class="hr">
		<a href="lists.php?pid=3&ty=18">
			了解更多
		</a>
	</div>
</div>