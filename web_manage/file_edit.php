<?
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';
$filenames=basename($PHP_SELF,"_edit.php");

$id= (int)$_GET['id'];
$path="../".$upload_picpath."/";

$sql="select * FROM `{$tablepre}news` where id=".$id."";
$result=$db->sql_query($sql);
if($row=$db->sql_fetchrow($result)){
	$content=$row['content'];
	$introduce=$row['introduce'];
	$pid=$row['pid'];
	$ty=$row['ty'];
	$tty=$row['tty'];
	$ttty=$row['ttty'];
	$showtype=get_showtype($pid,$ty,$tty,$ttty);
	$gourl=get_tourl($pid,$ty,$tty,$ttty,"{$filenames}");
	$classname=get_toclassname($pid,$ty,$tty,$ttty);
	$cname=get_cname($pid,$ty,$tty,$ttty);
}

if($ty==29) $trcount=2;else $trcount=1;

if (isset($_POST['dosubmit'])) {
 	
	$delfile=$_POST['delfile'];
	$file_name=$_FILES['file']['name'];
	$filetype=end(explode(".", basename($file_name))); 

 	$data = new_addslashes($_POST['info']);
	$data = new_html_special_chars($data);
	if($_POST['info']['seodescription']) $data['seodescription'] = $_POST['info']['seodescription']; else $data['seodescription'] = cutstr_html($_POST['info']['content'],300);
	$data['sendtime'] = strtotime($_POST['info']['sendtime']);
	
	//附件上传
	if($file_name){
		if (file_exists($path.$delfile)) @unlink($path.$delfile); 
 		$filenewname=date("YmdHis").mt_rand(10,99).".".$filetype;
		uploadfile('file',$path,$filenewname);
		$data['file']=$filenewname;
	}

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
	
	$insertid=$db->update($data,"news"," id={$id}");
	//exit($sql);
 	if($insertid) {
 		AddLog("编辑{$cname}栏目内容",$_SESSION['Admin_UserName']);
 		JsSucce("操作成功！","{$gourl}");
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
				<form action="" method="post" name="formlist" enctype="multipart/form-data" onSubmit="return news_check(this);">
					<input type="hidden" name="referer" value="<?=$indexurl?>">
					<? for ($i=1;$i<=$trcount;$i++){
					$hideimg_more="img{$i}";
						echo "<input type=\"hidden\" name=\"delimg{$i}\" value=\"{$row[$hideimg_more]}\">";
					}
					?>
					<input type="hidden" name="delfile" value="<?=$row['file']?>">
					
					<li><label>文件名称<b>*</b></label><input name="info[title]" type="text" class="dfinput" value="<?=$row['title']?>" /></li>
		
					<li><label>文件简介<b>*</b></label><?=get_kindeditor(new_stripslashes(new_html_entity_decode($row['content'])))?></li>
					
					<li><label>附件上传<b>*</b></label><? 
					  if ($row['file']==""){
						echo "<input name=\"file\" type=\"file\" id=\"file\">";
					  }else{
						echo"<input name=\"file\" type=\"file\" id=\"file\"> <a href=\"{$path}{$row['file']}\" target=\"_blank\">下载</a>";
					  }
					  ?> <font color="#FF0000">限1M内</font></li>
				
					<? for ($i=1;$i<=$trcount;$i++){
					if($i==1) $sname="封面图片";elseif($i==2) $sname="推荐图片"; 
					if($i==1) $simg=get_toimgsize($pid,$ty,$tty,$ttty);elseif($i==2) $simg="336 * 188"; 
					
					?>
					<li><label><?=$sname?><b>*</b></label><? 
					$showimg_more="img{$i}";
					if ($row[$showimg_more]==""){
						echo "<input name=\"{$showimg_more}\" type=\"file\" id=\"{$showimg_more}\">";
					}else{
						echo"<input name=\"{$showimg_more}\" type=\"file\" id=\"{$showimg_more}\"> <a href=\"{$path}{$row[$showimg_more]}\" target=\"_blank\">查看图片</a>";
					}
					?> <font color="#FF0000">图片大小:<?=$System_Picsize?>K内,图片尺寸：200*180px</font></li>
					<? }?>
					<li><label>创建时间<b>*</b></label><input name="info[sendtime]" type="text" class="dfinput" value="<?=date('Y-m-d H:i:s',$row['sendtime'])?>" style="width:150px;"/></li>
					<li><label>信息排序<b>*</b></label><input name="info[disorder]" type="text" class="dfinput" value="<?=$row['disorder']?>" style="width:100px;"/> 注：数字越大越排在前</li>			
					<li><label>&nbsp;</label><input name="dosubmit" type="submit" class="btn" value="马上修改"/></li>
				</form>
			</ul>
		
		</div> 

	</div> 

</div>


</body>

</html>
