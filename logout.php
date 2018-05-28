<?php
if(!empty($_COOKIE['sys_uid'])){  
	setcookie("sys_username", null, time()-3600*24*365,"./");  
	setcookie("sys_uid", null, time()-3600*24*365,"./");  
	setcookie("sys_addes", null, time()-3600*24*365,"./");  
}  

echo "<script>location.href='./index.php'</script>";

unset($action);
?>