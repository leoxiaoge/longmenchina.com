<?php

if(!defined('IN_COPY')) {
	exit('Access Denied');
}

//在线客服
function get_index_qq_list() {
 	global $db,$tablepre,$srt; 
	$sql= "SELECT * FROM `{$tablepre}qq` order by id asc limit 3";
 	//echo $sql;
 	$result=$db->sql_query($sql);
	$i=0;
 	while($bd=$db->sql_fetchrow($result)){
		$i++;
 		$str.="<li class=\"online_qq\">
				<a target=\"_blank\" href=\"http://wpa.qq.com/msgrd?v=3&uin={$bd['qq']}&site=qq&menu=yes\">
				 <img border=\"0\" src=\"http://wpa.qq.com/pa?p=2:{$bd['qq']}:51\" alt=\"点击这里给我发消息\" title=\"点击这里给我发消息\" /></a></li>";
 	}
 	return $str;
 	unset($sql,$result,$bd);
}

//列表页rewrite
function rt($pid,$ty,$ok,$url){
	if($ok==1) $basename=".html";else $basename=".php";
	if($ok){
		if($pid&&$ty){
			$mystr="{$url}_{$pid}_{$ty}{$basename}";
		}elseif($pid){
			$mystr="{$url}_{$pid}{$basename}";
		}else{
			$mystr="{$url}{$basename}";
		}
	}else{
		if($pid&&$ty){
			$mystr="{$url}{$basename}?pid={$pid}&ty={$ty}";
		}elseif($pid){
			$mystr="{$url}{$basename}?pid={$pid}";
		}else{
			$mystr="{$url}{$basename}";
		}
	}
	return $mystr;
	unset($pid,$ty,$ok,$url,$mystr);
}

function get_gurl($ty){
	$num=get_count("news"," and ty={$ty}");
	$str="city.php?pid=4&ty={$ty}";
	return $str;
}

//内容页rewrite
function vrt($id,$ok,$url){
	if($ok==1) $basename=".html";else $basename=".php";
	if($ok){
		$mystr="{$url}_{$id}{$basename}";
	}else{
		$mystr="{$url}{$basename}?id={$id}";
	}
	return $mystr;
	unset($pid,$ty,$ok,$url,$mystr);
}
/**
* 【功能】过滤HTML代码2
* 【参数】$fString 传过来的字符串 
* 【返回值】 String
* */
function HTMLEncode($fString){
    $fString = str_replace(">", "&gt;", $fString);
    $fString = str_replace("<", "&lt;", $fString);
    $fString = str_replace(chr(32), "&nbsp;", $fString);
    $fString = str_replace(chr(9), "&nbsp;", $fString);
    $fString = str_replace(chr(34),"&quot;", $fString);
    $fString = str_replace(chr(39), "&#39;" ,$fString );
    $fString = str_replace("", chr(13),$fString);
    $fString = str_replace("</P><P>", chr(13) & chr(10),$fString);
    $fString = str_replace(chr(10), "<BR /> ", $fString);
    return $fString;
}

//作用：询价数量统计
if(!function_exists('get_count')){
	function get_count($table="ask",$where) {
		global $db,$tablepre; 
 		$sqls= "SELECT count(*) as counts FROM `{$tablepre}{$table}` where 1=1 {$where}";
		//echo $sqls;
    	$results=$db->sql_query($sqls);
		if($rows=$db->sql_fetchrow($results)){
			$strs=(int)$rows['counts'];
		}
		return $strs;
	}
	unset($sqls,$results,$strs);
}

//作用：平均值
if(!function_exists('get_sum')){
	function get_sum($uid,$jg="totalprice") {
		global $db,$tablepre; 
 		$sql= "SELECT sum({$jg}) as price FROM `{$tablepre}orders` where ispay=1 AND uid={$uid}";
		// exit($sql);
    	$result=$db->sql_query($sql);
		if($row=$db->sql_fetchrow($result)){
			$str=$row['price'];
		}
		return $str;
	}
	unset($sql,$result,$str);
}
 
