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
}else if($_GET["code"]!=""){
	$query=new query;
	$query->column=array("id");
	$query->table="account";
	$query->where=array("verify",$_GET["code"]);
	$query->limit=array(0,1);
	$row=fetchone(SELECT($query));
	if($row==""){
		$error="驗證碼錯誤";
		insertlog(0,$row["id"],"verify",false,"wrong code");
	}else {
		$query=new query;
		$query->table="account";
		$query->value=array("verify","OK");
		$query->where=array("verify",$_GET["code"]);
		UPDATE($query);
		insertlog(0,$row["id"],"verify");
		$message="已成功驗證帳號";
		?><script>setTimeout(function(){location="../login";},3000)</script><?php
		$noshow=false;
	}
}else if(isset($_POST['user'])){
	$query=new query;
	$query->column=array("id","pwd","power","email","verify");
	$query->table="account";
	$query->where=array("user",$_POST['user']);
	$query->limit=array(0,1);
	$row=fetchone(SELECT($query));
	if($row==""){
		$error="無此帳號";
		insertlog(0,0,"verify",false,"no user");
	}else if(crypt($_POST['pwd'],$row["pwd"])==$row["pwd"]){
		if($row["power"]<=0){
			$error="此帳戶已遭封禁，無法驗證";
			insertlog(0,$row["id"],"verify",false,"account block");
		}else if($row["verify"]=="OK"){
			$error="此帳戶已經驗證過囉";
			insertlog(0,$row["id"],"verify",false,"already verify");
			?><script>setTimeout(function(){location="../login"},3000)</script><?php
		}else{
			$cookie=md5(uniqid(rand(),true));
			setcookie("ELMScookie", $cookie, time()+86400*7, "/");
			$verifycode=md5(uniqid(rand(),true));
			$query=new query;
			$query->table="account";
			$query->value=array("verify",$verifycode);
			$query->where=array("user",$_POST['user']);
			UPDATE($query);
			insertlog(0,$row["id"],"verify",true,"resend verify email");
			mail($row["email"], "ELMS 帳戶驗證", "你剛剛重新發送ELMS ( http://books.tfcis.org/ ) 的驗證信\n請點選此連結驗證帳戶: http://books.tfcis.org/verify/?code=".$verifycode."\n若沒有註冊請不要點選!!\n舊的驗證碼將失效", "From: t16@tfcis.org");
			$message='已重新發送驗證信，請先至信箱點選驗證帳戶連結後，始可登入；舊的驗證碼將失效';
			?><script>setTimeout(function(){location="../login";},10000)</script><?php
			$noshow=false;
		}
	}else{
		$error="密碼錯誤";
		insertlog(0,$row["id"],"verify",false,"wrong password");
	}
}
?>
<head>
<meta charset="UTF-8">
<title>重發驗證信-TFcisBooks</title>
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
					<td align="center"><h1>重發驗證信</h1></td>
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
									<td height="45" colspan="2" align="center" valign="top"><input type="submit" value="重發驗證信"></td>
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