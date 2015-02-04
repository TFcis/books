<html>
<?php
include_once("../func/checklogin.php");
include_once("../func/sql.php");
include_once("../func/url.php");
$error="";
$message="";
$noshow=true;
$nosignup=true;
if(checklogin()){
	$message="你已經登入了";
	$noshow=false;
	?><script>setTimeout(function(){history.back();},1000)</script><?php
}else if(isset($_POST['user'])){
	$row = mfa(SELECT(["id","pwd","power","verify"],"account",[["user",$_POST['user']]]));
	if($row==""){
		$error="無此帳號";
	}else if(crypt($_POST['pwd'],$row["pwd"])==$row["pwd"]){
		if($row["power"]<=0){
			$error="此帳戶已遭封禁，無法登入";
		}else if($row["verify"]!="OK"){
			$error="你尚未驗證帳號，請至信箱點選驗證連結，或是到<a href='../verify'>這裡</a>重發驗證信";
		}else{
			$cookie=md5(uniqid(rand(),true));
			setcookie("ELMScookie", $cookie, time()+86400*7, "/");
			INSERT("session",[["id",$row["id"]],["cookie",$cookie]]);
			$message="登入成功";
			$noshow=false;
			?><script>setTimeout(function(){location="../<?php echo ($_GET["from"]==""?"home":$_GET["from"]);?>";},3000)</script><?php
		}
	}else{
		$error="密碼錯誤";
	}
}else if(isset($_POST['suser'])){
	$row = mfa(SELECT("*","account",[["user",$_POST['suser']]]));
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
		$id=$row["MAX(id)"]+1;
		$verifycode=md5(uniqid(rand(),true));
		INSERT("account",[["id",$id],["user",$_POST["suser"]],["pwd",crypt($_POST["spwd"])],["email",$_POST["semail"]],["name",$_POST["sname"]],["verify",$verifycode]]);
		mail($_POST["semail"], "ELMS 帳戶驗證", "你剛剛註冊了ELMS ( http://books.tfcis.org/ ) 的帳戶\n請點選此連結驗證帳戶: http://books.tfcis.org/verify/?code=".$verifycode."\n若沒有註冊請不要點選!!", "From: t16@tfcis.org");
		$message='註冊成功，請先至信箱點選驗證帳戶連結後，始可登入';
		$nosignup=false;
	}
}
?>
<head>
<meta charset="UTF-8">
<title>登入-TFcisBooks</title>
<link href="login.css" rel="stylesheet" type="text/css">
<link href="../res/css.css" rel="stylesheet" type="text/css">
<link rel="icon" href="../res/icon.ico" type="image/x-icon">
<?php
include_once("../res/meta.php");
meta();
?>
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
		<td class="dfromh" colspan="3"></td>
	</tr>
	<tr>
		<td valign="top">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center"><h1>登入</h1></td>
				</tr>
				<tr>
					<td>
						<form method="post">
							<table border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td valign="top" class="inputleft">帳號：</td>
									<td valign="top" class="inputright"><input name="user" type="text" value="<?php echo $_POST['user'];?>" maxlength="32"></td>
								</tr>
								<tr>
									<td valign="top" class="inputleft">密碼：</td>
									<td valign="top" class="inputright"><input name="pwd" type="password"></td>
								</tr>
								<tr>
									<td height="45" colspan="2" align="center" valign="top"><input type="submit" value="登入"></td>
								</tr>
								<tr>
									<td align="center" colspan="2"><a href="javascript:alert('請向管理員要求重置密碼');" target="_parent">忘記密碼</a></td>
								</tr>
							</table>
						</form>
					</td>
				</tr>
			</table>
		</td>
		<?php
		if($nosignup){
		?>
		<td width="20"></td>
		<td valign="top">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center"><h1>註冊</h1></td>
				</tr>
				<tr>
					<td>
						<form method="post">
							<table border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td valign="top" class="inputleft">帳號：</td>
									<td valign="top" class="inputright"><input name="suser" type="text" id="suser" placeholder="英文字開頭/僅含英數/至少3字" value="<?php echo $_POST['suser'];?>" maxlength="32"></td>
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
									<td valign="top" class="inputright"><input name="sname" type="text" id="sname" value="<?php echo $_POST['sname'];?>" maxlength="32" placeholder="最長32字"></td>
								</tr>
								<tr>
									<td valign="top" class="inputleft">郵件：</td>
									<td valign="top" class="inputright"><input name="semail" type="email" id="semail" value="<?php echo $_POST['semail'];?>" maxlength="64"></td>
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
		<?php
		}
		?>
	</tr>
</table>
</center>
<?php
	}
?>
</body>
</html>