//作用：平均值
if(!function_exists('get_avg')){
	function get_avg($table="message",$where) {
		global $db,$tablepre; 
 		$sql= "SELECT AVG(score) as counts FROM `{$tablepre}{$table}` where 1=1 {$where}";
		// exit($sql);
    	$result=$db->sql_query($sql);
		if($row=$db->sql_fetchrow($result)){
			$str=$row['counts'];
		}
		return $str;
	}
	unset($sql,$result,$str);
}
 
//作用：获取新闻详细内容
if(!function_exists('get_detail_view')){
	function get_detail_view($id,$action,$table="news"){
	global $db,$tablepre; 
		if($action) $sqlstr=" "; else $sqlstr=" and isstate=1";
		$sql= "SELECT * FROM `{$tablepre}{$table}` WHERE id={$id}{$sqlstr}";
		//echo $sql;
		$result=$db->sql_query($sql);
		$arr=$db->sql_fetchrow($result);
		return $arr;
	}
	unset($sql,$result,$cachekey,$arr);
}

//id 格式：获取当前分类的第一子分类id
function get_newsid($pid=2,$ty=10) {
	global $db,$tablepre; 
 	if($pid) $sqlkey=" AND pid={$pid}";
	if($ty) $sqlkey.=" AND ty={$ty}";
	
	$sql= "SELECT id FROM `{$tablepre}news` WHERE isstate=1 ".$sqlkey." ORDER BY disorder desc, id asc";
	//echo $sql;
	$result=$db->sql_query($sql);
	if($bd=$db->sql_fetchrow($result)){
 		return (int)$bd['id'];
	}else{
		return "0";
	}
	unset($sql,$result,$cachekey,$arr);
}
 
//id 格式：获取当前分类的第一子分类id
function get_nextid($pid,$table="news_cats") {
	global $db,$tablepre; 
	$sql= "SELECT id FROM `{$tablepre}{$table}` WHERE isstate=1 AND pid=".$pid." ORDER BY disorder ASC, id ASC";
	$result=$db->sql_query($sql);
	if($bd=$db->sql_fetchrow($result)){
 		return (int)$bd['id'];
	}else{
		return "0";
	}
	unset($sql,$result,$cachekey,$arr);
}

//根据条件获取内容
if(!function_exists('get_zd_name')){
	function get_zd_name($zd,$table='users',$where) {
	global $db,$tablepre; 
		$sql= "SELECT {$zd} FROM `{$tablepre}{$table}` WHERE 1=1 {$where}";
		//echo $sql;
 		$result=$db->sql_query($sql);
		if($arr=$db->sql_fetchrow($result)){
			return $arr[$zd];
		} 
	}
	unset($sql,$result,$arr);
}

//id 格式：获取当前分类的第一子分类id
function get_prod_nextid($typeid,$table="prod_cats") {
	global $db,$tablepre; 
	$sql= "SELECT id FROM `{$tablepre}{$table}` WHERE isstate=1 AND typeid=".$typeid." ORDER BY disorder ASC, id ASC";
	$result=$db->sql_query($sql);
	if($bd=$db->sql_fetchrow($result)){
 		return (int)$bd['id'];
	}else{
		return "0";
	}
	unset($sql,$result,$cachekey,$arr);
}

//作用：获取类别名称
if(!function_exists('get_catname')){
	function get_catname($pid,$zd="catname",$table="news_cats") {
	global $db,$tablepre; 
		$sql= "SELECT {$zd} FROM `{$tablepre}{$table}` WHERE isstate=1 and id=".$pid;
 		// echo $sql;
 		$result=$db->sql_query($sql);
		$arr=$db->sql_fetchrow($result);
		return $arr[$zd];
	}
	unset($sql,$result,$cachekey,$arr);
}

//作用：获取类别名称
if(!function_exists('get_catname2')){
	function get_catname2($pid,$zd="catname2",$table="news_cats") {
	global $db,$tablepre; 
		$sql= "SELECT {$zd} FROM `{$tablepre}{$table}` WHERE isstate=1 and id=".$pid;
 		// echo $sql;
 		$result=$db->sql_query($sql);
		$arr=$db->sql_fetchrow($result);
		return $arr[$zd];
	}
	unset($sql,$result,$cachekey,$arr);
}

