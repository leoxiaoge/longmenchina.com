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
$imgnum=get_imgnum($pid,$ty,$tty,$ttty);
$trcount=$imgnum;

if (isset($_POST['dosubmit'])) {
	
 	$path="../".$upload_picpath."/";
	
 	$data = new_addslashes($_POST['info']);
	$data = new_html_special_chars($data);
  	$data['sendtime'] = strtotime($_POST['info']['sendtime']);
  	$data['isstate'] = 1;
 	
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
<title>银行卡添加页面</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/select.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="js/checkform.js"></script>
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
					
					<li><label>银行<b>*</b></label><input name="info[title]" type="text" class="dfinput" value=""/></li>
					
					<? 
					$imgsize=get_toimgsize($pid,$ty,$tty,$ttty);
					$arrValue = explode(',',$imgsize);
					for ($i=1;$i<=1;$i++){
					?>
					
					<li><label>银行图片<b>*</b></label><input name="img<?=$i?>" type="file" id="img<?=$i?>"> <font color="#FF0000">图片大小:<?=$System_Picsize?>K内<? if($arrValue[$i-1]){?>,图片尺寸：<?=$arrValue[$i-1]?>px<? }?></font></li>
					<? }?>

					<li><label>创建时间<b>*</b></label><input name="info[sendtime]" type="text" class="dfinput" value="<?=$PHP_DATE?>" style="width:150px;"/></li>

					<li><label>&nbsp;</label><input name="dosubmit" type="submit" class="btn" value="马上发布"/></li>
				
				</form>
			
			</ul>
		
		</div> 

	</div> 

</div>

</body>

</html>
