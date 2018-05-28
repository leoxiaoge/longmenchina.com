<?
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';
$filenames=basename($PHP_SELF,"_edit.php");

$id= (int)$_GET['id'];
$path="../".$upload_picpath."/";

$sql="select * FROM `{$tablepre}size` where id=".$id."";
$result=$db->sql_query($sql);
if($row=$db->sql_fetchrow($result)){
	$content=$row['content'];
	$pid=$row['pid'];
	$ty=$row['ty'];
	$tty=$row['tty'];
	$ttty=$row['ttty'];
 
	$gourl=get_tourl($pid,$ty,$tty,$ttty,"{$filenames}");
	$classname=get_toclassname($pid,$ty,$tty,$ttty);
	$cname=get_cname($pid,$ty,$tty,$ttty);
}

if (isset($_POST['dosubmit'])) {

  	$data = new_addslashes($_POST['info']);
	$data = new_html_special_chars($data);
  	$data['sendtime'] = strtotime($_POST['info']['sendtime']);

	for ($i=1;$i<=1;$i++){
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

 	$insertid=$db->update($data,"size"," id={$id}");
	//exit($sql);
 	if($insertid) {
 		AddLog("编辑{$cname}栏目内容",$_SESSION['Admin_UserName']);
 		JsSucce("操作成功！",$gourl);
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
<title>现金抵用券发布页面</title>
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
		<li>信息编辑</li>
	</ul>
</div>
    
<div class="formbody">
	 
	<div id="usual1" class="usual"> 
	
		<div id="tab1" class="tabson">
		
		<div class="formtext">Hi，<b><?=$_SESSION['Admin_UserName']?></b>，欢迎您使用信息发布功能！</div>
		
			<ul class="forminfo">
				
				<form action="" method="post" name="formlist" enctype="multipart/form-data" onSubmit="return check_link(this);">
			  
					<input type="hidden" name="referer" value="<?=$indexurl?>">
					<input type="hidden" name="info[pid]" value="<?=$pid?>">

					<li>
						<label>信息标题<b>*</b></label>
						<input name="info[title]" type="text" class="dfinput" value="<?=$row['title']?>" style=" width: 500px;"/>
					</li>
					
					<? 
					$imgsize=get_toimgsize($pid,$ty,$tty,$ttty);
					$arrValue = explode(',',$imgsize);
					for ($i=1;$i<=1;$i++){ 
						if($i==1)
							$s="配置图片";
						elseif($i==2)
							$s="产品图片";
					?>
					<li><label><?=$s?><b>*</b></label><? 
					$showimg_more="img{$i}";
					if ($row[$showimg_more]==""){
						echo "<input name=\"{$showimg_more}\" type=\"file\" id=\"{$showimg_more}\">";
					}else{
						echo"<input name=\"{$showimg_more}\" type=\"file\" id=\"{$showimg_more}\"> <a href=\"{$path}{$row[$showimg_more]}\" target=\"_blank\">查看图片</a>";
					}
					?> <font color="#FF0000">图片大小:<?=$System_Picsize?>K内</font></li>

					<? }
					?>
					
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