//获取左侧导航栏目加高亮选中
function get_header_cats_list($pid=2,$url="list.php",$num=100) {
	global $db,$tablepre,$srt; 
	$sql= "SELECT * FROM `{$tablepre}news_cats` WHERE pid={$pid} AND isstate=1 order by disorder Asc,id ASC limit 0,$num";
	 //echo $sql;
  	$result=$db->sql_query($sql);
	$i=0;
  	while($bd=$db->sql_fetchrow($result)){
		$i++;
		if($bd['weblinkurl']) $surl=$bd['weblinkurl'];else $surl="{$url}?pid={$bd['pid']}&ty={$bd['id']}";
 		if($ty==$bd['id']) $s="on";else $s="";
		
 		$str.="<li><a href=\"{$surl}\">{$bd['catname']}<i class=\"\"></i></a></li>";
   	}
 	return $str;
	unset($sql,$result,$bd);
}

//获取左侧导航栏目加高亮选中
function get_nav_cats_list($pid=2,$ty,$url="list.php",$num=100) {
	global $db,$tablepre,$srt; 
	$sql= "SELECT * FROM `{$tablepre}news_cats` WHERE pid={$pid} AND isstate=1 order by disorder Asc,id ASC limit 0,$num";
	 //echo $sql;
  	$result=$db->sql_query($sql);
	$i=0;
  	while($bd=$db->sql_fetchrow($result)){
		$i++;
		if($bd['weblinkurl']) $surl=$bd['weblinkurl'];else $surl="{$url}?pid={$bd['pid']}&ty={$bd['id']}";
 		if($ty==$bd['id']) $s="act_lm";else $s="";
		
 		$str.="<a href=\"{$surl}\" class=\"{$s}\">{$bd['catname']}</a>";
   	}
 	return $str;
	unset($sql,$result,$bd);
}

//获取单页面单一内容
function get_single_content($pid,$ty,$tty,$c="k_cc pb10",$len) {
	global $db,$tablepre,$srt; 
 	if($pid) $sqlkey=" AND pid={$pid}";
 	if($ty) $sqlkey.=" AND ty={$ty}";
 	if($tty) $sqlkey.=" AND tty={$tty}";
	
 	$sql= "SELECT content FROM `{$tablepre}news` WHERE 1=1 ".$sqlkey." order by id Desc limit 1";
 	  //echo $sql;
 	$result=$db->sql_query($sql);
	if($bd=$db->sql_fetchrow($result)){
		if($len){
			$content=cutstr(strip_tags($bd['content']),$len);
			$str.=$content;
		}else{
 			$str.="".new_stripslashes(new_html_entity_decode($bd['content']))."";
		}
	}else{
		$str="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Content update...";
	}
	return $str;
	unset($sql,$result,$bd);
}

//服务项目
function get_news_time_list($pid=11,$ty=37,$num=3){
	global $db,$upload_picpath,$tablepre,$srt; 
	if($pid) $sqlkey=" AND pid={$pid}";
	if($ty) $sqlkey.=" AND ty={$ty}";
 
	$sql= "SELECT * FROM `{$tablepre}news` WHERE isstate=1 ".$sqlkey." order by disorder desc,id asc limit 0,$num";
 	$result=$db->sql_query($sql);
 	while($bd=$db->sql_fetchrow($result)){
		if(!empty($bd['img1'])) $Img=$bd['img1']; else $Img="nopic.jpg";
		$url=vrt($bd['id'],$srt,"view");
 		$str.="
		<a href=\"{$url}\" title=\"{$bd['title']}\">
			{$bd['title']}
		</a>\n";
	}
	return $str;
	unset($sql,$result,$bd);
}

//服务项目
function get_news_pic_list($pid=11,$ty=37,$num=3){
	global $db,$upload_picpath,$tablepre,$srt; 
	if($pid) $sqlkey=" AND pid={$pid}";
	if($ty) $sqlkey.=" AND ty={$ty}";
 
	$sql= "SELECT * FROM `{$tablepre}news` WHERE isstate=1 ".$sqlkey." order by disorder desc,id asc limit 0,$num";
 	$result=$db->sql_query($sql);
 	while($bd=$db->sql_fetchrow($result)){
		if(!empty($bd['img1'])) $Img=$bd['img1']; else $Img="nopic.jpg";
		$url=vrt($bd['id'],$srt,"view");
 		$str.="
		<li class=\"fl\">
			<a href=\"{$url}\">
				<img src=\"./{$upload_picpath}/{$Img}\" alt=\"{$bd['title']}\"/>
				<i></i>
				<div class=\"txt\">
					<p class=\"tit\">{$bd['title']}</p>
					{$bd['seodescription']}
				</div>
			</a>
		</li>\n";
	}
	return $str;
	unset($sql,$result,$bd);
}

