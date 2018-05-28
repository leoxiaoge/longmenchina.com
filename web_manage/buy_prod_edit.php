<?
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';
$filenames=basename($PHP_SELF,"_edit.php");

$id= (int)$_GET['id'];
$path="../".$upload_picpath."/";

$sql="select * FROM `{$tablepre}sell_temp` where id=".$id."";
//echo $sql;
$result=$db->sql_query($sql);
if($row=$db->sql_fetchrow($result)){
	$pid=$row['pid'];
}

$trcount=6;

if (isset($_POST['dosubmit'])) {
 	
  	$data = new_addslashes($_POST['info']);

	for ($i=1;$i<=$trcount;$i++){
		$delimg_more="delimg{$i}";
		$delimg_more=$_POST[$delimg_more];
		$img_more="img{$i}";
		$img_name_more="{$img_name}{$i}";
		$img_name_more=$_FILES[$img_more]['name'];
		$img_type_more="{$imgtype}{$i}";
		$img_type_more=end(explode(".", basename($img_name_more))); 
	
 		if($img_name_more){
			if (file_exists($path.$delimg_more)) @unlink($path.$delimg_more); 
			$imgnewname_more=date("YmdHis").mt_rand(10,99).".".$img_type_more;
			$imgnewname_more_small="small_{$imgnewname_more}";
			
			uploadimg($img_more,$path,$imgnewname_more);
			if((int)$_POST['iscutout']){
				$ic=new ImageCrop($path."/".$imgnewname_more,$path."/".$imgnewname_more_small);
				$ic->Crop($System_Picwidth,$System_Picheight,2);
				$ic->SaveImage();
				//$ic->SaveAlpha();将补白变成透明像素保存
				$ic->destory();
				$data[$img_more]=$imgnewname_more_small;
			}else{
				$data[$img_more]=$imgnewname_more;
			}
		}
	}
	
	$insertid=$db->update($data,"sell_temp"," id={$id}");
	//exit($sql);
 	if($insertid) {
 		AddLog("编辑{$cname}栏目内容",$_SESSION['Admin_UserName']);
 		JsSucce("操作成功！","sell_prod.php");
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
<title>新闻编辑页面</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/select.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="js/jquery-1.6.2.min.js"></script>
<script language="javascript" src="js/checkform.js"></script>
<script charset="utf-8" src="myeditor/kindeditor-min.js"></script>
<script charset="utf-8" src="myeditor/lang/zh_CN.js"></script>
<script charset="utf-8" src="myeditor/plugins/code/prettify.js"></script>
<script>
KindEditor.ready(function(K) {
	var editor1 = K.create('.editor_id', {
		filterMode: false,//是否开启过滤模式
		cssPath : 'myeditor/plugins/code/prettify.css',
		uploadJson : 'myeditor/php/upload_json.php',
		fileManagerJson : 'myeditor/php/file_manager_json.php',
		allowFileManager : true,
 		afterBlur : function() {
		 this.sync();
		 K.ctrl(document, 13, function() {
		  K('form[name=formlist]')[0].submit();
		 });
		 K.ctrl(this.edit.doc, 13, function() {
		  K('form[name=formlist]')[0].submit();
		 });
		}
	});
	prettyPrint();
});
</script>

</head>

<body>

<div class="place">
	<span>位置：</span>
	<ul class="placeul">
		<li><a href="mains.php">首页</a></li>
		<?=$classname?>
		<li>信息编辑</li>
	</ul>
</div>

<div class="formbody">

	<div id="usual1" class="usual"> 

		<div id="tab1" class="tabson">

			<div class="formtext">Hi，<b><?=$_SESSION['Admin_UserName']?></b>，欢迎您使用信息发布功能！</div>

			<ul class="forminfo">

				<form action="" method="post" name="formlist" enctype="multipart/form-data" onSubmit="return prod_check(this);">
					<input type="hidden" name="referer" value="<?=$indexurl?>">
					<? for ($i=1;$i<=$trcount;$i++){
						$hideimg_more="img{$i}";
						echo "<input type=\"hidden\" name=\"delimg{$i}\" value=\"{$row[$hideimg_more]}\">";
					}
					?>
					<li><label>商品标题<b>*</b></label><input name="info[title]" type="text" class="dfinput" value="<?=$row['title']?>"  style="width:518px;"/></li>

					<li><label>标题说明<b>*</b></label><textarea name="info[seodescription]" id="seodescription" style="width:520px;height:100px;" class="dfinput"><?=$row['seodescription']?></textarea></li>

					<li><label>钢琴详情<b>*</b></label><?=get_kindeditor(new_stripslashes(new_html_entity_decode($row['content'])),"content")?></li>

					<li><label>配置参数<b>*</b></label><?=get_kindeditor(new_stripslashes(new_html_entity_decode($row['content2'])),"content2")?></li>

					<? 
					$imgsize=get_toimgsize($pid,$ty,$tty,$ttty);
					$arrValue = explode(',',$imgsize);
					for ($i=1;$i<=$trcount;$i++){ 
						if($i==1)
							$s="缩略图";
						elseif($i==2)
							$s="产品图片";
						elseif($i==3)
							$s="钢琴外观1";
						elseif($i==4)
							$s="钢琴外观2";
						elseif($i==5)
							$s="内部配置1";
						elseif($i==6)
							$s="内部配置2";
					?>
					<li><label><?=$s?><b>*</b></label><? 
					$showimg_more="img{$i}";
					if ($row[$showimg_more]==""){
						echo "<input name=\"{$showimg_more}\" type=\"file\" id=\"{$showimg_more}\">";
					}else{
						echo"<input name=\"{$showimg_more}\" type=\"file\" id=\"{$showimg_more}\"> <a href=\"{$path}{$row[$showimg_more]}\" target=\"_blank\">查看图片</a>";
					}
					?> <font color="#FF0000">图片大小:<?=$System_Picsize?>K内</font></li>

					<? } ?>
					
					<li><label>钢琴外观描述1<b>*</b></label><input name="info[img3_title]" type="text" id="seokeywords" value="<?=$row['img3_title']?>" class="dfinput" style="width:518px;"></li>
					
					<li><label>钢琴外观描述2<b>*</b></label><input name="info[img4_title]" type="text" id="seokeywords" value="<?=$row['img4_title']?>" class="dfinput" style="width:518px;"></li>
					
					<li><label>内部配置描述1<b>*</b></label><input name="info[img5_title]" type="text" id="seokeywords" value="<?=$row['img5_title']?>" class="dfinput" style="width:518px;"></li>
					
					<li><label>内部配置描述2<b>*</b></label><input name="info[img6_title]" type="text" id="seokeywords" value="<?=$row['img6_title']?>" class="dfinput" style="width:518px;"></li>

					<li><label>&nbsp;</label><input name="dosubmit" type="submit" class="btn" value="马上修改"/></li>
				
				</form>

			</ul>
		
		</div> 

	</div> 

</div>


</body>

</html>
