<?
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';
$pid=(int)$_GET['pid'];
$path="../".$upload_picpath."/";

$sql="select disorder FROM `{$tablepre}news_cats` where pid={$pid} order by id desc";
$result=$db->sql_query($sql);
if($bd=$db->sql_fetchrow($result)){
	$disorder=$bd['disorder']+10;
}else{
	$disorder=10;
}

if (isset($_POST['dosubmit'])) {
	$img_name=$_FILES['img1']['name'];
	$imgtype=end(explode(".", basename($img_name))); 
	
	$sqlvalues="";
	$fields=$_POST['fields'];
	$fields['pid']=(int)$_POST['pid'];
	$fields['catname']=trim($_POST['catname']);
	$fields['catname2']=trim($_POST['catname2']);
	if((int)$_POST['showtype']) $fields['showtype']=(int)$_POST['showtype']; else  $fields['showtype']=(int)$_POST['hiddenshowtype'];
	$fields['disorder']=trim($_POST['disorder']);
	$fields['isstate']=1;
  	
	if($img_name){
		$imgnewname=date("YmdHis").mt_rand(10,99).".".$imgtype;
		uploadimg('img1',$path,$imgnewname);
		$fields['img1']=$imgnewname;
	}

	
	while(list ($key,$val) =each ($fields)){
		$sqlvalues.=",$key='$val'";
	}
	$sqlvalues=substr($sqlvalues,1);
	$sql="INSERT INTO `{$tablepre}news_cats` SET ".$sqlvalues;
	
	// exit($sql);
	
	if($db->sql_query($sql)){
		AddLog("添加栏目:{$fields['catname']}",$_SESSION['Admin_UserName']);
		
		JsSucce("操作成功！","news_cat.php");
	}else{
		JsError("操作失败！");
	}
	exit();
}
if($tty) $zid=$tty; elseif($ty)$zid=$ty;else $zid=$pid;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>栏目分类添加</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/select.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="js/checkform.js"></script>
</head>

<body>

<div class="place">
	<span>位置：</span>
	<ul class="placeul">
		<li><a href="mains.php">首页</a></li>
		<li>栏目管理</li>
		<li>栏目添加</li>
	</ul>
</div>
    
<div class="formbody">

	<div id="usual1" class="usual"> 
	
		<div class="itab">
			<ul> 
				<li><a href="#tab1" class="selected"><?=get_catname($zid,"news_cats")?></a></li> 
			</ul>
		</div> 
		
		<div id="tab1" class="tabson">
		
			<div class="formtext">Hi，<b><?=$_SESSION['Admin_UserName']?></b>，欢迎您使用信息发布功能！</div>
		
			<ul class="forminfo">
			
				<form name="form1" method="post" action="" onSubmit="return checkcats(this)" enctype="multipart/form-data">
			
					<input name="pid" type="hidden" value="<?=$zid?>">
					<input name="hiddenshowtype" type="hidden" value="<?=get_catname($zid,"news_cats","showtype")?>">
			
					<li><label>所属栏目<b>*</b></label><label style="font-weight:bold;"><?=get_catname($zid,"news_cats")?></label></li>
					<li><label>栏目栏目<b>*</b></label><input name="catname" type="text" class="dfinput" value=""/></li>
					
					<li><label>栏目英文<b>*</b></label><input name="catname2" type="text" class="dfinput" value=""/></li>
					
					<li><label>栏目图片<b>*</b></label><input name="img1" type="file" id="img1"><font color="#FF0000">图片大小:<?=$System_Picsize?>K内,图片尺寸：175 * 250px</font></li>
					
					<li><label>类别排序<b>*</b></label><input name="disorder" type="text" class="dfinput" value="<?=$disorder?>" style="width:100px;"/> 注：数字越小越排在前</li>
					<li><label>&nbsp;</label><input name="dosubmit" type="submit" class="btn" value="马上发布"/></li>
				
				</form>
				
			</ul>
		
		</div>
	   
	</div> 

</div>


</body>

</html>


<? if ($zid<>"0") {?>
<script language="JavaScript">
	document.form1.pid.value=<?=$zid?>;
</script>
<? }?>