//服务项目
function get_news_pic_index($pid=11,$ty=37,$num=3){
	global $db,$upload_picpath,$tablepre,$srt; 
	if($pid) $sqlkey=" AND pid={$pid}";
	if($ty) $sqlkey.=" AND ty={$ty}";
 
	$sql= "SELECT * FROM `{$tablepre}news` WHERE isstate=1 ".$sqlkey." order by disorder desc,id asc limit 0,$num";
 	$result=$db->sql_query($sql);
 	while($bd=$db->sql_fetchrow($result)){
		if(!empty($bd['img1'])) $Img=$bd['img1']; else $Img="nopic.jpg";
		$url=vrt($bd['id'],$srt,"view");
		$sendtime=date("Y",$bd['sendtime']);
		$sendtime2=date("m.d",$bd['sendtime']);
		$nr=cutstr_html($bd['seodescription'],250);

 		$str.="
		
		<li class=\"cle\">
			<a href=\"{$url}\" class=\"cle\">
				<img src=\"./{$upload_picpath}/{$Img}\" style=\" width:285px;\" alt=\"{$bd['title']}\"/>
				<div class=\"fr dect\">
					<p class=\"t\">{$bd['title']}</p>
					<p class=\"info\">{$bd['ftitle']}</p>
					<div class=\"cont cle\">
						<div class=\"g1 fl\">{$nr}</div>
						<div class=\"fl time\">
							<p>{$sendtime2}</p>
							{$sendtime}
						</div>
					</div>
				</div>
				<span class=\"i\"></span>
			</a>
		</li>\n";
	}
	return $str;
	unset($sql,$result,$bd);
}

//新闻动态
function get_news_pic_list_fy($pid=3,$ty,$pagesize=9,$len=300) {
	global $db,$upload_picpath,$tablepre,$srt; 
	$page=(int)$_GET['page']==0?1:(int)$_GET['page'];

	if($pid) $sqlkey=" AND pid={$pid}";
	if($ty) $sqlkey.=" AND ty={$ty}";

	$sql= "SELECT * FROM `{$tablepre}news` WHERE isstate=1 ".$sqlkey." order by disorder desc,id desc";
 	$pagestr=page_list($sql,$page,$pagesize);
	$sql.=" limit ".(($page-1)*$pagesize).",$pagesize";	
	//echo $sql;
	$result=$db->sql_query($sql);
	$PageCount=$db->sql_numrows($result);
	
	$str.="<ul>";
			  
	while($bd=$db->sql_fetchrow($result)){
 		if(!empty($bd['img1'])) $Img=$bd['img1']; else $Img="nopic.jpg";
		$url=vrt($bd['id'],$srt,"view");
		$sendtime=date("Y",$bd['sendtime']);
		$sendtime2=date("m-d",$bd['sendtime']);
		$nr=cutstr_html($bd['seodescription'],16);
		$bt=cutstr($bd['title'],50);

		$str.="\n
		<li>
			<div class=\"box w html5_w90 cle\">
				<img src=\"./{$upload_picpath}/{$Img}\" alt=\"{$bd['title']}\" class=\"fl\" />
				<div class=\"fr dect\">
					<p class=\"t txtov\">{$bd['title']}</p>
					<div class=\"c\">{$nr}</div>
					<div class=\"info cle\">
						<div class=\"time fl\"><p>{$sendtime2}</p>{$sendtime}</div>
						<a href=\"{$url}\" class=\"fr hr_a\">了解更多》</a>
					</div>
				</div>
			</div>
		</li>";
	}
	
	$str.="</ul>{$pagestr}";
	
 	return $str;
	unset($sql,$result,$bd);
}

