<html>
<?php
include_once("func/url.php");
include_once("func/checklogin.php");
include_once("func/sql.php");
?>
<head>
<meta charset="UTF-8">
<title>頁首-TFcisELMS</title>
<script src="<?php echo $url;?>res/jquery.min.js"></script>
</head>
<body topmargin="0" leftmargin="0" bottommargin="0">
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
			<script>
			function admin(){
				if(document.all.admindiv.style.display=="none"){
					$("#admindiv").show("slow");
					manage.innerHTML="管理&nbsp;&lt;&nbsp;";
				}else{
					$("#admindiv").hide("slow");
					manage.innerHTML="管理&nbsp;&gt;&nbsp;";
				}
			}
			</script>
			<div style="float:left">
			&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $url;?>" target="_parent" style="color:#FFF" >首頁</a>&nbsp;|&nbsp;<a href="<?php echo $url;?>search" target="_parent" style="color:#FFF">館藏查詢</a>&nbsp;|&nbsp;<a href="<?php echo $url;?>user" target="_parent" style="color:#FFF">讀者資料查詢</a>&nbsp;|&nbsp;<a name="manage" id="manage" style="color:#FFF" onClick="admin();">管理&nbsp;&gt;&nbsp;</a>
			</div>
			<div id="admindiv" style=" display:none; float:left;">
			<a href="<?php echo $url;?>borrow" target="_parent" style="color:#FFF">借書</a>&nbsp;|&nbsp;<a href="<?php echo $url;?>return" target="_parent" style="color:#FFF">還書</a>&nbsp;|&nbsp;<a href="<?php echo $url;?>managebook" target="_parent" style="color:#FFF">圖書</a>&nbsp;|&nbsp;<a href="<?php echo $url;?>manageuser" target="_parent" style="color:#FFF">使用者</a>
			</div>
		</td>
		<td width="32%" height="25" valign="middle" bgcolor="#0000FF" style="text-align: right; color: #FFF;">
			<?php 
			$islogin=checklogin();
			if($islogin==false){
			?>
			<a href="<?php echo $url;?>login" target="_parent" style="color:#FFF">登入</a>
			<?php
			}
			else{ echo "目前登入: ".$islogin[1]."(".$islogin[3].")";
			?>
			<a href="<?php echo $url;?>logout" target="_parent" style="color:#FFF">登出</a>
			<?php
			}
			?>
			&nbsp;&nbsp;
		</td>
	</tr>
</table>
</body>
</html>