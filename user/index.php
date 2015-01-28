<html>
<?php
include_once("../func/checklogin.php");
include_once("../func/sql.php");
$data=checklogin();
if($data==false)header("Location: ../login");
$id=$data["id"];
$name=$data["name"];
$email=$data["email"];
$error="";
$message="";
if($_POST['spwd']!=""){
	if($_POST["spwd"]!=$_POST["spwd2"]){
		$error="密碼不符";
	}else if(preg_match("/\s/", $_POST["spwd"])){
		$error="密碼不可有空白";
	}else if(!preg_match("/^.{4,}$/", $_POST["spwd"])){
		$error="密碼至少4個字";
	}else{
		UPDATE("account",[ ["pwd",crypt($_POST['spwd'])] ],[ ["id",$id] ]);
		if($message=="")$message="已更新以下資料:";
		else $message.=" ";
		$message.="密碼";
	}
}
if($_POST['sname']!=""&&$_POST['sname']!=$name){
	UPDATE("account",[ ["name",$_POST['sname']] ],[ ["id",$id] ]);
	if($message=="")$message="已更新以下資料:";
	else $message.=" ";
	$message.="姓名";
}
if($_POST['semail']!=""&&$_POST['semail']!=$email){
	if(!preg_match("/^[_a-z0-9-]+([.][_a-z0-9-]+)*@[a-z0-9-]+([.][a-z0-9-]+)*$/", $_POST["semail"])){
		$error="郵件位址不正確";
	}else{
		UPDATE("account",[ ["email",$_POST['semail']] ],[ ["id",$id] ]);
		if($message=="")$message="已更新以下資料:";
		else $message.=" ";
		$message.="郵件";
	}
}
$data=checklogin();
$name=$data["name"];
$email=$data["email"];
?>
<head>
<meta charset="UTF-8">
<title>讀者資料查詢-TFcisELMS</title>
<link href="user.css" rel="stylesheet" type="text/css">
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
	}
?>
<center>
<table width="0" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height="29">&nbsp;</td>
	</tr>
	<tr>
		<td><h1>更新資料</h1></td>
	</tr>
	<tr>
		<td height="0">&nbsp;</td>
	</tr>
	<tr>
		<td>
			<form method="post">
				<table width="0" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td valign="top" class="inputleft">密碼：</td>
						<td valign="top" class="inputright"><input name="spwd" type="password" id="spwd"></td>
					</tr>
					<tr>
						<td valign="top" class="inputleft">確認：</td>
						<td valign="top" class="inputright"><input name="spwd2" type="password" id="spwd2"></td>
					</tr>
					<tr>
						<td valign="top" class="inputleft">姓名：</td>
						<td valign="top" class="inputright"><input name="sname" type="text" id="sname" value="<?php echo het($name);?>"></td>
					</tr>
					<tr>
						<td valign="top" class="inputleft">郵件：</td>
						<td valign="top" class="inputright"><input name="semail" type="text" id="semail" value="<?php echo $email;?>"></td>
					</tr>
					<tr>
						<td align="center" colspan="2"><input type="submit" value="更新資料"></td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
</table>
</center>
</body>
</html>