//新闻动态
function get_news_pic_list_fy2($key,$pid=3,$ty,$pagesize=6,$len=300) {
	global $db,$upload_picpath,$tablepre,$srt; 
	$page=(int)$_GET['page']==0?1:(int)$_GET['page'];

	if($pid) $sqlkey=" AND pid={$pid}";
	if($ty) $sqlkey.=" AND ty={$ty}";
	if ($key) $sqlkey.=" AND title like '%".$key."%'";

	$sql= "SELECT * FROM `{$tablepre}news` WHERE isstate=1 ".$sqlkey." order by disorder desc,id desc";
 	$pagestr=page_list($sql,$page,$pagesize);
	$sql.=" limit ".(($page-1)*$pagesize).",$pagesize";	
	//echo $sql;
	$result=$db->sql_query($sql);
	$PageCount=$db->sql_numrows($result);
	
	$str.="<ul class=\"w html5_w90 cle\">";
			  
	while($bd=$db->sql_fetchrow($result)){
 		if(!empty($bd['img1'])) $Img=$bd['img1']; else $Img="nopic.jpg";
		$url=vrt($bd['id'],$srt,"view");
		$sendtime=date("Y-m-d",$bd['sendtime']);
		$nr=cutstr_html($bd['seodescription'],50);
		$bt=cutstr($bd['title'],50);

		$str.="\n
		<li class=\"fl\">
			<a href=\"{$url}\">
				<img src=\"./{$upload_picpath}/{$Img}\" alt=\"{$bd['title']}\" width=\"100%\" />
				<div class=\"c\">
					<p class=\"t\">{$bd['title']}</p>
					<div class=\"dect\">{$nr}</div>
					<div class=\"time\">{$sendtime}</div>
				</div>
			</a>
		</li>";
	}
	
	$str.="</ul>{$pagestr}";
	
 	return $str;
	unset($sql,$result,$bd);
}

//新闻动态
function get_linkurl_pic_list_fy($pid=3,$ty,$pagesize=20,$len=300) {
	global $db,$upload_picpath,$tablepre,$srt; 
	$page=(int)$_GET['page']==0?1:(int)$_GET['page'];

	if($pid) $sqlkey=" AND pid={$pid}";
	if($ty) $sqlkey.=" AND ty={$ty}";

	$sql= "SELECT * FROM `{$tablepre}news` WHERE isstate=1 ".$sqlkey." order by disorder desc,id desc";
 	$pagestr=page_list($sql,$page,$pagesize);
	$sql.=" limit ".(($page-1)*$pagesize).",$pagesize";	
	//echo $sql;
	$result=$db->sql_query($sql);
	$PageCount=$db->sql_numrows($result);
	
	$str.="<ul class=\"cle w html5_w90\">";
			  
	while($bd=$db->sql_fetchrow($result)){
 		if(!empty($bd['img1'])) $Img=$bd['img1']; else $Img="nopic.jpg";
		$url=vrt($bd['id'],$srt,"view");
		$sendtime=date("Y",$bd['sendtime']);
		$sendtime2=date("m-d",$bd['sendtime']);
		$nr=cutstr_html($bd['seodescription'],16);
		$bt=cutstr($bd['title'],50);

		$str.="\n
		<li class=\"fl\">
			<img src=\"./{$upload_picpath}/{$Img}\" alt=\"{$bd['title']}\" width=\"100%\">
		</li>";
	}
	
	$str.="</ul>{$pagestr}";
	
 	return $str;
	unset($sql,$result,$bd);
}

