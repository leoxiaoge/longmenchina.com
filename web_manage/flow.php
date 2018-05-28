<? 
$Title="购物车";
include_once('header.php');
$sys_typeid=$_SESSION['sys_typeid'];

if($_GET['action']=='del'){
	$sql="delete FROM `{$tablepre}temp` where isstate=0 AND guestid='$guestid' AND id={$id}";
 	if($db->sql_query($sql)){
		JsGourl("flow.php?step=cart");
	}else{
		JsError("网络异常,删除失败！");
	}
}

$kid=(int)opturl("kid");;

//echo $kid;

$payprice=get_zd_name("price","payment"," and id={$kid} ");

//exit($payprice);

if($uid){
	$address_count=get_count("address"," and uid={$uid}");
}else{
	$address_count=get_count("address"," and guestid={$guestid} and isdel=0 ");
}
 
if ($_POST['ok']=="删除选中的商品"){
 
	$del_num=count($checkid);
 
	for($i=0;$i<$del_num;$i++){
		$sql="select img1 FROM `{$tablepre}temp` where id=".$checkid[$i]."";
		$result=$db->sql_query($sql);
		if($bd=$db->sql_fetchrow($result)){
			$insertid=$db->delete("temp"," id=".$checkid[$i]."");
 		}
		JsSucce("操作成功！","flow.php?step=cart");
	}
	
	exit();
	
}elseif($_POST['step']=="checkorder"){

	if($_POST['send']=="1"){
	 	
		if (isset($_POST['saveaddress'])) {
			
 			$sqlvalues="";
			$fields=$_POST['fields'];
			$fields['uid']=$uid;
			$fields['guestid']=$guestid;
			$fields['realname']=trim($_POST['realname']);
			$fields['address']=trim($_POST['address']);
			$fields['zip']=trim($_POST['zip']);
			$fields['tel']=trim($_POST['tel']);
			$fields['phone']=trim($_POST['phone']);
			$fields['province']=trim($_POST['province']);
			$fields['city']=trim($_POST['city']);
			$fields['town']=trim($_POST['town']);
			$fields['isdefault']=(int)$_POST['isdefault'];
			$fields['sendtime']=$PHP_TIME;
			
			while(list ($key,$val) =each ($fields)){
				$sqlvalues.=",$key='$val'";
			}
			$sqlvalues=substr($sqlvalues,1);
		
			$sql="INSERT INTO `{$tablepre}address` SET ".$sqlvalues;
			//exit($sql);
			
			$db->sql_query($sql);
			
			JsGourl("flow.php?step=consignee");
				
		}else{
 	 
			if($_POST['aid']==0){
				JsError("请先保存收货地址！");
				exit();
			}
			
			if(empty($_POST['zid'])){
				JsError("请选择支付方式！");
				exit();
			}

			$result2 = $db->sql_query("SELECT * FROM `{$tablepre}temp` WHERE isstate=0 AND uid='$uid' group by uid ");
			//exit("SELECT * FROM `{$tablepre}temp` WHERE isstate=0 AND uid='$uid' group by uid ");
			while($bd2=$db->sql_fetchrow($result2)){

				$curDateTime = date("YmdHis");
				$strDate = date("ymd");
				$strTime = date("His");
				//4位随机数
				$randNum = rand(1000, 9999);
				//10位序列号,可以自行调整。
				$strReq = $strTime . $randNum;
				 /* 商家的定单号 */
				$orderid = $curDateTime . $randNum;
				
				$tpayprice=get_zd_name("price","payment"," and id={$_POST['kid']} ");
				
				$tprice=$bd2['price']*$bd2['num'];
				
				$arr=explode(",",$_POST['yid']);
				
				$yid=$arr[0];
				$yids=$arr[1];
				
				$torderid=$orderid;
				$tuid=$uid;
				$tguestid=$guestid;
				$taid=(int)$_POST['aid']; //地址id
				$tkid=(int)$_POST['kid']; //快递id
				$tzid=(int)$_POST['zid']; //支付ID
				$tyid=$yid; //优惠ID
				$tyids=$yids; //优惠TY
				$tpayprice=$tpayprice;
				$tfp=trim($_POST['fp']);
				$tfptt=trim($_POST['fptt']);
				$tagree=trim($_POST['agree']);
				$ttotalprice=$tprice;
				$tmessage=trim($_POST['message']);
				$tsendtime=$PHP_TIME;
				
				$sql3="insert into `{$tablepre}orders`(orderid,uid,guestid,aid,kid,zid,yid,yids,payprice,fp,fptt,agree,totalprice,message,sendtime) values('$torderid','$tuid','$tguestid','$taid','$tkid','$tzid','$tyid','$tyids','$tpayprice','$tfp','$tfptt','$tagree','$ttotalprice','$tmessage','$tsendtime')";
				
				//exit($sql3);
				
				if($db->sql_query($sql3)){
					
					$oid=mysql_insert_id();
					
					if($tyids==28){
						$db->sql_query("update `{$tablepre}size` set isstate=0 where id={$tyid}");
						$yprice=get_zd_name("price","size"," and id={$tyid} ");
						$db->sql_query("update `{$tablepre}orders` set totalprice=totalprice-{$yprice} where orderid={$torderid}");
					}elseif($tyids==29){
						$db->sql_query("update `{$tablepre}bank` set isstate=0 where id={$tyid}");
						$yprice=get_zd_name("price","bank"," and id={$tyid} ")*0.1;
						exit("update `{$tablepre}orders` set totalprice=totalprice*{$yprice} where orderid={$torderid}");
					}
					
					$result = $db->sql_query("SELECT * FROM `{$tablepre}temp` WHERE isstate=0 AND uid='{$uid}' ");
					while($bd=$db->sql_fetchrow($result)){
						$gid=$bd['pid'];
						$gnum=$bd['num'];
						$gtitle=$bd['title'];
						$gprice=$bd['price'];
						$gcolor=$bd['color'];
						$gimg1=$bd['img1'];
							
						$sql2="insert into `{$tablepre}orders_items`(orderid,oid,uid,guestid,pid,title,color,img1,price,num,sendtime) values('$orderid','$oid','$uid','$guestid','$gid','$gtitle','$gcolor','$gimg1','$gprice','$gnum',".$PHP_TIME.")";
						if($db->sql_query($sql2)){
							$db->sql_query("update `{$tablepre}prod` set inventory=inventory-".$gnum." where id={$gid}");
							$db->sql_query("delete from `{$tablepre}temp` where uid='{$uid}' ");
						}
					}
				}
			}
			
			header("location: flow.php?step=done&orderid={$orderid}"); 
			exit();
		
		}	
		
	}
	
}elseif($step=="done"){

	$sql="select o.orderid,o.totalprice,o.payprice,o.zid,o.kid,a.* from `{$tablepre}orders` o,`{$tablepre}address` a where o.aid=a.id AND o.uid={$uid} and o.orderid='$orderid'";
	//echo $sql;
	$result=$db->sql_query($sql);
	if($row=$db->sql_fetchrow($result)){
		$zid=$row['zid'];
		$message=$row['message'];
		$orderid=$row['orderid'];
 		$tprice=$row['totalprice']+get_zd_name("price","payment"," and id={$row['kid']} ");
		$title="会员：".$_COOKIE['sys_username']."购买订单{$orderid}的商品款！";
 	}
	
}
?>

