<?php
include_once("func/url.php");
include_once("func/checklogin.php");
include_once("func/sql.php");
$data=checklogin();
$power=$data["power"];
?>
<script src="<?php echo $url;?>res/jquery.min.js"></script>
<script>
function keyFunction(){
	if ((event.altKey) && (event.keyCode!=18)){
		switch(event.keyCode){
			case 49: location="<?php echo $url;?>home";break;
			case 50: location="<?php echo $url;?>search";break;
			case 51: location="<?php echo $url;?>user";break;
			<?php
			if($power>=2){
			?>
			case 52: location="<?php echo $url;?>borrow";break;
			case 53: location="<?php echo $url;?>return";break;
			case 54: location="<?php echo $url;?>managebook";break;
			case 55: location="<?php echo $url;?>manageuser";break;
			<?php
			}
			?>
			case 48: location="<?php echo $url.($data?"logout":"login");?>";break;
		}
	}
}
window.onkeydown=keyFunction;
document.onkeydown=keyFunction;
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="60%" height="100" align="center" valign="middle" bgcolor="#F0F0F0" style="font-weight: bold;">
			<span style="font-size: 36px; color: #888;">E-</span><span style="font-size: 36px">LM</span><span style="font-size: 36px; color: #888;">S</span>
			<br>
			<span style="color: #999">E-Library Management System</span><br>
			<span style="color: #999">電子化圖書管理系統</span>
		</td>
		<td width="40%" bgcolor="#F0F0F0" style="text-align: right" colspan="2"><img src="http://www.tfcis.org/images/TFcisweb3_03.gif"height="100px"></td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="68%" height="25" valign="middle" bgcolor="#0000FF" style="color: #FFF">
			&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $url;?>home" target="_parent" style="color:#FFF" >首頁</a>&nbsp;|&nbsp;<a href="<?php echo $url;?>search" target="_parent" style="color:#FFF">館藏查詢</a>&nbsp;|&nbsp;<a href="<?php echo $url;?>user" target="_parent" style="color:#FFF">讀者資料查詢</a><?php if($power>=2){ ?>&nbsp;|&nbsp;<a href="<?php echo $url;?>borrow" target="_parent" style="color:#FFF">借書</a>&nbsp;|&nbsp;<a href="<?php echo $url;?>return" target="_parent" style="color:#FFF">還書</a>&nbsp;|&nbsp;<a href="<?php echo $url;?>managebook" target="_parent" style="color:#FFF">圖書</a>&nbsp;|&nbsp;<a href="<?php echo $url;?>manageuser" target="_parent" style="color:#FFF">使用者</a>
			<?php } ?>
		</td>
		<td width="32%" height="25" valign="middle" bgcolor="#0000FF" style="text-align: right; color: #FFF;">
			<?php 
			$islogin=checklogin();
			if($islogin==false){
			?>
			<a href="<?php echo $url;?>login" target="_parent" style="color:#FFF">登入</a>
			<?php
			}
			else{echo "目前登入: ".$islogin["user"]."(".het($islogin["name"]).")";
			?>
			<a href="<?php echo $url;?>logout" target="_parent" style="color:#FFF">登出</a>
			<?php
			}
			?>
			&nbsp;&nbsp;
		</td>
	</tr>
</table>