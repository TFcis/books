<html>
<?php
include_once("../func/checklogin.php");
include_once("../func/sql.php");
include_once("../func/url.php");
include_once("../func/log.php");
$error="";
$message="";
$noshow=true;
$nosignup=true;
if(checklogin()){
	$message="你已經登入了";
	$noshow=false;
	?><script>setTimeout(function(){history.back();},1000)</script><?php
}else if(isset($_POST['user'])){
	$query=new query;
	$query->column=["id","pwd","power","verify"];
	$query->table="account";
	$query->where=array("user",$_POST['user']);
	$query->limit=array(0,1);
	$row=fetchone(SELECT($query));
	if($row==""){
		$error="無此帳號";
		insertlog(0,0,"login",false,"no user");
	}else if(crypt($_POST['pwd'],$row["pwd"])==$row["pwd"]){
		if($row["power"]<=0){
			$error="此帳戶已遭封禁，無法登入";
			insertlog(0,$row["id"],"login",false,"account block");
		}else if($row["verify"]!="OK"){
			$error="你尚未驗證帳號，請至信箱點選驗證連結，或是到<a href='../verify'>這裡</a>重發驗證信";
			insertlog(0,$row["id"],"login",false,"no verify");
		}else{
			$cookie=md5(uniqid(rand(),true));
			setcookie("ELMScookie", $cookie, time()+86400*7, "/");
			$query=new query;
			$query->table="session";
			$query->value=array(
				array("id",$row["id"]),
				array("cookie",$cookie)
			);
			INSERT($query);
			insertlog(0,$row["id"],"login");
			$message="登入成功";
			$noshow=false;
			?><script>setTimeout(function(){location="../<?php echo ($_GET["from"]==""?"home":$_GET["from"]);?>";},3000)</script><?php
		}
	}else{
		$error="密碼錯誤";
		insertlog(0,$row["id"],"login",false,"wrong password");
	}
}else if(isset($_POST['suser'])){
	$query=new query;
	$query->table="account";
	$query->where=array("user",$_POST['suser']);
	$query->limit=array(0,1);
	$row=fetchone(SELECT($query));
	if($row!=""){
		$error="已經有人註冊此帳號";
		insertlog(0,0,"signup",false,"user exist:".$_POST['suser']);
	}else if(!preg_match("/^[a-zA-Z]{1}[a-zA-Z0-9]{2,}$/", $_POST["suser"])){
		$error="帳號不符合格式 /^[a-zA-Z]{1}[a-zA-Z0-9]{2,}$/<br>應該由英文字開頭，僅包含英數，至少3個字";
		insertlog(0,0,"signup",false,"user format:".$_POST["suser"]);
	}else if($_POST["spwd"]!=$_POST["spwd2"]){
		$error="密碼不相符";
		insertlog(0,0,"signup",false,"password not match");
	}else if(preg_match("/\s/", $_POST["spwd"])){
		$error="密碼不可有空白";
		insertlog(0,0,"signup",false,"password has space");
	}else if(!preg_match("/^.{4,}$/", $_POST["spwd"])){
		$error="密碼至少4個字";
		insertlog(0,0,"signup",false,"password length");
	}else if($_POST["sname"]==""){
		$error="姓名為空";
		insertlog(0,0,"signup",false,"name empty");
	}else if(!preg_match("/^[_a-z0-9-]+([.][_a-z0-9-]+)*@[a-z0-9-]+([.][a-z0-9-]+)*$/", $_POST["semail"])){
		$error="郵件位址不正確";
		insertlog(0,0,"signup",false,"email format:".$_POST["semail"]);
	}else{
		$query=new query;
		$query->column="MAX(id)";
		$query->table="account";
		$query->limit=array(0,1);
		$row=fetchone(SELECT($query));
		$id=$row["MAX(id)"]+1;
		$verifycode=md5(uniqid(rand(),true));
		$query=new query;
		$query->table="account";
		$query->value=array(
			array("id",$id),
			array("user",$_POST["suser"]),
			array("pwd",crypt($_POST["spwd"])),
			array("email",$_POST["semail"]),
			array("name",$_POST["sname"]),
			array("verify",$verifycode)
		);
		INSERT($query);
		mail($_POST["semail"], "ELMS 帳戶驗證", "你剛剛註冊了ELMS ( http://books.tfcis.org/ ) 的帳戶\n請點選此連結驗證帳戶: http://books.tfcis.org/verify/?code=".$verifycode."\n若沒有註冊請不要點選!!", "From: t16@tfcis.org");
		insertlog(0,$id,"signup");
		$message='註冊成功，請先至信箱點選驗證帳戶連結後，始可登入';
		$nosignup=false;
	}
}
?>
<head>
<meta charset="UTF-8">
<title>登入-TFcisBooks</title>
<link href="login.css" rel="stylesheet" type="text/css">
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
								<tr>
									<td align="center" colspan="2"><a href="../verify" target="_parent">驗證郵件</a></td>
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