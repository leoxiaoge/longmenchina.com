
function login_show(id){ 
	if(confirm("只有登录会员才可以操作？")){ 
		var gurl=encodeURI("show.php?id="+id);
		window.location = 'login.php?uri='+gurl; 
	} 
} 

function login2(id){ 
	if(confirm("只有登录会员才可以报名？")){ 
		var gurl=encodeURI("list.php?pid=5");
		window.location = 'login.php?uri='+gurl; 
	} 
}

 function Search() {
	var n = $("#kws").val();
	if (n == null || n == "" || n == " ") {
		alert("请输入商品名称");
		return false;
	}
	else {
		window.location.href = "/products.php?q=" + n;
	}
}

function CheckAll(formlist){
  var val=document.formlist.all.checked;
  for (var i=0;i<document.formlist.elements.length;i++){
    var e = document.formlist.elements[i];
    if (e.name != 'all')
      e.checked = val;
  }
}

function checkData(formlist){  
	var RecordsCount=0;
	for (var i=0;i<document.formlist.elements.length;i++){
		var e = document.formlist.elements[i];
		if (e.name != 'all' && e.checked)
			RecordsCount++;
	}
           
	if(!RecordsCount){
   		alert("你还没选择记录！");
    	return false
	}else{
		if (confirm("即将操作所有选择的记录， 是否继续 ？")){            
     		return true;
		}else{
   			return false;
		}
	}
}

function check_search(formlist){	
   	if (checkspace(formlist.q.value))
	{alert("请输入关键字！");
	formlist.q.focus();
	return false;
	}
}

function check_updatepwd(formlist){
  if (checkspace(formlist.password.value))
  {
    alert("提示：请输入原密码！");
    formlist.password.focus();
    return (false);
  }
  if (checkspace(formlist.password1.value))
  {
    alert("提示：请输入新密码！");
    formlist.password1.focus();
    return (false);
  }
  if (checkspace(formlist.password2.value))
  {
    alert("提示：请确认新密码！");
    formlist.password2.focus();
    return (false);
  }
  
  if (formlist.password1.value!=formlist.password2.value)
  {
    alert("提示：您两次输入的密码不一样，请检查后重新输入！");
    formlist.password2.focus();
    return (false);
  }
}

function check_message(formlist){

	if (checkspace(formlist.title.value)){
		alert("提示：请输入标题！");
		formlist.title.focus();
		return (false);
	}

	if (checkspace(formlist.message.value)){
		alert("提示：请输入内容！");
		formlist.message.focus();
		return (false);
	}

	if (checkspace(formlist.tel.value)){
		alert("提示：请输入手机号码！");
		formlist.tel.focus();
		return false;
	}

	var telcode=new RegExp(/^\d{11}$/);
	
	if (!formlist.tel.value.match(telcode)) {
		alert("提示：请输入正确的手机号!");
		return false;
	}

	if (checkspace(formlist.addess.value)){
		alert("提示：请输入地址！");
		formlist.addess.focus();
		return false;
	}

}

function check_apply(formlist){

	if (checkspace(formlist.realname.value)){
		alert("提示：请输入您的姓名！");
		formlist.realname.focus();
		return (false);
	}

	if (checkspace(formlist.tel.value)){
		alert("提示：请输入手机号码！");
		formlist.tel.focus();
		return false;
	}

	var telcode=new RegExp(/^\d{11}$/);
	
	if (!formlist.tel.value.match(telcode)) {
		alert("提示：请输入正确的手机号!");
		return false;
	}

	if (checkspace(formlist.addes.value)){
		alert("提示：请输入您的联系地址！");
		formlist.addes.focus();
		return (false);
	}
}

function check_orders(formlist){

	if (checkspace(formlist.realname.value)){
		alert("提示：请输入您的姓名！");
		formlist.realname.focus();
		return (false);
	}

	if (checkspace(formlist.tel.value)){
		alert("提示：请输入手机号码！");
		formlist.tel.focus();
		return false;
	}

	var telcode=new RegExp(/^\d{11}$/);
	
	if (!formlist.tel.value.match(telcode)) {
		alert("提示：请输入正确的手机号!");
		return false;
	}

}