//产品列表
function get_prod_pic_list_fy($pid=3,$ty=0,$tty=0,$desc,$pagesize=50,$len=46) {
	global $db,$upload_picpath,$tablepre,$srt; 
	$page=(int)$_GET['page']==0?1:(int)$_GET['page'];
   	
	if ($key){
		$sqlkey.=" AND title like '%".$key."%'";
  	}else{
		if($pid) $sqlkey.=" AND pid={$pid}";
		if($ty) $sqlkey.=" AND ty={$ty}";
		if($tty) $sqlkey.=" AND tty={$tty}";
	}
	if($desc) $d.=$desc; else $d.="disorder";
	
	$sql= "SELECT * FROM `{$tablepre}news` WHERE isstate=1 ".$sqlkey." order by ".$d." desc,id desc";
 	$pagestr=page_list($sql,$page,$pagesize);

	$sql.=" limit ".(($page-1)*$pagesize).",$pagesize";	
	//echo $sql;
	$result=$db->sql_query($sql);
	$PageCount=$db->sql_numrows($result);

	$str.="<ul class=\"proadvul clearfix\">";

	while($bd=$db->sql_fetchrow($result)){
 		if(!empty($bd['img1'])) $Img=$bd['img1']; else $Img="nopic.jpg";
		$url=vrt($bd['id'],$srt,"view");
		$nr=cutstr_html($bd['seodescription'],16);
		$bt=cutstr($bd['title'],50);

		$str.="\n
		<li>
			<a href=\"{$url}\" title=\"{$bd['title']}\">
				<span class=\"tp\"><img src=\"./{$upload_picpath}/{$Img}\" alt=\"{$bd['title']}\"/></span>
				<div class=\"des\">
					<h4 class=\"tit\">{$bd['title']}</h4>
				</div>
			</a>
		</li>\n";		
	}
	
	$str.="</ul>";
	
 	return $str;
	unset($sql,$result,$bd);
}

//友情链接 
function get_link_list($pid=7,$ty=18,$num=20) {
	global $db,$tablepre,$srt; 
	if($pid) $sqlkey=" AND pid={$pid}";
	if($ty) $sqlkey.=" AND ty={$ty}";

	$sql= "SELECT * FROM `{$tablepre}news` WHERE isstate=1 ".$sqlkey." order by disorder desc,id asc limit 0,$num";
	// echo $sql;
 	$result=$db->sql_query($sql);
  	while($bd=$db->sql_fetchrow($result)){
   		$str.="<a href=\"{$bd['linkurl']}\" target=\"_blank\">{$bd['title']}, </a>";
	}
	return $str;
	unset($sql,$result,$bd);
}

//服务项目
function get_link_img_list($pid=11,$ty=37,$num=3){
	global $db,$upload_picpath,$tablepre,$srt; 
	if($pid) $sqlkey=" AND pid={$pid}";
	if($ty) $sqlkey.=" AND ty={$ty}";
 
	$sql= "SELECT * FROM `{$tablepre}news` WHERE isstate=1 and img1<>'' ".$sqlkey." order by disorder desc,id asc limit 0,$num";
 	$result=$db->sql_query($sql);
 	while($bd=$db->sql_fetchrow($result)){
		if(!empty($bd['img1'])) $Img=$bd['img1']; else $Img="nopic.jpg";
		$url=vrt($bd['id'],$srt,"view");
		

 		$str.="
		<li>
			<img src=\"./{$upload_picpath}/{$Img}\" alt=\"{$bd['title']}\"/>
			<p class=\"t\">".new_stripslashes(new_html_entity_decode($bd['title']))."</p>
			<div class=\"t1\">
				{$bd['seodescription']}
			</div>
		</li>\n";
	}
	return $str;
	unset($sql,$result,$bd);
}

//服务项目
function get_link_img_list2($pid=11,$ty=37,$num=5){
	global $db,$upload_picpath,$tablepre,$srt; 
	if($pid) $sqlkey=" AND pid={$pid}";
	if($ty) $sqlkey.=" AND ty={$ty}";
 
	$sql= "SELECT * FROM `{$tablepre}news` WHERE isstate=1 ".$sqlkey." order by disorder desc,id asc limit 0,$num";
 	$result=$db->sql_query($sql);
	$i=0;
 	while($bd=$db->sql_fetchrow($result)){
		$i++;
		if(!empty($bd['img1'])) $Img=$bd['img1']; else $Img="nopic.jpg";
		$url=vrt($bd['id'],$srt,"view");

 		$str.="<div class=\"i{$i}\">{$bd['title']}</div>\n";

	}
	return $str;
	unset($sql,$result,$bd);
}

//服务项目
function get_link_img_list3($pid=11,$ty=37,$num=5){
	global $db,$upload_picpath,$tablepre,$srt; 
	if($pid) $sqlkey=" AND pid={$pid}";
	if($ty) $sqlkey.=" AND ty={$ty}";
 
	$sql= "SELECT * FROM `{$tablepre}news` WHERE isstate=1 ".$sqlkey." order by disorder desc,id asc limit 0,$num";
 	$result=$db->sql_query($sql);
	$i=0;
 	while($bd=$db->sql_fetchrow($result)){
		$i++;
		if(!empty($bd['img1'])) $Img=$bd['img1']; else $Img="nopic.jpg";
		$url=vrt($bd['id'],$srt,"view");

 		$str.="
		<li>
			<span>{$bd['title']}</span>
			<p>0{$i}</p>
		</li>\n";

	}
	return $str;
	unset($sql,$result,$bd);
}

