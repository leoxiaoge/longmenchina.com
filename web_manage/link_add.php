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
	
	$img_name=$_FILES['img1']['name'];
	$imgtype=end(explode(".", basename($img_name))); 
  	
	$data = new_addslashes($_POST['info']);
	$data = new_html_special_chars($data);
  	$data['sendtime'] = strtotime($_POST['info']['sendtime']);
  	$data['isstate'] = 1;
 	
	if($img_name){
 		$imgnewname=date("YmdHis").mt_rand(10,99).".".$imgtype;
		uploadimg('img1',$path,$imgnewname);
		$data['img1']=$imgnewname;
	}else{
		$data['img1']="";
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
<title>信息添加页面</title>
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

				<form action="" method="post" name="formlist" enctype="multipart/form-data"  onSubmit="return check_link(this);">
					<input type="hidden" name="info[pid]" value="<?=$pid?>">
					<input type="hidden" name="info[ty]" value="<?=$ty?>">
					<input type="hidden" name="info[tty]" value="<?=$tty?>">
					<input type="hidden" name="info[ttty]" value="<?=$ttty?>">

					<? if($pid==4){ ?>

					<li><label>信息标题<b>*</b></label><input name="info[title]" type="text" class="dfinput" value=""/></li>

					<? }else{ ?>

					<li><label>信息标题<b>*</b></label><input name="info[title]" type="text" class="dfinput" value=""/></li>

					<li><label>链接地址<b>*</b></label><input name="info[linkurl]" type="text" class="dfinput" value=""/></li>

					<? } ?>

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
