<?
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';

//树型结构类
require('./include/tree.class.php');
$tree = new tree;

//------------------------------------------获取添加属性
//获得栏目组
$sql="SELECT id,pid,catname FROM `{$tablepre}news_cats` ORDER BY disorder ASC, id ASC";
$result=$db->sql_query($sql);
$i=1;
while($bd=$db->sql_fetchrow($result)){
	$categorys_type[$i]['id']=$bd['id'];
	$categorys_type[$i]['pid']=$bd['pid'];
	$categorys_type[$i]['catname']=$bd['catname'];
	$categorys[$i]['Selected'] = ($bd['id']==$sPid) ? 'selected' : '';
	$i++;
}


$str_type="<option value='\$id' \$Selected>\$spacer\$catname</option>\n";
$categorys_type = $tree->get_tree(0,$categorys_type,$str_type);

$id= (int)$_GET['id'];
$sql="select * FROM `{$tablepre}news_cats` where id=".$id."";
//echo $sql;
$result=$db->sql_query($sql);
$row=$db->sql_fetchrow($result);

if(isset($_POST['update']))
{
	$sqlvalues="";
	$fields=$_POST['fields'];
	$fields['pid']=(int)$_POST['pid'];
	$fields['catname']=trim($_POST['catname']);
	$fields['catname2']=trim($_POST['catname2']);
	$fields['linkurl']=trim($_POST['linkurl']);
 	$fields['disorder']=(int)$_POST['disorder'];
	$fields['seotitle']=trim($_POST['seotitle']);
	$fields['seokeywords']=trim($_POST['seokeywords']);
	$fields['seodescription']=trim($_POST['seodescription']);
	$fields['seodescription']=trim($_POST['seodescription']);
	$fields['imgsize']=trim($_POST['imgsize']);
	$fields['imgnum']=(int)$_POST['imgnum'];
	
	$fields['iscats']=(int)$_POST['iscats'];
	$fields['showtype']=(int)$_POST['showtype'];
	$fields['linkurl']=trim($_POST['linkurl']);
	$fields['weblinkurl']=trim($_POST['weblinkurl']);
  	
	while(list ($key,$val) =each ($fields)){
		$sqlvalues.=",$key='$val'";
	}
	$sqlvalues=substr($sqlvalues,1);
	$sql="update `{$tablepre}news_cats` SET ".$sqlvalues." where id=".$id."";
	//exit($sql);
	if($db->sql_query($sql)){
		AddLog("编辑栏目分类",$_SESSION['Admin_UserName']);
		
		JsSucce("操作成功！","class_cat.php");
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
    <li>栏目管理</li>
    <li>栏目添加</li>
	</ul>
    </div>
    
    <div class="formbody">
    
    
		<div id="usual1" class="usual"> 
		
		<div class="itab">
		<ul> 
		<li><a href="#tab1" class="selected">栏目管理</a></li> 
		<li><a href="#tab2">SEO设置</a></li> 
		</ul>
		</div> 
			<form name="form1" method="post" action="" onSubmit="return checkcats(this)">
			<div id="tab1" class="tabson">
			
			<div class="formtext">Hi，<b><?=$_SESSION['Admin_UserName']?></b>，欢迎您使用信息发布功能！</div>
			
				<ul class="forminfo">
					<li><label>所属分类<b>*</b></label><div class="vocation"><select name="pid" class="select1">
								<option value="0">无（属一级栏目）</option>
								<?=$categorys_type?>
							  </select></div></li>
					<li><label>类别名称<b>*</b></label><input name="catname" type="text" class="dfinput" value="<?=$row['catname']?>"/></li>
					<li><label>英文名称<b>*</b></label><input name="catname2" type="text" class="dfinput" value="<?=$row['catname2']?>"/></li>
 				
					
 					<li><label>显示方式<b>*</b></label><cite><?=frm_out_put($webarr["showtype"],"showtype","radio",$row['showtype'],"","")?></cite></li>
					<li><label>图片数量<b>*</b></label><input name="imgnum" type="text" class="dfinput" value="<?=$row['imgnum']?>"/></li>
					<li><label>图片尺寸<b>*</b></label><input name="imgsize" type="text" class="dfinput" value="<?=$row['imgsize']?>"/> 多个用,分割</li>
					<li><label>内部跳链<b>*</b></label><input name="linkurl" type="text" class="dfinput" value="<?=$row['linkurl']?>"/></li>
					<li><label>外部跳链<b>*</b></label><input name="weblinkurl" type="text" class="dfinput" value="<?=$row['weblinkurl']?>"/></li>
					<li><label>开放分类<b>*</b></label><cite><?=frm_out_put($webarr["iscats"],"iscats","radio",$row['iscats'],"","")?></cite></li>
 					
					<li><label>类别排序<b>*</b></label><input name="disorder" type="text" class="dfinput" value="<?=$row['disorder']?>" style="width:100px;"/> 注：数字越小越排在前</li>
				</ul>
			
			</div> 
		
			<div id="tab2" class="tabson">
				<ul class="forminfo">
						<li><label>页面标题<b>*</b></label><input name="seotitle" type="text" class="dfinput" value="<?=$row['seotitle']?>"/></li>
						<li><label>页面关键字<b>*</b></label><input name="keywords" type="text" class="dfinput" value="<?=$row['keywords']?>"/></li>
						<li><label>页面描述<b>*</b></label><input name="description" type="text" class="dfinput" value="<?=$row['description']?>"/></li>
 				</ul>
			</div>  
		   
			<li><label>&nbsp;</label><input name="update" type="submit" class="btn" value="马上修改"/></li>
			</form>
		   
		</div> 
		
		<script type="text/javascript"> 
		  $("#usual1 ul").idTabs(); 
		</script>
		
		<script type="text/javascript">
		$('.tablelist tbody tr:odd').addClass('odd');
		</script>
		
    
    </div>
<? if ($row['pid']<>"0") {?>
<script language="JavaScript">
	document.form1.pid.value=<?=$row['pid']?>;
</script>
<? }?>

</body>

</html>
