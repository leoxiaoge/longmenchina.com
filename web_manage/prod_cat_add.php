<?
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';
$mtypeid=(int)opturl("mtypeid");
//树型结构类
require('./include/tree.class.php');
$tree = new tree;
$pid=(int)$_GET['pid'];
//------------------------------------------获取添加属性
//获得栏目组
$sql="SELECT id,pid,catname FROM `{$tablepre}prod_cats` ORDER BY disorder ASC, id ASC";
$result=$db->sql_query($sql);
$i=1;
while($bd=$db->sql_fetchrow($result)){
	$categorys_type[$i]['id']=$bd['id'];
	$categorys_type[$i]['pid']=$bd['pid'];
	$categorys_type[$i]['catname']=$bd['catname'];
	$categorys[$i]['Selected'] = ($bd['id']==$spid) ? 'selected' : '';
	$i++;
}

$str_type="<option value='\$id' \$Selected>\$spacer\$catname</option>\n";
$categorys_type = $tree->get_tree(0,$categorys_type,$str_type);
 

$sql="select disorder FROM `{$tablepre}prod_cats` order by id desc";
$result=$db->sql_query($sql);
if($bd=$db->sql_fetchrow($result)){
	$disorder=$bd['disorder']+10;
}else{
	$disorder=10;
}

if(isset($_POST['add'])){
	$path="../".$upload_picpath."/";
 	$img_name=$_FILES['img1']['name'];
	$imgtype=end(explode(".", basename($img_name))); 
	
	$img_name2=$_FILES['img2']['name'];
	$imgtype2=end(explode(".", basename($img_name2))); 
	
	$sqlvalues="";
	$fields=$_POST['fields'];
	$fields['typeid']=(int)$_POST['typeid'];
	$fields['pid']=(int)$_POST['pid'];
	$fields['catname']=trim($_POST['catname']);
 	$fields['disorder']=(int)$_POST['disorder'];
 	$fields['isgood']=(int)$_POST['isgood'];
  	$fields['isstate']=1;
  	
	if($img_name){
 		$imgnewname=date("YmdHis").mt_rand(10,99).".".$imgtype;
		uploadimg('img1',$path,$imgnewname);
		$fields['img1']=$imgnewname;
	}else{
		$fields['img1']="";
	}
 	
	if($img_name2){
 		$imgnewname2=date("YmdHis").mt_rand(10,88).".".$imgtype2;
		uploadimg('img2',$path,$imgnewname2);
		$fields['img2']=$imgnewname2;
	}else{
		$fields['img2']="";
	}
 	
	
	while(list ($key,$val) =each ($fields)){
		$sqlvalues.=",$key='$val'";
	}
	$sqlvalues=substr($sqlvalues,1);
	$sql="INSERT INTO `{$tablepre}prod_cats` SET ".$sqlvalues;
	
	//exit($sql);
	if($db->sql_query($sql)){
	
		AddLog("添加栏目分类",$_SESSION['Admin_UserName']);
		
		JsSucce("操作成功！","prod_cat.php?mtypeid=$mtypeid");
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
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.idTabs.min.js"></script>
</head>

<body>

<div class="place">
	<span>位置：</span>
	<ul class="placeul">
		<li><a href="mains.php">首页</a></li>
		<li><?=$webarr["mtypeid"][$mtypeid]?></li>
		<li>分类发布</li>
	</ul>
</div>

<div class="formbody">

	<div id="usual1" class="usual"> 
	
		<div class="itab">
			<ul> 
				<li><a href="#tab1" class="selected"><?=$webarr["mtypeid"][$mtypeid]?></a></li> 
			</ul>
		</div>

		<form name="form1" method="post" action="" onSubmit="return checkcats(this)" enctype="multipart/form-data">

			<input name="typeid" type="hidden" id="typeid" value="<?=$mtypeid?>">

			<div id="tab1" class="tabson">
			
				<div class="formtext">Hi，<b><?=$_SESSION['Admin_UserName']?></b>，欢迎您使用信息发布功能！</div>
			
				<ul class="forminfo">
	
					<? if($pid){?>
					<li><label>所属分类<b>*</b></label><div class="vocation">
					<select name="pid" class="select1">
						<option value="0">无（属一级栏目）</option>
						<?=$categorys_type?>
					</select></div></li>
					<? } ?> 

					<li><label>类别名称<b>*</b></label><input name="catname" type="text" class="dfinput" value=""/><? if($mtypeid==2111){?> <input name="isgood" type="checkbox" value="1" /> 推荐<? }?></li>

					<? if($mtypeid==2111){?>

					<li><label>配置图片<b>*</b></label><input name="img1" type="file" id="img1"> <font color="#FF0000">图片大小:<?=$System_Picsize?>K内,图片尺寸：100 * 100 px</font></li>

					<? }?>

					<li><label>类别排序<b>*</b></label><input name="disorder" type="text" class="dfinput" value="<?=$disorder?>" style="width:100px;"/> 注：数字越小越排在前</li>

				</ul>
			
			</div>

			<li><label>&nbsp;</label><input name="add" type="submit" class="btn" value="马上发布"/></li>

		</form>
	   
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

<? if ($zid<>"0") {?>
<script language="JavaScript">
	document.form1.pid.value=<?=$zid?>;
</script>
<? }?>