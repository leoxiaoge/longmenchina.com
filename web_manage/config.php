<?
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';

$sql="select * from `{$tablepre}config` order by id asc";
$result=$db->sql_query($sql);
while($row=$db->sql_fetchrow($result)){
	//echo $row['id'].$row['varinfo']."<br>";
 	 $sys[]=$row;
}
//$sys = array_filter($sys);

//$sys=sort($sys);
//print_r($sys);
  
$path="../".$upload_picpath."/";

if(isset($_POST['dosubmit'])){
	$delimg=$_POST['delimg'];
	$img_name=$_FILES['sys_img1']['name'];
	$imgtype=end(explode(".", basename($img_name)));

	$delimg2=$_POST['delimg2'];
	$img_name2=$_FILES['sys_img2']['name'];
	$imgtype2=end(explode(".", basename($img_name2))); 

	$delimg3=$_POST['delimg3'];
	$img_name3=$_FILES['sys_img3']['name'];
	$imgtype3=end(explode(".", basename($img_name3))); 

 	if($img_name){
		if (file_exists($path.$delimg)) @unlink($path.$delimg); 
		$imgnewname=date("YmdHis").mt_rand(10,99).".".$imgtype;
		uploadimg('sys_img1',$path,$imgnewname);
		$sys_img1=$imgnewname;
	}
  	
	if($img_name2){
		if (file_exists($path.$delimg2)) @unlink($path.$delimg2); 
		$imgnewname2=date("YmdHis").mt_rand(10,88).".".$imgtype2;
		uploadimg('sys_img2',$path,$imgnewname2);
		$sys_img2=$imgnewname2;
	} 
 	
	if($img_name3){
		if (file_exists($path.$delimg3)) @unlink($path.$delimg3); 
		$imgnewname3=date("YmdHis").mt_rand(10,77).".".$imgtype3;
		uploadimg('sys_img3',$path,$imgnewname3);
		$sys_img3=$imgnewname3;
	} 
 	
	
	if($sys_img1) $db->sql_query("UPDATE `{$tablepre}config` SET `varvalue`='$sys_img1' WHERE varname='sys_img1'");
	
 	if($sys_img2) $db->sql_query("UPDATE `{$tablepre}config` SET `varvalue`='$sys_img2' WHERE varname='sys_img2'");
	
 	if($sys_img3) $db->sql_query("UPDATE `{$tablepre}config` SET `varvalue`='$sys_img3' WHERE varname='sys_img3'");
 
	if($_POST['isdel1']==1){
		$db->sql_query("UPDATE `{$tablepre}config` SET `varvalue`='' WHERE varname='sys_img1'");
		if (file_exists($path.$delimg)) @unlink($path.$delimg); 
	}
	
	if($_POST['isdel2']==1){
		$db->sql_query("UPDATE `{$tablepre}config` SET `varvalue`='' WHERE varname='sys_img2'");
		if (file_exists($path.$delimg2)) @unlink($path.$delimg2); 
	}
	if($_POST['isdel3']==1){
		$db->sql_query("UPDATE `{$tablepre}config` SET `varvalue`='' WHERE varname='sys_img3'");
		if (file_exists($path.$delimg3)) @unlink($path.$delimg3); 
	}
 
    foreach($_POST as $k=>$v){
		$db->sql_query("UPDATE `{$tablepre}config` SET `varvalue`='$v' WHERE varname='$k' and varname<>'delimg' and varname<>'delimg2'");
 		$num=1;
     }
	
	if($num){
	
		$odelpathid_arr = $_POST["odelpathid"];
		$odownid_arr = $_POST["odownid"];
		$oqq_arr = $_POST["oqq"];
		$otitle_arr = $_POST["otitle"];
		
		for($i=0;$i<count($otitle_arr);$i++){
			if($oqq_arr[$i]){
				if($odownid_arr[$i]){
					$xstrsql="update `{$tablepre}qq` set qq='$oqq_arr[$i]',title='$otitle_arr[$i]' where id=".$odownid_arr[$i]."";
 					@$db->sql_query($xstrsql);
				}else{
					$wstrsql = "insert into `{$tablepre}qq`(qq,title)values('$oqq_arr[$i]','$otitle_arr[$i]')";
 					@$db->sql_query($wstrsql);
					$db->sql_query("update `{$tablepre}config` set iskf=1 where id=1");
				}	
 			}	
			if($odelpathid_arr[$i]){
				$delstr="delete from `{$tablepre}qq` where id=".$odelpathid_arr[$i]."";
 				@$db->sql_query($delstr);
			}	
			
		}
	}	
 	
	
	JsSucce("操作成功！","config.php");
	exit();
}elseif (isset($_POST['adosubmit'])) {
    	
   	$typeid = 4;
   	$varname = trim($_POST['varname']);
   	$varinfo = trim($_POST['varinfo']);
   	$varvalue = trim($_POST['varvalue']);
   	
    if(trim($varname)=='' || preg_match("#[^a-z_]#i", $varname) )
    {
		JsError("变量名不能为空并且必须为[a-z_]组成！");
         exit();
    }
	
	
	//检查变量名的唯一性
	$result = $db->sql_query("SELECT id FROM `{$tablepre}config` WHERE varname='$varname'");
	if($bd=$db->sql_fetchrow($result)){
		JsError("该变量名称已经存在！");
		exit();
	}
 	
	$sql="insert into `{$tablepre}config`(typeid,varname,varinfo,varvalue) values(4,'$varname','$varinfo','$varvalue')";
 
 	if($db->sql_query($sql)) {
 		JsSucce("操作成功！","config.php");
	}else{
		JsError("操作失败！");
	}
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>系统参数设置</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/select.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="js/checkform.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.idTabs.min.js"></script>
</head>

<body>

<div class="place">
	<span>位置：</span>
	<ul class="placeul">
		<li>基本信息</li>
		<li>管理页面</li>
	</ul>
</div>
    
<div class="formbody">

	<div id="usual1" class="usual"> 

		<div class="itab">
			<ul> 
				<li><a href="#tab1" class="selected">基本信息</a></li> 
				<li><a href="#tab2">上传参数</a></li> 
				<li><a href="#tab3">第三方安装代码</a></li> 
				<? if($_SESSION['is_hidden']==true){?><li><a href="#tab4">添加新变量</a></li><? }?>
			</ul>
		</div> 

		<div id="tab1" class="tabson">

			<div class="formtext">Hi，<b><?=$_SESSION['Admin_UserName']?></b>，欢迎您使用信息发布功能！</div>

			<form action="" method="post" name="myform" id="myform" enctype="multipart/form-data">
				<input type="hidden" name="delimg" value="<?=$sys[20]['varvalue']?>">
				<input type="hidden" name="delimg2" value="<?=$sys[21]['varvalue']?>">
				<input type="hidden" name="delimg3" value="<?=$sys[22]['varvalue']?>">

				<ul class="forminfo">
					<? if($_SESSION['is_hidden']==true){?>
					<li><label style="width:110px;">网站关闭<b>*</b></label>
					<input name="sys_showinfo" type="text" class="dfinput" id="sys_showinfo" value="<?=$sys[12]['varvalue']?>"/> 
					<input type="radio" name="sys_isstate" value="0" <? if($sys['9']['varvalue']=='0') echo 'checked'?>> 开启</input>
					<input type="radio" name="sys_isstate" value="1" <? if($sys['9']['varvalue']=='1') echo 'checked'?>> 关闭</input>
					<? } ?>

					<li><label style="width:110px;">网站名称<b>*</b></label><input name="sys_sitename" type="text" class="dfinput" value="<?=$sys[13]['varvalue']?>"/><? if($_SESSION['is_hidden']==true){?> $System_sitename<? }?></li>

					<li><label style="width:110px;">首页LOGO<b>*</b></label><cite>
					<? 
					if ($sys[20]['varvalue']==""){
						echo '<input name="sys_img1" type="file" id="sys_img1">';
					}else{
						echo"<input name=\"sys_img1\" type=\"file\" id=\"sys_img1\"> <a href=\"{$path}/".$sys[20]['varvalue']."\" target=\"_blank\">查看图片</a>  <input name=\"isdel1\" type=\"checkbox\" class=radio id=\"isdel1\" value=\"1\"> 删除";
					}
					?>
					<font color="red">*请上传png透明底图,尺寸:147*60像素
						<? if($_SESSION['is_hidden']==true){?> $System_img1<? } ?>
					</font></cite></li>

					<li><label style="width:110px;">微信公众号<b>*</b></label><cite>
					<? 
					if ($sys[21]['varvalue']==""){
						echo '<input name="sys_img2" type="file" id="sys_img2">';
					}else{
						echo"<input name=\"sys_img2\" type=\"file\" id=\"sys_img2\"> <a href=\"{$path}/".$sys[21]['varvalue']."\" target=\"_blank\">查看图片</a>  <input name=\"isdel2\" type=\"checkbox\" class=radio id=\"isdel2\" value=\"1\"> 删除";
					}
					?>
					<font color="red">*请上传png透明底图,尺寸:200*200像素
						<? if($_SESSION['is_hidden']==true){?> $System_img2<? } ?>
					</font></cite></li>

					<li><label style="width:110px;">微信二维码<b>*</b></label><cite>
					<? 
					if ($sys[22]['varvalue']==""){
						echo '<input name="sys_img3" type="file" id="sys_img3">';
					}else{
						echo"<input name=\"sys_img3\" type=\"file\" id=\"sys_img3\"> <a href=\"{$path}/".$sys[22]['varvalue']."\" target=\"_blank\">查看图片</a>  <input name=\"isdel3\" type=\"checkbox\" class=radio id=\"isdel3\" value=\"1\"> 删除";
					}
					?>
					<font color="red">*请上传png透明底图,尺寸：200*200像素
						<? if($_SESSION['is_hidden']==true){?> $System_img3<? } ?>
					</font></cite></li>

					<li><label style="padding-right:300px;">

					<SCRIPT>
					function doroom(){
					var i;
					var str="";
					var oldi=0;
					var j=0;
					oldi=parseInt(document.myform.oeditroomnum.value);
					for(i=1;i<=document.myform.oroomnum.value;i++){
						j=i+oldi;
						str=str+"<tr><td>QQ号：<input name=oqq[] value='' class='dfinput' style='width:150px'> 名称：<input name=otitle[] value='' class='dfinput' style='width:100px'></td></tr>";
					}
					document.getElementById("addroom").innerHTML="<table width='100%'>"+str+"</table>";
					}
					</SCRIPT>

					<table width="500">
						<? if($sys['10']['varvalue']==0){?>
						<?
						$k=0;
						$sql2= "SELECT * FROM `{$tablepre}qq` order by id asc";
						$result2=$db->sql_query($sql2);
						while($bd2=$db->sql_fetchrow($result2)){
						$sid=$bd2['id'];
						$k++;
						?>
						<tr>
							<td><input value="<?=$sid?>" type="checkbox" name="odelpathid[]" />删 QQ号：<input name="oqq[]" class="dfinput" style="width:150px;" value="<?=$bd2['qq']?>" size="25"/> 
							名称：<input name="otitle[]" class="dfinput" style="width:100px;" value="<?=$bd2['title']?>" size="25"/>
							<input value="<?=$sid?>" name="odownid[]" type="hidden"/>
							<font color="red">*限4个字</font>
							</td>
						</tr>
						<? } ?>
						<tr>
							<td>
							再增加：<INPUT id="oeditroomnum" value="<?=$k?>" type="hidden" name="oeditroomnum">
							<INPUT id="oroomnum" value="1" type="text" name="oroomnum" class="dfinput" style="width:50px;"> 个
							<a href="javascript:;" onclick="javascript:doroom();">确认增加</a>
							</td>
						</tr>
						<? }else{ ?>
						<tr>
							<td>
							QQ号：<input name="oqq[]" class="dfinput" style="width:150px;" value="" size="25"/> 
							名称：<input name="otitle[]" class="dfinput" style="width:100px;" value="" size="25"/>
							<font color="red">*限4个字</font>
							</td>
						</tr>
						<tr>
							<td>
							QQ号：<input name="oqq[]" class="dfinput" style="width:150px;" value="" size="25"/> 
							名称：<input name="otitle[]" class="dfinput" style="width:100px;" value="" size="25"/>
							<font color="red">*限4个字</font>
							</td>
						</tr>
						<tr>
							<td>
							QQ号：<input name="oqq[]" class="dfinput" style="width:150px;" value="" size="25"/> 
							名称：<input name="otitle[]" class="dfinput" style="width:100px;" value="" size="25"/>
							<font color="red">*限4个字</font>
							</td>
						</tr>
						
						<tr>
							<td>
							再增加：<INPUT id="oeditroomnum" value="3" type="hidden" name="oeditroomnum">
							<INPUT id="oroomnum" value="1" type="text" name="oroomnum" class="dfinput" style="width:50px;"> 个
							<a href="javascript:void()" onclick="javascript:doroom();">确认增加</a>
							</td>
						</tr>
						<? } ?>
						<tr>
							<td id=addroom>&nbsp;</td>
						</tr>
					</table>

					</li>

					<li><label style="width:110px;">手机号码</label><input name="sys_hotsearch" type="text" class="dfinput" value="<?=$sys[23]['varvalue']?>"/><? if($_SESSION['is_hidden']==true){ ?> $System_hotsearch<? } ?></li>

					<li><label style="width:110px;">联系电话</label><input name="sys_phone" type="text" class="dfinput" value="<?=$sys[24]['varvalue']?>"/> <? if($_SESSION['is_hidden']==true){ ?>$System_phone<? } ?></li>

					<li><label style="width:110px;">联系QQ</label><input name="sys_qq" type="text" class="dfinput" value="<?=$sys[25]['varvalue']?>"/> <? if($_SESSION['is_hidden']==true){ ?>$System_qq<? } ?></li>

					<li><label style="width:110px;">邮箱地址</label><input name="sys_email" type="text" class="dfinput" value="<?=$sys[26]['varvalue']?>"/> <? if($_SESSION['is_hidden']==true){ ?>$System_email<? } ?></li>

					<li><label style="width:110px;">联系方式</label><textarea name="sys_contact" id="sys_contact" style="width:520px;height:100px;" class="dfinput"><?=$sys[19]['varvalue']?></textarea><? if($_SESSION['is_hidden']==true){?> $System_contact<? } ?></li>

					<li><label style="width:110px;">底部版权</label><textarea name="sys_copyright" id="sys_copyright" style="width:520px;height:100px;" class="dfinput"><?=$sys[18]['varvalue']?></textarea><? if($_SESSION['is_hidden']==true){?> $System_copyright<? }?></li>
					<? 
						$sqls="select * FROM `{$tablepre}config` where typeid=4 ";	
						$results=$db->sql_query($sqls);
						$i=-1;
						while($rs=$db->sql_fetchrow($results)){
							$i++;
							$bds[]=$rs;
					?>	
					<li><label style="width:110px;"><?=$rs['varinfo']?></label><input name="<?=$rs['varname']?>" type="text" class="dfinput" value="<?=$rs['varvalue']?>"/> 调用字段: $tag[<?=$i?>]['varvalue']</li>
					<? }?>		
				</ul>
					
				<li><label style="width:110px;">&nbsp;</label><input name="dosubmit" type="submit" class="btn" value="马上修改"/></li>
			</form>
				
		</div> 
	
		<div id="tab2" class="tabson">
			<form action="" method="post" name="myform2" id="myform2" enctype="multipart/form-data">
			<ul class="forminfo">
				<li><label style="width:110px;">文件类型<b>*</b></label><input name="sys_filetype" type="text" class="dfinput" value="<?=$sys[0]['varvalue']?>"/> 以|分隔后缀名,切记勿允许上传asp/exe文件</li>
				<li><label style="width:110px;">文件大小<b>*</b></label><input name="sys_filesize" type="text" class="dfinput" value="<?=$sys[1]['varvalue']?>"/> K</li>
				<li><label style="width:110px;">图片类型<b>*</b></label><input name="sys_pictype" type="text" class="dfinput" value="<?=$sys[2]['varvalue']?>"/> 以|分隔后缀名,切记勿允许上传asp/exe文件</li>
				<li><label style="width:110px;">图片大小<b>*</b></label><input name="sys_picsize" type="text" class="dfinput" value="<?=$sys[3]['varvalue']?>"/> K</li>
			</ul>
			<li><label style="width:110px;">&nbsp;</label><input name="dosubmit" type="submit" class="btn" value="马上修改"/></li>
			</form>
		</div>  
			
		<div id="tab3" class="tabson">
			<form action="" method="post" name="myform3" id="myform3" enctype="multipart/form-data">
				<ul class="forminfo">
					<li><label style="width:110px;">流量统计<b>*</b></label><textarea id="sys_visits" name="sys_visits" style="width:520px;height:100px;" class="dfinput"><?=stripslashes($sys[4]['varvalue'])?></textarea></li>
					<li><label style="width:110px;">在线客服<b>*</b></label><textarea id="sys_kefu" name="sys_kefu" style="width:520px;height:100px;" class="dfinput"><?=stripslashes($sys[5]['varvalue'])?></textarea></li>
					<li><label style="width:110px;">在线分享<b>*</b></label><textarea id="sys_share" name="sys_share" style="width:520px;height:100px;" class="dfinput"><?=stripslashes($sys[6]['varvalue'])?></textarea></li>
				</ul>
				<li><label style="width:110px;">&nbsp;</label><input name="dosubmit" type="submit" class="btn" value="马上修改"/></li>
			</form>
		</div>  
			
		<? if($_SESSION['is_hidden']==true){?>
		<div id="tab4" class="tabson">
			<form action="" method="post" name="myform4" id="myform4" enctype="multipart/form-data">
				<ul class="forminfo">
					<li><label>参数说明<b>*</b></label><input name="varinfo" type="text" class="dfinput" value=""/></li>
					<li><label>参数值<b>*</b></label><input name="varvalue" type="text" class="dfinput" value=""/></li>
					<li><label>字段名(英文)<b>*</b></label><input name="varname" type="text" class="dfinput" value=""/></li>
				</ul>
				<li><label style="width:110px;">&nbsp;</label><input name="adosubmit" type="submit" class="btn" value="添加字段"/></li>
			</form>
		</div>
		<? }?>
			
	</div> 

	<script type="text/javascript"> 
	  $("#usual1 ul").idTabs(); 
	</script>
	
	<script type="text/javascript">
	$('.tablelist tbody tr:odd').addClass('odd');
	</script>

</div>

</body>

</html>