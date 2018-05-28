<? 
$Title="会员中心";
include_once('./include/checkuser.inc.php'); 
include_once('header.php');

$path="./".$upload_picpath."/";

if($_POST['send']==1){

	$data = new_addslashes($_POST['info']);
   	
	$insertid=$db->update($data,"users"," id={$uid}");
	//exit($sql);
	if($insertid) {
  		JsSucce("操作成功！","main.php");
	}else{
		JsError("操作失败！");
	}
	exit();
}

?>

<div class="user_warp">

	<div class="w cle html5_w90">

		<div class="weizhiss">
			<a href="./index.php">首页</a> > <a >个人信息</a>
		</div>

		<div class="cle user_info_warp">
			
			<? include_once('ulefts.php');?>

			<div class="m19 fr cle common_ri">

				<div class="end">
					<img src="img/img92.jpg" />编辑个人信息
				</div>

				<form  method="post" enctype="multipart/form-data" onSubmit="return check_update(this);">

                	<input name="send" type="hidden" value="1"/>

					<div class="form">
	
						<div class="list cle">
							<span class="fl">登录帐号：</span>
							<div class="fl tel"><?=substr_replace($row['username'],'****',3,4);?></div>
						</div>
						
						<? if($row['login']==1){ ?>
	
						<div class="list cle input">
							<span class="fl">真实姓名：</span>
							<input type="text" class="fl" name="info[xm]" value="<?=$row['xm']?>" />
						</div>
	
						<div class="list cle input">
							<span class="fl">手机号码：</span>
							<input type="text" class="fl" name="info[tel]" value="<?=$row['tel']?>" />
						</div>
						
						<div class="list cle input">
							<span class="fl">邮箱地址：</span>
							<input type="text" class="fl" name="info[email]" value="<?=$row['email']?>" />
						</div>
	
						<div class="list cle radio">
							<span class="fl">性&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;别：</span>
							<label>
								<input type="radio" name="info[sex]" <? if($row['sex']==""||$row['sex']=="男"){ ?>checked="checked"<? } ?> value="男"/>男
							</label>
							<label>
								<input type="radio" name="info[sex]" <? if($row['sex']=="女"){ ?>checked="checked"<? } ?> value="女"/>女
							</label>
						</div>
	
						<div class="list cle select">
							<span class="fl">服&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;务：</span>
							<select class="fl" name="info[yxfw]">
								<option value="">意向服务</option>
								<option value="政务猎头" <? if($row['yxfw']=="政务猎头"){ ?> selected<? } ?>>政务猎头</option>
								<option value="职业转型" <? if($row['yxfw']=="职业转型"){ ?> selected<? } ?>>职业转型</option>
								<option value="政务沙龙" <? if($row['yxfw']=="政务沙龙"){ ?> selected<? } ?>>政务沙龙</option>
							</select>
							<select class="fl" name="info[yxhy]">
								<option value="">意向行业</option>
								<option value="大地产" <? if($row['yxhy']=="大地产"){ ?> selected<? } ?>>大地产</option>
								<option value="大金融" <? if($row['yxhy']=="大金融"){ ?> selected<? } ?>>大金融</option>
								<option value="大健康" <? if($row['yxhy']=="大健康"){ ?> selected<? } ?>>大健康</option>
								<option value="IT互联网" <? if($row['yxhy']=="IT互联网"){ ?> selected<? } ?>>IT互联网</option>
								<option value="大文娱" <? if($row['yxhy']=="大文娱"){ ?> selected<? } ?>>大文娱</option>
								<option value="新兴产业" <? if($row['yxhy']=="新兴产业"){ ?> selected<? } ?>>新兴产业</option>
								<option value="其它行业" <? if($row['yxhy']=="其它行业"){ ?> selected<? } ?>>其它行业</option>
							</select>
						</div>
						<script type="text/javascript">
							_init_area();
						</script>
						<div class="list cle select">
							<span class="fl">
								所在城市：
							</span>
							<div class="info">
								<div class="cle">
									<select id="s_province" name="info[province]" class="fl">
										<? if($row['province']){ ?><option value="<?=$row['province']?>" selected><?=$row['province']?></option><? } ?>
									</select>&nbsp;&nbsp;
									<select id="s_city" name="info[city]" class="fl">
										<? if($row['city']){ ?><option value="<?=$row['city']?>" selected><?=$row['city']?></option><? } ?>
									</select>&nbsp;&nbsp;
									<script type="text/javascript">
									_init_area();
									</script>
								</div>
								<div id="show"></div>
							</div>
							<div class="fl xiangxi">
								<input type="text" name="info[addess]" value="<?=$row['addess']?>" />
							</div>
						</div>
	
						<script type="text/javascript">
							var Gid = document.getElementById;
							var showArea = function() {
								Gid('show').innerHTML = "<h3>省" + Gid('s_province').value + " - 市" +
									Gid('s_city').value + " - 县/区" +
									Gid('s_county').value + "</h3>"
							}
							Gid('s_county').setAttribute('onchange', 'showArea()');
						</script>
						
						<div class="list cle input">
							<span class="fl">所在系统：</span>
							<input type="text" class="fl" name="info[szxt]" value="<?=$row['szxt']?>" />
						</div>
						
						<div class="but">
							<input type="submit" value="保存" />
						</div>
	
						<? }elseif($row['login']==2){ ?>
						
						<div class="list cle input">
							<span class="fl">真实姓名：</span>
							<input type="text" class="fl" name="info[xm]" value="<?=$row['xm']?>" />
						</div>
	
						<div class="list cle input">
							<span class="fl">手机号码：</span>
							<input type="text" class="fl" name="info[tel]" value="<?=$row['tel']?>" />
						</div>
						
						<div class="list cle input">
							<span class="fl">公司名称：</span>
							<input type="text" class="fl" name="info[gsname]" value="<?=$row['email']?>" />
						</div>
	
						<div class="list cle select">
							<span class="fl">服&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;务：</span>
							<select class="fl" name="info[yxfw]">
								<option value="">意向服务</option>
								<option value="政务猎头" <? if($row['yxfw']=="政务猎头"){ ?> selected<? } ?>>政务猎头</option>
								<option value="职业转型" <? if($row['yxfw']=="职业转型"){ ?> selected<? } ?>>职业转型</option>
								<option value="政务沙龙" <? if($row['yxfw']=="政务沙龙"){ ?> selected<? } ?>>政务沙龙</option>
							</select>
							<select class="fl" name="info[yxhy]">
								<option value="">意向行业</option>
								<option value="大地产" <? if($row['yxhy']=="大地产"){ ?> selected<? } ?>>大地产</option>
								<option value="大金融" <? if($row['yxhy']=="大金融"){ ?> selected<? } ?>>大金融</option>
								<option value="大健康" <? if($row['yxhy']=="大健康"){ ?> selected<? } ?>>大健康</option>
								<option value="IT互联网" <? if($row['yxhy']=="IT互联网"){ ?> selected<? } ?>>IT互联网</option>
								<option value="大文娱" <? if($row['yxhy']=="大文娱"){ ?> selected<? } ?>>大文娱</option>
								<option value="新兴产业" <? if($row['yxhy']=="新兴产业"){ ?> selected<? } ?>>新兴产业</option>
								<option value="其它行业" <? if($row['yxhy']=="其它行业"){ ?> selected<? } ?>>其它行业</option>
							</select>
						</div>

						<script type="text/javascript">
							_init_area();
						</script>

						<div class="list cle select">
							<span class="fl">
								所在城市：
							</span>
							<div class="info">
								<div class="cle">
									<select id="s_province" name="info[province]" class="fl">
										<? if($row['province']){ ?><option value="<?=$row['province']?>" selected><?=$row['province']?></option><? } ?>
									</select>&nbsp;&nbsp;
									<select id="s_city" name="info[city]" class="fl">
										<? if($row['city']){ ?><option value="<?=$row['city']?>" selected><?=$row['city']?></option><? } ?>
									</select>&nbsp;&nbsp;
									<script type="text/javascript">
									_init_area();
									</script>
								</div>
								<div id="show"></div>
							</div>
							<div class="fl xiangxi">
								<input type="text" name="info[addess]" value="<?=$row['addess']?>" />
							</div>
						</div>
	
						<script type="text/javascript">
							var Gid = document.getElementById;
							var showArea = function() {
								Gid('show').innerHTML = "<h3>省" + Gid('s_province').value + " - 市" +
									Gid('s_city').value + " - 县/区" +
									Gid('s_county').value + "</h3>"
							}
							Gid('s_county').setAttribute('onchange', 'showArea()');
						</script>
						
						<div class="list cle input">
							<span class="fl">人才招聘需求：</span>
							<input type="text" class="fl" name="info[szxt]" value="<?=$row['szxt']?>" />
						</div>
						
						<div class="but">
							<input type="submit" value="保存" />
						</div>
						
						<? }elseif($row['login']==3){ ?>
						
						<div class="list cle input">
							<span class="fl">真实姓名：</span>
							<input type="text" class="fl" name="info[xm]" value="<?=$row['xm']?>" />
						</div>
	
						<div class="list cle input">
							<span class="fl">手机号码：</span>
							<input type="text" class="fl" name="info[tel]" value="<?=$row['tel']?>" />
						</div>
						
						<div class="list cle input">
							<span class="fl">单位名称：</span>
							<input type="text" class="fl" name="info[gsname]" value="<?=$row['email']?>" />
						</div>

						<div class="list cle input">
							<span class="fl">招才引智需求：</span>
							<input type="text" class="fl" name="info[szxt]" value="<?=$row['szxt']?>" />
						</div>
						
						<div class="but">
							<input type="submit" value="保存" />
						</div>
						
						<? } ?>
	
					</div>

				</form>

			</div>

		</div>

	</div>

</div>

<? include_once('footer.php');?>
    
</body>
</html>