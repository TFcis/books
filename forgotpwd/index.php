<html>
<?php
include_once("../func/checklogin.php");
include_once("../func/sql.php");
include_once("../func/url.php");
$error="";
$message="";
$noshow=true;
if(checklogin()){
	$message="你已經登入了";
	$noshow=false;
	?><script>setTimeout(function(){history.back();},1000)</script><?php
}
if(isset($_POST['suser'])){
	$row=mfa(SELECT(["id"],"account",[ ["user",$_POST['suser']],["email",$_POST['semail']],["name",$_POST['sname']] ]));
	if($row==""){
		$error="資料錯誤";
	}else if($_POST["spwd"]!=$_POST["spwd2"]){
		$error="密碼不符";
	}else{
		$newpwd=substr(md5(uniqid(rand(),true)),0,6);
		UPDATE("account",[ ["pwd",crypt($newpwd)] ],[ ["user",$_POST['suser']] ]);
		$message="你的密碼已更新為".$newpwd."，請登入以修改密碼";
	}
}
?>
<head>
<meta charset="UTF-8">
<title>密碼救援-TFcisBooks</title>
<link href="forgotpwd.css" rel="stylesheet" type="text/css">
<?php
include_once("../res/meta.php");
meta();
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
	include_once("../res/header.php");
	if($error!=""){
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center" valign="middle" bgcolor="#F00" class="message"><?php echo $error;?></td>
	</tr>
</table>
<?php
	}
	if($message!=""){
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center" valign="middle" bgcolor="#0A0" class="message"><?php echo $message;?></td>
	</tr>
</table>
<?php
	}
	if($noshow){
?>
<center>
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="dfromh">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><h1>密碼救援</h1></td>
	</tr>
	<tr>
		<td>
			<form method="post">
				<table border="0" cellspacing="0" cellpadding="0">
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