//服务项目
function get_link_img_list4($pid=11,$ty=37,$num=3){
	global $db,$upload_picpath,$tablepre,$srt; 
	if($pid) $sqlkey=" AND pid={$pid}";
	if($ty) $sqlkey.=" AND ty={$ty}";
 
	$sql= "SELECT * FROM `{$tablepre}news` WHERE isstate=1 and img1<>'' ".$sqlkey." order by disorder desc,id asc limit 0,$num";
 	$result=$db->sql_query($sql);
 	while($bd=$db->sql_fetchrow($result)){
		if(!empty($bd['img1'])) $Img=$bd['img1']; else $Img="nopic.jpg";
		$url=vrt($bd['id'],$srt,"view");
		

 		$str.="
		<li class=\"fl\">
			<img src=\"./{$upload_picpath}/{$Img}\" alt=\"{$bd['title']}\" />
		</li>\n";
	}
	return $str;
	unset($sql,$result,$bd);
}

//服务项目
function get_link_img_list5($pid=11,$ty=37,$num=3){
	global $db,$upload_picpath,$tablepre,$srt; 
	if($pid) $sqlkey=" AND pid={$pid}";
	if($ty) $sqlkey.=" AND ty={$ty}";
 
	$sql= "SELECT * FROM `{$tablepre}news` WHERE isstate=1 and img1<>'' ".$sqlkey." order by disorder desc,id asc limit 0,$num";
 	$result=$db->sql_query($sql);
 	while($bd=$db->sql_fetchrow($result)){
		if(!empty($bd['img1'])) $Img=$bd['img1']; else $Img="nopic.jpg";
		$url=vrt($bd['id'],$srt,"view");
		

 		$str.="
		<li class=\"fl\">
			<img src=\"./{$upload_picpath}/{$Img}\" alt=\"{$bd['title']}\" width=\"100%\">
		</li>\n";
	}
	return $str;
	unset($sql,$result,$bd);
}

//服务项目
function get_link_img_list6($pid=11,$ty,$tty,$num=3){
	global $db,$upload_picpath,$tablepre,$srt; 
	if($pid) $sqlkey=" AND pid={$pid}";
	if($ty) $sqlkey.=" AND ty={$ty}";
 	if($tty) $sqlkey.=" AND tty={$tty}";
 
	$sql= "SELECT * FROM `{$tablepre}news` WHERE isstate=1 ".$sqlkey." order by disorder desc,id asc limit 0,$num";
 	$result=$db->sql_query($sql);
 	while($bd=$db->sql_fetchrow($result)){
		if(!empty($bd['img1'])) $Img=$bd['img1']; else $Img="nopic.jpg";
		
		$str.="<li>{$bd['title']}</li>\n";

	}
	return $str;
	unset($sql,$result,$bd);
}

//服务项目
function get_link_img_list7($pid=11,$ty=37,$num=3){
	global $db,$upload_picpath,$tablepre,$srt; 
	if($pid) $sqlkey=" AND pid={$pid}";
	if($ty) $sqlkey.=" AND ty={$ty}";
 
	$sql= "SELECT * FROM `{$tablepre}news` WHERE isstate=1 and img1<>'' ".$sqlkey." order by disorder desc,id asc limit 0,$num";
 	$result=$db->sql_query($sql);
 	while($bd=$db->sql_fetchrow($result)){
		if(!empty($bd['img1'])) $Img=$bd['img1']; else $Img="nopic.jpg";
		$url=vrt($bd['id'],$srt,"view");
		$nr=cutstr_html($bd['seodescription'],150);

 		$str.="
		<div class=\"item cle\">
			<li>
				<div class=\"c\">
					<p class=\"t\">{$bd['title']}</p>
					<p class=\"t1\">{$bd['ftitle']}</p>
					<div class=\"infos\">{$nr}</div>
					<a href=\"{$url}\" class=\"hrs\">了解更多</a>
				</div>
				<img src=\"./{$upload_picpath}/{$Img}\" alt=\"{$bd['title']}\"/>
			</li>
		</div>\n";
	}
	return $str;
	unset($sql,$result,$bd);
}

