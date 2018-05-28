<? 
$Title="修改密码";
include_once('./include/checkuser.inc.php'); 
include_once('header.php');

if($_POST['send']==1){
 
	$sqlvalues="";
	$fields=$_POST['fields'];
	$olduserpwd=trim($_POST['password']);
	$fields['userpwd']=trim($_POST['password1']);
	
	$sql="select * from `{$tablepre}users` where id={$uid} AND userpwd='".$olduserpwd."'";
	$result=$db->sql_query($sql);
	$Count=$db->sql_numrows($result);
	if($Count==0){
		JsError("提示：输入的原密码不正确 ！");
		exit();
	}else{
 		while(list ($key,$val) =each ($fields)){
			$sqlvalues.=",$key='$val'";
		}
		$sqlvalues=substr($sqlvalues,1);
	
		$sql="UPDATE `{$tablepre}users` SET ".$sqlvalues." where id={$uid}";
		 //exit($sql);
 		$db->sql_query($sql);
 		JsSucce("密码修改成功 ！","updatepwd.php");
		exit();
	}
}
?>

<div class="user_warp">

	<div class="w cle html5_w90">

		<div class="weizhiss">
			<a href="./index.php">首页</a> > <a >修改密码</a>
		</div>

		<div class="cle user_info_warp">

			<? include_once('ulefts.php');?>

			<div class="m19 fr cle common_ri m21">

				<div class="end">修改密码</div>

				<form action="" method="post" name="formlist" onSubmit="return check_updatepwd(this);">
			
					<input name="send" type="hidden" value="1"/>

					<div class="form" style="margin-top: 30px;">
						<div class="list cle">
							<span class="fl">登录帐号：</span>
							<div class="fl tel"><?=substr_replace($row['username'],'****',3,4);?></div>
						</div>
						<div class="list cle input">
							<span class="fl">原始密码：</span>
							<input type="password" class="fl" placeholder="请输入原始密码" name="password" />
						</div>
						<div class="list cle input">
							<span class="fl">新密码：</span>
							<input type="password" class="fl" placeholder="请输入新密码" name="password1" />
						</div>
						<div class="list cle input">
							<span class="fl">确认密码：</span>
							<input type="password" class="fl" placeholder="请输确认新密码" name="password2" />
						</div>
						<div class="but">
							<input type="submit" value="保存" />
						</div>
					</div>

				</form>

			</div>

		</div>

	</div>

</div>

<? include_once('footer.php');?>

</body>
</html>