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

if (isset($_POST['dosubmit'])) {
	
	$path="../".$upload_picpath."/";
	
 	$data = new_addslashes($_POST['info']);
	$data = new_html_special_chars($data);
  	$data['sendtime'] = strtotime($_POST['info']['sendtime']);
  	$data['isstate'] = 1;
   	
	//图片上传
	for ($i=1;$i<=1;$i++){
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
	
 	$insertid=$db->insert($data,"size2",true);

 	if($insertid) {
  		AddLog("添加{$cname}栏目内容",$_SESSION['Admin_UserName']);
		JsSucce("操作成功！","{$gourl}");
	}else{
		JsError("操作失败！");
	}
	exit();
}elseif (isset($_POST['gosubmit'])) {
	
 	$data = new_addslashes($_POST['info']);
	$data = new_html_special_chars($data);
  	$data['sendtime'] = strtotime($_POST['info']['sendtime']);
  	$data['isstate'] = 1;
   	
 	$insertid=$db->insert($data,"size2",true);

 	if($insertid) {
  		AddLog("添加{$cname}栏目内容",$_SESSION['Admin_UserName']);
		JsSucce("操作成功！","size2_add.php?pid={$pid}&ty={$ty}&tty={$tty}");
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
<title>现金抵用券添加页面</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/select.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="js/checkform.js"></script>
<script  type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>


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
		
				<form action="" method="post" name="formlist" enctype="multipart/form-data" onSubmit="return check_link(this);">
		
					<input type="hidden" name="info[pid]" value="<?=$pid?>">

					<li>
						<label>信息标题<b>*</b></label>
						<input name="info[title]" type="text" class="dfinput" value="" style=" width: 500px;"/>
					</li>
					
					<? 
					$imgsize=get_toimgsize($pid,$ty,$tty,$ttty);
					$arrValue = explode(',',$imgsize);
					for ($i=1;$i<=1;$i++){?>
					<li><label>配置图片<b>*</b></label><input name="img<?=$i?>" type="file" id="img<?=$i?>"> <font color="#FF0000">图片大小:<?=$System_Picsize?>K内,图片建议尺寸：800*800px</font></li>
					<? } ?>
					
					<li><label>创建时间<b>*</b></label><input name="info[sendtime]" type="text" class="dfinput" value="<?=$PHP_DATE?>" style="width:150px;"/></li>
					
					<li><label>信息排序<b>*</b></label><input name="info[disorder]" type="text" class="dfinput" value="0" style="width:100px;"/> 注：数字越大越排在前</li>			
					
					<li><label>&nbsp;</label><input name="dosubmit" type="submit" class="btn" value="马上发布"/> <label>&nbsp;</label><input name="gosubmit" type="submit" class="btn" value="继续添加"/></li>
				
				</form>
				
			</ul>
		
		</div> 
	
	</div> 

</div>

</body>

</html>