//服务项目
function get_link_img_list8($pid=11,$ty=37,$num=3){
	global $db,$upload_picpath,$tablepre,$srt; 
	if($pid) $sqlkey=" AND pid={$pid}";
	if($ty) $sqlkey.=" AND ty={$ty}";
 
	$sql= "SELECT * FROM `{$tablepre}news` WHERE isstate=1 and img1<>'' ".$sqlkey." order by disorder desc,id asc limit 0,$num";
 	$result=$db->sql_query($sql);
 	while($bd=$db->sql_fetchrow($result)){
		if(!empty($bd['img1'])) $Img=$bd['img1']; else $Img="nopic.jpg";
		$url=vrt($bd['id'],$srt,"view");
		

 		$str.="
		<li class=\"fl\">
			<img src=\"./{$upload_picpath}/{$Img}\" alt=\"{$bd['title']}\"/>
			<p class=\"t1\">{$bd['title']}</p>
			<p class=\"t2\">{$bd['ftitle']}</p>
			<div class=\"c1\">{$bd['seodescription']}</div>
		</li>\n";
	}
	return $str;
	unset($sql,$result,$bd);
}





//首页按钮广告*5 
function get_ad_img_list($pid=6,$ty=29,$num=10){
	global $db,$upload_picpath,$tablepre,$srt; 
	if($pid) $sqlkey=" AND pid={$pid}";
	if($ty) $sqlkey.=" AND ty={$ty}";
 
	$sql= "SELECT * FROM `{$tablepre}news` WHERE isstate=1 ".$sqlkey." order by disorder asc,id asc limit 0,$num";
 	$result=$db->sql_query($sql);

 	while($bd=$db->sql_fetchrow($result)){
		
		if(!empty($bd['img1'])) $Img=$bd['img1']; else $Img="nopic.jpg";

 		$str.="
		<li>
			<p class=\"t\">{$bd['seodescription']}</p>
			<div class=\"img\"><img src=\"./{$upload_picpath}/{$Img}\" /></div>
			<p class=\"t1\">{$bd['title']}</p>
		</li>\n";

	}
	return $str;
	unset($sql,$result,$bd);
}

//首页按钮广告*5 
function get_ad_img_list2($pid=6,$ty=29,$num=10){
	global $db,$upload_picpath,$tablepre,$srt; 
	if($pid) $sqlkey=" AND pid={$pid}";
	if($ty) $sqlkey.=" AND ty={$ty}";
 
	$sql= "SELECT * FROM `{$tablepre}news` WHERE isstate=1 ".$sqlkey." order by disorder asc,id asc limit 0,$num";
 	$result=$db->sql_query($sql);

 	while($bd=$db->sql_fetchrow($result)){
		
		if(!empty($bd['img1'])) $Img=$bd['img1']; else $Img="nopic.jpg";

 		$str.="
		<div class=\"swiper-slide\">
			<div class=\"banimgarea\"><img src=\"./{$upload_picpath}/{$Img}\" class=\"banimg\"  /></div>
		</div>\n";

	}
	return $str;
	unset($sql,$result,$bd);
}

//首页按钮广告*5 
function get_ad_img_list3($pid=6,$ty=29,$num=10){
	global $db,$upload_picpath,$tablepre,$srt; 
	if($pid) $sqlkey=" AND pid={$pid}";
	if($ty) $sqlkey.=" AND ty={$ty}";
 
	$sql= "SELECT * FROM `{$tablepre}news` WHERE isstate=1 ".$sqlkey." order by disorder asc,id asc limit 0,$num";
 	$result=$db->sql_query($sql);

 	while($bd=$db->sql_fetchrow($result)){
		
		if(!empty($bd['img1'])) $Img=$bd['img1']; else $Img="nopic.jpg";

 		$str.="
		<div class=\"item\">
			1
			<img src=\"./{$upload_picpath}/{$Img}\" />
		</div>\n";

	}
	return $str;
	unset($sql,$result,$bd);
}


?>