<? if($step=="done"){?>

<!-- 热门推荐 -->
<div class="w1200 clearfix">

    <div class="tit"><a href="./">首页</a><span>&gt;</span>成功提交订单</div>

    <div class="step_tit step_three"><span>1、我的购物车</span><span>2、确认订单信息</span><span class="on">3、成功提交订单</span></div>
    <div class="cart3 tc">
        <img src="images/ico/OK.png"><br>
        <h2>感谢您在本店购物！您的订单已提交成功，请记住您的订单号:<span> <?=$orderid?></span></h2>
        <p>您选定的配送方式为: <?=get_zd_name("title","payment"," and id={$row['kid']} ")?>，您选定的支付方式为:<? if($zid==1){?> 支付宝<? }else{ ?>货到付款<? } ?>。您的应付款金额为: <?=$tprice?>元</p>
        <a href="" class="tj yuan_3">立即去支付</a>
    </div>
</div>

<? }elseif ($step=="cart"&&$cartnums) {?>

<!-- 热门推荐 -->
<div class="w1200 clearfix">
    
	<div class="tit"><a href="./">首页</a><span>&gt;</span>我的购物车</div>
    
	<div class="step_tit step_one"><span class="on">1、我的购物车</span><span>2、确认订单信息</span><span>3、成功提交订单</span></div>
    
	<form action="flow.php?step=consignee" method="post">
	
		<table class="cart1_tab">
			<tr class="td_bg">
				<td width="20%" class="tl"><input type="checkbox" id="checkall" ><span>全选</span></td>
				<td width="30%">商品</td>
				<td width="15%">单价</td>
				<td width="15%">数量</td>
				<td width="10%">小计</td>
				<td width="10%">编辑</td>
			</tr>
			
			<?
			if($uid) $sqlkey=" AND uid={$uid}";
			
			$sql1="SELECT * FROM `{$tablepre}temp` where isstate=0 {$sqlkey} and guestid='$guestid'";
			//echo $sql1;
			$result1=$db->sql_query($sql1);
			$t=0;
			while($bd1=$db->sql_fetchrow($result1)){
				if(!empty($bd1['img1'])) $img1=$bd1['img1']; else $img1="nopic.jpg";
				$gid=$bd1['id'];
				$t++;
				$num=$bd1['num'];
				$nums=$nums+$num;
				$tnum=$nums;
				
				$price=$bd1['price'];
				
				$tprice=$price*$num;
				$tjine=$tjine+$tprice;
				$totalprice=$tjine;	
				
			?>
			<input value="<?=$bd1['id']?>" class="gid" type="hidden">
			
			<tr>
				
				<td class="tl">
					<input type="checkbox" name='checkid[]' class="ids" value='<?=$bd1['id']?>' >
					<a href="show.php?id=<?=$bd1['pid']?>">
						<img src="/uploadfile/upload/<?=$bd1['img1']?>" class="small_img">
					</a>
				</td>
				
				<td><?=$bd1['title']?>--<?=$bd1['color']?></td>
				
				<td>￥<?=$bd1['price']?></td>
				
				<td>
					<span class="add_jian">
				
						<em href="javascript:void(0)" class="jian" onClick="setAmount.reduce('#qty_item_<?=$t?>',<?=$gid?>)">-</em>
						<input type="text" class="num" name="qty_item_<?=$t?>" value="<?=$num?>" id="qty_item_<?=$t?>" onKeyUp="setAmount.modify('#qty_item_<?=$t?>',<?=$gid?>)">
						<em href="javascript:void(0)" onClick="setAmount.add('#qty_item_<?=$t?>',<?=$gid?>)" class="add">+</em>
						
					</span>
				</td>
				
				<td class="red1">￥<?=$tprice?></td>
				
				<td><a href="javascript:void()" onclick="if (confirm('您确认删除该购物车的信息吗？'))location.href='?action=del&id=<?=$bd1['id']?>'">删除</a></td>
			
			</tr>
			
			<? } ?>
			
			<tr class="other td_bg">
				<td class="tl" colspan="3">
					<input type="button" class="del" onClick="return checkData();" name="ok" value="删除选中的商品">
				</td>
				<td colspan="3" class="tr cart_btn">
					<span>商品总计</span>
					<b class="red1">￥<?=$totalprice?></b>
					<a href="./" class="continue">继续购物</a>
					<input type="submit" class="tj" value="下单结算">
				</td>
			</tr>
			
		</table>
	
	</form>
	
</div>

<? }elseif($step=="consignee"){?>

<script type="text/javascript" src="js/selectarea.js"></script>

<!-- 热门推荐 -->
<div class="w1200 clearfix">
    
	<div class="tit"><a href="./">首页</a><span>&gt;</span>确认订单信息</div>
    
	<div class="step_tit step_two"><span>1、我的购物车</span><span class="on">2、确认订单信息</span><span>3、成功提交订单</span></div>
    
	<div class="cart2">
        
		<form action="" method="post" name="formlist" onsubmit="return check_orders(this)" class="gwc2_form">
			<input name="send" type="hidden" value="1"/>
			<input name="step" type="hidden" value="checkorder"/>
			<input name="tipid" type="hidden" value="<?=$address_count?>">
			<input name="ids" type="hidden" value="<?=$ids?>">
			
			<div class="cart2_tit">填写和提交订单信息</div>
			
			<div class="cart2_one">
				<table class="cart2_tab b">
					<tr>
						<td colspan="2">
							<span>收货信息：</span>
							<a href="flow.php?step=consignee&amp;action=add_d" class="red1">修改收货人信息</a>
						</td>
					</tr>
					
					<?
					if($address_count&&$action<>"add_d") {
						echo get_user_address_list($uid,$guestid);
					}else{
					?>
					<tr>
						<td class="tr"><i>*</i><span>收货人：</span></td>
						<td><input type="text" class="text" name="realname" ></td>
					</tr>
					<tr>
						<td class="tr"><i>*</i><span>所在地区：</span></td>
						<td>
							<select name="province" id="province" onChange="changepro('city','province');">
								<option value="" selected="selected">省/直辖市</option>
								<option value='北京市'>北京市</option>
								<option value='天津市'>天津市</option>
								<option value='河北省'>河北省</option>
								<option value='山西省'>山西省</option>
								<option value='内蒙古区'>内蒙古区</option>
								<option value='辽宁省'>辽宁省</option>
								<option value='吉林省'>吉林省</option>
								<option value='黑龙江省'>黑龙江省</option>
								<option value='上海市'>上海市</option>
								<option value='江苏省'>江苏省</option>
								<option value='浙江省'>浙江省</option>
								<option value='安徽省'>安徽省</option>
								<option value='福建省'>福建省</option>
								<option value='江西省'>江西省</option>
								<option value='山东省'>山东省</option>
								<option value='河南省'>河南省</option>
								<option value='湖北省'>湖北省</option>
								<option value='湖南省'>湖南省</option>
								<option value='广东省'>广东省</option>
								<option value='广西区'>广西区</option>
								<option value='海南省'>海南省</option>
								<option value='重庆市'>重庆市</option>
								<option value='四川省'>四川省</option>
								<option value='贵州省'>贵州省</option>
								<option value='云南省'>云南省</option>
								<option value='西藏区'>西藏区</option>
								<option value='陕西省'>陕西省</option>
								<option value='甘肃省'>甘肃省</option>
								<option value='青海省'>青海省</option>
								<option value='宁夏区'>宁夏区</option>
								<option value='新疆区'>新疆区</option>
							</select>
							<select name="city" id="city" onChange="changecity('town','city');">
								<option value="">请选择</option>
							</select>
							<select name="town" id="town">
								<option value="">请选择</option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="tr"><i>*</i><span>详细地址：</span></td>
						<td><input type="text" class="text" style="width:300px" name="address" ></td>
					</tr>
					<tr>
						<td class="tr"><i>*</i><span>手机号码：</span></td>
						<td>
							<input type="text" class="text" name="tel" ><span>或</span>　　
							<span>固定电话：</span>
							<input type="text" class="text" name="phone" >
						</td>
					</tr>
					<tr>
						<td class="tr"><i>*</i><span>邮编：</span></td>
						<td><input type="text" class="text" name="zip" ><span>如您不清楚邮递区号，请填写000000</span></td>
					</tr>
					<tr>
						<td></td>
						<td><input type="submit" class="tj" name="saveaddress" value="保存收货人信息"></td>
					</tr>
					
					<? } ?>
					
				</table>
			</div>
			
			<div class="cart2_one">
				<div class="cart2_two">
					<i class="cart2_tit2 i">配送方式</i>
					<?=get_payment_price()?>
				</div>
				<div class="cart2_two">
					<i class="cart2_tit2 i">支付方式</i>
					<input type="radio" name="zid" value="1" checked><span>支付宝支付</span>
				</div>
				
				<!--发票信息-->
				<div class="cart2_two">
					<i class="cart2_tit2 i">发票信息</i>
					<input type="checkbox" class="facheck">
				</div>
				
				<div class="tabborder invotab">
					<div class="invo_tit">
						 <span class="act_invo" title="不要发票">不要发票</span>
						 <span title="个人发票">个人发票</span>
						 <span title="公司发票">公司发票</span>
					 </div>
					 
					<input type="hidden" id="fp" name="fp" value="不要发票"/>
						
					<script>
					$('.invo_tit span').click(function(){
						$("#fp").val(this.title);
					});
					</script>
					 
					<div class="invo_con">
						<div class="invobox"></div>
						<div class="invobox"></div>
						<div class="invobox">
							<div class="idt">
								<span class="mc">发票抬头</span><input type="text" class="fp_text" name="fptt">
							</div>
							<div class="idb">
								<input type="checkbox" name="agree" id="agree" value="1" checked="checked" disabled="disabled">
								我已阅读 并同意<a href="" target="_blank">《发票须知》</a>
							</div>
						</div>
					</div>
			   </div>
			
			</div>
			
			<style>
			/** 购物车2————发票信息 **/
			.invotab{ display:none;}
			.invo_tit{}
			.invo_tit span{ display:inline-block; width: 100px;height: 30px;line-height: 30px;margin-right: 15px;cursor: pointer;background:url(../images/ico/h_1.png) no-repeat scroll left center;padding-left: 25px;}
			.act_invo{background:url(../images/ico/h_2.png) no-repeat scroll left center !important;}
			.invo_con{ }
			.invobox{ display:none;}
			.idt{ padding:10px 0;}
			.idt .mc{margin-right: 5px;}
			.idt input{width: 300px;height: 28px;border: 1px solid #DDD;padding: 0px 5px;}
			.idb{line-height: 30px;}
			.idb input{margin-right: 5px;}
			.idb a{color: #039;}
			.idb a:hover{ color:#87c012;}
			</style>
			<script>
			//购物车2发票
			$(function(){
				 $(".facheck").click(function(){
						 if($(".facheck").prop("checked"))
						 {
							 $(".invotab").slideDown();
						 }
							 else
							 {
								 $(".invotab").slideUp();
							 }
					 })
				 })
			
			$(function(){
					$(".invo_tit span").click(function(){
						$(this).addClass("act_invo");
						$(this).siblings().removeClass("act_invo");
						var _index=$(this).index();
						$(".invobox").eq(_index).show();
						$(".invobox").eq(_index).siblings().hide();
					})
				});
			
			</script>
			
			<table class="order_tab order_tab3">
				<tr class="td">
					<td width="40%" class="tl">商品</td>
					<td width="20%" class="tc">单价</td>
					<td width="20%" class="tc">数量</td>
					<td width="20%" class="tc">小计</td>
				</tr>
				
				<?
				if($_POST['checkid']){
					$ids=implode(",",$_POST['checkid']);
					$sqlkeys=" AND id in(".$ids.")";
				}	
				if($sys_typeid) $sqlkeys.=" AND typeid={$sys_typeid} ";
				$sql1="SELECT * FROM `{$tablepre}temp` where isstate=0 AND guestid='$guestid' AND uid='$uid' {$sqlkeys}";
				   //echo $sql1;
				$result1=$db->sql_query($sql1);
				$t=0;
				while($bd1=$db->sql_fetchrow($result1)){
					$t++;
					if(!empty($bd1['img1'])) $img1=$bd1['img1']; else $img1="nopic.jpg";
					$gid=$bd1['id'];
					$num=$bd1['num'];
					$price=$bd1['price'];
					$tprice=$price*$num;
					$tjine=$tjine+$tprice;
					$totalprice=$tjine;
				?>
				<input type="hidden" name="color" value="<?=$bd1['color']?>">
				<tr>
					<td class="tl">
						<a href='show.php?id=<?=$bd1['id']?>' target="_blank" class="order_img">
							<img src="/uploadfile/upload/<?=$bd1['img1']?>">
							<span><?=$bd1['title']?>--<?=$bd1['color']?></span>
						</a>
					</td>
					<td class="tc">￥<?=$price?></td>
					<td class="tc"><?=$bd1['num']?></td>
					<td class="tc">￥<?=$tprice?></td>
				</tr>
				<? } ?>
				
			</table>
			<div style=" text-align:right;">
				使用优惠券：
				<select name="yid" style=" width: 200px; border: 1px solid #c9c9c9;">
					<option value="0">不使用优惠</option>
					<?=get_xjdyj_list($uid,$PHP_DATE,$totalprice)?>
					<?=get_zkj_list($uid,$PHP_DATE,$totalprice)?>
				</select>
			</div>
			<div class="order_ly"><b>订单留言</b><span class="sp1">最多200字，如有超过请联系客服</span></div>
			
			<table class="cart2_tab2">
				<tr>
					<td width="650"><textarea name="message"></textarea></td>
					<td class="tr">
						<p>商业金额：￥<?=$totalprice?></p>
						<input type="submit" class="tj" value="提交订单">
					</td>
				</tr>
			</table>
		
			<input type="hidden" class="zfb" value="商品总价：订单总价：<?=$totalprice?>元">
			<input type="hidden" name="totalprice" value="<?=$totalprice?>">
		
		</form>
		
    </div>
	
</div>

<? }elseif ($step=="cart"&&$cartnums==0) {?>

<!-- 热门推荐 -->
<div class="w1200 clearfix">

    <div class="tit"><a href="./">首页</a><span>&gt;</span>成功提交订单</div>

    <div class="step_tit step_one"><span class="on">1、我的购物车</span><span>2、确认订单信息</span><span>3、成功提交订单</span></div>
    <div class="cart3 tc">
        <br>
        <h2>您的购物车还没有商品</h2>
        <a href="prod.php?pid=1" class="tj yuan_3">立即去购买</a>
    </div>
	
</div>

<? }?>
 	
<? include_once('footer.php');?>
    
</body>
</html>