<html>
<?php
include_once("../func/checklogin.php");
include_once("../func/sql.php");
$message="";
if(isset($_POST['suser'])){
	$row = sql("SELECT * FROM `account` WHERE `user`='".$_POST['suser']."' AND `email` = '".$_POST['semail']."' AND `name` = '".$_POST['sname']."' LIMIT 0,1");
	if($row==""){
		$message="資料錯誤";
	}
	else {
		$id=$row[0];
		$pwd=$row[2];
		if(crypt($_POST['pwd'],$pwd)==$pwd){
			$cookie=md5(uniqid(rand(),true));
			setcookie("ELMScookie", $cookie, time()+86400, "/");
			sql("INSERT INTO `elms`.`session` (`id`, `time`, `cookie`) VALUES ('".$id."', DATE_ADD(UTC_TIMESTAMP(),INTERVAL 8 HOUR), '".$cookie."');",false);
			header('Location: ../');
		}
		else $message="密碼錯誤";
	}
}
?>
<head>
<meta charset="UTF-8">
<title>登入-TFcisELMS</title>
<link href="forgotpwd.css" rel="stylesheet" type="text/css">
<link href="../res/css.css" rel="stylesheet" type="text/css">
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
	include_once("header.php");
	if($message!=""){
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center" valign="middle" bgcolor="#F00" class="message"><?php echo $message;?></td>
	</tr>
</table>
<?php
	}
	if(checklogin()){
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center" valign="middle" bgcolor="#0A0" class="message">你已經登入了
		</td>
	</tr>
</table>
<script>setTimeout(function(){location="../";},1000)</script>
<?php
	}else{
?>
<center>
<table width="0" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height="29">&nbsp;</td>
	</tr>
	<tr>
		<td><h1>密碼救援</h1></td>
	</tr>
	<tr>
		<td height="0">&nbsp;</td>
	</tr>
	<tr>
		<td>
			<form method="post">
				<table width="0" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td valign="top" class="inputleft">帳號：</td>
						<td valign="top" class="inputright"><input name="suser" type="text" id="suser" value="<?php echo $_POST['suser'];?>"></td>
					</tr>
					<tr>
						<td valign="top" class="inputleft">姓名：</td>
						<td valign="top" class="inputright"><input name="sname" type="text" id="sname" value="<?php echo $_POST['sname'];?>"></td>
					</tr>
					<tr>
						<td valign="top" class="inputleft">郵件：</td>
						<td valign="top" class="inputright"><input name="semail" type="text" id="semail" value="<?php echo $_POST['semail'];?>"></td>
					</tr>
					<tr>
						<td valign="top" class="inputleft">新密碼：</td>
						<td valign="top" class="inputright"><input name="semail" type="text" id="semail" value="<?php echo $_POST['semail'];?>"></td>
					</tr>
					<tr>
						<td valign="top" class="inputleft">確認：</td>
						<td valign="top" class="inputright"><input name="semail" type="text" id="semail" value="<?php echo $_POST['semail'];?>"></td>
					</tr>
					<tr>
						<td align="center" colspan="2"><input type="submit" value="重設密碼"></td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
</table>
</center>
<?php
	}
?>
</body>
</html>