function addess_check(formlist){

	if (checkspace(formlist.realname.value))
	{
		alert("提示：请输入您的姓名！");
		formlist.realname.focus();
		return (false);
	}

	if (checkspace(formlist.tel.value))
	{
		alert("提示：请输入手机号码！");
		formlist.tel.focus();
		return false;
	}

	var telcode=new RegExp(/^\d{11}$/);
	
	if (!formlist.tel.value.match(telcode)) {
		alert("提示：请输入正确的手机号!");
		return false;
	}

	if (checkspace(formlist.addes.value))
	{
		alert("提示：请输入您的联系地址！");
		formlist.addes.focus();
		return (false);
	}
}
 
function change_yzm() {
	var num = 	new Date().getTime();
	var rand = Math.round(Math.random() * 10000);
	num = num + rand;
	if ($("#vdimgck")[0]) {
		$("#vdimgck")[0].src = "/include/yzm.php?tag=" + num;
	}
	return false;	
}

//刪除信息驗證
function ConfirmDelInfo(){
   if(confirm("確定要刪除此信息嗎？刪除後不能恢復！"))
     return true;
   else
     return false;
	 
}


//去空格
function checkspace(checkstr) {
	var str = '';
	for(i = 0; i < checkstr.length; i++) 
	{
		str = str + ' ';
	}
	return (str == checkstr);
}

function fun(targ,selObj,restore){
	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	if (restore) selObj.selectedIndex=0;
}
		
//加入收藏
function AddFavorite(sURL, sTitle) {
	sURL = encodeURI(sURL);
	try{  
		window.external.addFavorite(sURL, sTitle);  
	}catch(e) {  
		try{  
			window.sidebar.addPanel(sTitle, sURL, "");  
		}catch (e) {  
			alert("加入收藏失敗，請使用Ctrl+D進行添加,或手動在瀏覽器裏進行設置.");
		}  
	}
}
//設為首頁
function SetHome(url){
	if (document.all) {
		document.body.style.behavior='url(#default#homepage)';
		document.body.setHomePage(url);
	
	}else{
		alert("您好,您的瀏覽器不支持自動設置頁面為首頁功能,請您手動在瀏覽器裏設置該頁面為首頁!");
	}
}

function check_reg(formlist){

	if (checkspace(formlist.username.value))
	{
		alert("请输入用户名！");
		formlist.username.focus();
		return false;
	}
	
	 	if (checkspace(formlist.userpwd.value))
	{
		alert("提示：请输入密码！");
		formlist.userpwd.focus();
		return (false);
	}
	
	if (checkspace(formlist.userpwd2.value))
	{
		alert("提示：请输入确认密码！");
		formlist.userpwd2.focus();
		return (false);
	}

	if (formlist.userpwd.value!=formlist.userpwd2.value)
	{
		alert("提示：您两次输入的密码不一样，请检查后重新输入！");
		formlist.userpwd2.focus();
		return (false);
	}

}


function check_login(formlist){
	if (checkspace(formlist.username.value))
	{
		alert("请输入用户名！");
		formlist.username.focus();
		return false;
	}

	if (checkspace(formlist.userpwd.value))
	{
		alert("提示：请输入密码！");
		formlist.userpwd.focus();
		return (false);
	}
}

function check_getpwd(formlist){
	
	if (checkspace(formlist.username.value))
	{
		alert("请输入用户名！");
		formlist.username.focus();
		return false;
	}

	if (checkspace(formlist.yzm.value)){
		alert("请输入验证码！");
		formlist.yzm.focus();
		return false;
	}
	
}

function check_getpwd2(formlist){
	
	if (checkspace(formlist.password1.value)){
		alert("提示：请输入新密码！");
		formlist.password1.focus();
		return (false);
	}
	if (checkspace(formlist.password2.value)){
		alert("提示：请确认新密码！");
		formlist.password2.focus();
		return (false);
	}
  
	if (formlist.password1.value!=formlist.password2.value){
		alert("提示：您两次输入的密码不一样，请检查后重新输入！");
		formlist.password2.focus();
		return (false);
	}
}


function show(val){
	if(val==1){
		document.getElementById("show1").style.display = "block";
		document.getElementById("show2").style.display = "none";
	}
	if(val==2){
		document.getElementById("show1").style.display = "none";
		document.getElementById("show2").style.display = "block";
	} 
			
}