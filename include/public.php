<? include_once('header.php');?>
<? if($pid==6){?>
<!--banner begin-->
<div class="i_banner" style=" background:url(uploadfile/upload/<?=get_zd_name("img1","news_cats"," and id={$pid}")?>) center top no-repeat;"></div>
<!--banner end-->
<? }?>

<? if($ty==26){?>
<div style=" width:1100px; height:2350px; overflow:hidden ; margin:0 auto">
        <div style=" margin-top:-139px; margin-left:-4px; ">
           <iframe src="http://www.91shihua.com/cjsj.html" width="200%"; height="2500" frameborder="0" scrolling="no" style="margin-left:-50%" >
           </iframe>
         </div>
</div>
<div class="w1100"></div>
<div class="clear"></div>
<? }else{?>
<div class="w1100">
	<div class="down">
     <div class="downt">
           <?=get_download_list_fy($pid,$ty)?>
     </div>
     <div class="downb">
          <div class="db_l"></div>
          <div class="db_r">
               <div class="wz"><h4>厦门两岸商品交易中心交易软件</h4><p>简介：正式交易客户端兼容各windows操作系统</p></div>
               <ul>
                    <li><span><img src="images/icon/down_app.png" /></span><a href="#"><span>点击下载</span></a></li>
                    <li><span><img src="images/icon/down_app.png" /></span><a href="#"><span>点击下载</span></a></li>
               </ul>
          </div>
     </div>
</div>
<div class="clear"></div>
</div>
<? }?>  
<? include_once('footer.php');?>
  
</body>
</html>