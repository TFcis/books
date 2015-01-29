<html>
<?php
include_once("../func/checklogin.php");
include_once("../func/sql.php");
$error="";
$message="";
$refresh="";
if(checklogin()){
	$message="你已經登入了";
	?><script>setTimeout(function(){history.back();},1000)</script><?php
}
else if(isset($_POST['user'])){
	$row = mfa(SELECT("*","account",[["user",$_POST['user']]],null,[0,1]));
	if($row==""){
		$error="無此帳號";
	}
	else if($row["power"]<=0){
		$error="此帳戶已遭封禁，無法登入";
	}else {
		if(crypt($_POST['pwd'],$row["pwd"])==$row["pwd"]){
			$cookie=md5(uniqid(rand(),true));
			setcookie("ELMScookie", $cookie, time()+86400, "/");
			INSERT("session",[["id",$row["id"]],["cookie",$cookie]]);
			$message="登入成功";
			?><script>setTimeout(function(){location="../";},1000)</script><?php
		}
		else $error="密碼錯誤";
	}
}
else if(isset($_POST['suser'])){
	$row = mfa(SELECT("*","account",[["user",$_POST['suser']]],[0,1]));
	if($row!=""){
		$error="已經有人註冊此帳號";
	}else if(!preg_match("/^[a-zA-Z]{1}[a-zA-Z0-9]{2,}$/", $_POST["suser"])){
		$error="帳號不符合格式 /^[a-zA-Z]{1}[a-zA-Z0-9]{2,}$/<br>應該由英文字開頭，僅包含英數，至少3個字";
	}else if($_POST["spwd"]!=$_POST["spwd2"]){
		$error="密碼不相符";
	}else if(preg_match("/\s/", $_POST["spwd"])){
		$error="密碼不可有空白";
	}else if(!preg_match("/^.{4,}$/", $_POST["spwd"])){
		$error="密碼至少4個字";
	}else if($_POST["sname"]==""){
		$error="姓名為空";
	}else if(!preg_match("/^[_a-z0-9-]+([.][_a-z0-9-]+)*@[a-z0-9-]+([.][a-z0-9-]+)*$/", $_POST["semail"])){
		$error="郵件位址不正確";
	}else{
		$row = mfa(SELECT(["MAX(id)"],"account"));
		$id=$row[0]+1;
		INSERT("account",[["id",$id],["user",$_POST["suser"]],["pwd",crypt($_POST["spwd"])],["email",$_POST["semail"]],["name",$_POST["sname"]]]);
		$message='註冊成功，請登入';
		?><script>setTimeout(function(){location="./";},1000)</script><?php
	}
}
?>
<head>
<meta charset="UTF-8">
<title>登入-TFcisELMS</title>
<link href="login.css" rel="stylesheet" type="text/css">
<link href="../res/css.css" rel="stylesheet" type="text/css">
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
	include_once("../header.php");
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
	}else{
?>
<center>
<table width="0" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top">
			<table width="0" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="29">&nbsp;</td>
				</tr>
				<tr>
					<td><h1>登入</h1></td>
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
									<td valign="top" class="inputright"><input name="user" type="text" value="<?php echo $_POST['user'];?>"></td>
								</tr>
								<tr>
									<td valign="top" class="inputleft">密碼：</td>
									<td valign="top" class="inputright"><input name="pwd" type="password"></td>
								</tr>
								<tr>
									<td height="45" colspan="2" align="center" valign="top"><input type="submit" value="登入"></td>
								</tr>
								<tr>
									<td align="center" colspan="2"><a href="../forgotpwd" target="_parent">忘記密碼</a></td>
								</tr>
							</table>
						</form>
					</td>
				</tr>
			</table>
		</td>
		<td width="20"></td>
		<td valign="top">
			<table width="0" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="29">&nbsp;</td>
				</tr>
				<tr>
					<td><h1>註冊</h1></td>
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
									<td valign="top" class="inputright"><input name="suser" type="text" id="suser" value="<?php echo $_POST['suser'];?>" placeholder="英文字開頭/僅含英數/至少3字"></td>
								</tr>
								<tr>
									<td valign="top" class="inputleft">密碼：</td>
									<td valign="top" class="inputright"><input name="spwd" type="password" id="spwd" placeholder="不含空白/至少4字"></td>
								</tr>
								<tr>
									<td valign="top" class="inputleft">確認：</td>
									<td valign="top" class="inputright"><input name="spwd2" type="password" id="spwd2" placeholder="與密碼相符"></td>
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
									<td align="center" colspan="2"><input type="submit" value="註冊"></td>
								</tr>
							</table>
						</form>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</center>
<?php
	}
?>
</body>
</html>