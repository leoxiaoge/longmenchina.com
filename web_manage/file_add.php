<?
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';
$filenames=basename($PHP_SELF,"_add.php");

$pid=(int)$_GET['pid'];
$ty=(int)$_GET['ty'];
$tty=(int)$_GET['tty'];
$ttty=(int)$_GET['ttty'];

$gourl=get_tourl($pid,$ty,$tty,$ttty,"{$filenames}");
$classname=get_toclassname($pid,$ty,$tty,$ttty);
$cname=get_cname($pid,$ty,$tty,$ttty);
$showtype=get_showtype($pid,$ty,$tty,$ttty);

$trcount=1;
 
if (isset($_POST['dosubmit'])) {
 	$path="../".$upload_picpath."/";
 	
	$file_name=$_FILES['file']['name'];
	$filetype=end(explode(".", basename($file_name))); 
 	
	$data = new_addslashes($_POST['info']);
	$data = new_html_special_chars($data);
	if($_POST['info']['seodescription']) $data['seodescription'] = $_POST['info']['seodescription']; else $data['seodescription'] = cutstr_html($_POST['info']['content'],300);
  	$data['sendtime'] = strtotime($_POST['info']['sendtime']);
  	$data['isstate'] = 1;
	
	//附件上传
	if($file_name){
 		$filenewname=date("YmdHis").mt_rand(10,99).".".$filetype;
		uploadfile('file',$path,$filenewname);
		$data['file']=$filenewname;
	}else{
		$data['file']="";
	}
	
	//图片上传
	for ($i=1;$i<=$trcount;$i++){
		$img_more="img{$i}";
		$img_name_more="{$img_name}{$i}";
		$img_name_more=$_FILES[$img_more]['name'];
		$img_type_more="{$imgtype}{$i}";
		$img_type_more=end(explode(".", basename($img_name_more))); 
		
		
 		if($img_name_more){
			$imgnewname_more="{$imgnewname}{$i}";
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
			
		}else{
			$fields[$img_more]="";
		}
 	}	
	
	$insertid=$db->insert($data,"news",true);
   	 //exit($sql);
 	if($insertid) {
  		AddLog("添加{$cname}栏目内容",$_SESSION['Admin_UserName']);
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
<title>新闻发布页面</title>
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
		<li>信息添加</li>
	</ul>
</div>

<div class="formbody">

	<div id="usual1" class="usual"> 
	  
		<div id="tab1" class="tabson">
		
		<div class="formtext">Hi，<b><?=$_SESSION['Admin_UserName']?></b>，欢迎您使用信息发布功能！</div>
		
			<ul class="forminfo">
				<form action="" method="post" name="formlist" enctype="multipart/form-data"  onSubmit="return news_check(this);">
					<input type="hidden" name="info[pid]" value="<?=$pid?>">
					<input type="hidden" name="info[ty]" value="<?=$ty?>">
					<input type="hidden" name="info[tty]" value="<?=$tty?>">
					<input type="hidden" name="info[ttty]" value="<?=$ttty?>">
		
					<li><label>文件名称<b>*</b></label><input name="info[title]" type="text" class="dfinput" value="" /></li>
		
					<li><label>文件简介<b>*</b></label><?=get_kindeditor(new_stripslashes(new_html_entity_decode($row['content'])))?></li>
					<li><label>附件上传<b>*</b></label><input name="file" type="file" id="file"> <font color="#FF0000">限1M内</font></li>
					
					<li><label>创建时间<b>*</b></label><input name="info[sendtime]" type="text" class="dfinput" value="<?=$PHP_DATE?>" style="width:150px;"/></li>
				
					<li><label>信息排序<b>*</b></label><input name="info[disorder]" type="text" class="dfinput" value="0" style="width:100px;"/> 注：数字越大越排在前</li>			
					
					<li><label>&nbsp;</label><input name="dosubmit" type="submit" class="btn" value="马上发布"/></li>
				
				</form>
				
			</ul>
		
		</div> 

	</div> 

</div>


</body>

</html>
