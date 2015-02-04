<html>
<?php
include_once("../func/sql.php");
include_once("../func/url.php");
include_once("../func/checklogin.php");
include_once("../func/consolelog.php");
$error="";
$message="";
$data=checklogin();
if($data==false){
	header("Location: ../login/?from=borrow");
}else if($data["power"]<=1){
	$error="你沒有權限";
	?><script>setTimeout(function(){location="../";},1000)</script><?php
}else if(isset($_POST["bookid"])){
	if($_POST["bookid"]==""){
		$error="圖書ID為空";
	}else if($_POST["borrowuser"]==""){
		$error="借閱使用者為空";
	}else{
		$book=mfa(SELECT(["name","lend"],"booklist",[ ["id",$_POST["bookid"] ] ] ));
		$acct=mfa(SELECT(["id","user","name"],"account",[ ["user",$_POST["borrowuser"] ] ] ));
		if($book==""){
			$error="無此圖書ID";
		}else if($acct==""){
			$error="無此使用者";
		}else if($book["lend"]!="0"){
			$error="此本書已經被其他人借閱";
		}else{
			UPDATE( "booklist",[["lend",$acct["id"]] ],[["id",$_POST["bookid"]]]);
			INSERT("log",[["operate",$data["id"]],["affect",$acct["id"]],["type","borrow"],["action","borrow book id=".$_POST["bookid"]]]);
			$message="已將圖書 ".$_POST["bookid"]."(".$book["name"].") 借給 ".$acct["user"]."(".$acct["name"].")";
		}
	}
}
?>
<head>
<meta http-equiv="Content-Type" charset="UTF-8" name="viewport" content="width=device-width,user-scalable=yes">
<title>借書-TFcisBooks</title>
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
	if($data["power"]>=2){
?>
<center>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="dfromh">&nbsp;</td>
</tr>
<tr>
	<td colspan="1" style="text-align: center"><h1>借書</h1></td>
</tr>
<tr>
	<td>
		<form method="post">
		<table border="0" cellspacing="5" cellpadding="0">
		<tr>
			<td>書本ID</td>
			<td><input name="bookid" type="number" min="1" id="bookid" value="<?php echo $_GET["id"];?>"></td>
		</tr>
		<tr>
			<td>借閱使用者</td>
			<td><input name="borrowuser" type="text" id="borrowuser"></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input type="submit" value="借書"></td>
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