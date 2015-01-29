<html>
<?php
include_once("../func/sql.php");
include_once("../func/checklogin.php");
include_once("../func/consolelog.php");
$error="";
$message="";
$data=checklogin();
if($data==false)header("Location: ../login");
else if($data[5]<=1){
	$error="你沒有權限";
	?><script>setTimeout(function(){history.back();},1000)</script><?php
}
if(isset($_POST["bookid"])){
	if($_POST["bookid"]==""){
		$error="圖書ID為空";
	}
	else if($_POST["borrowuser"]==""){
		$error="借閱使用者為空";
	}
	else{
		$book=mfa(SELECT("*","booklist",[ ["id",$_POST["bookid"] ] ] ));
		$acct=mfa(SELECT("*","account",[ ["user",$_POST["borrowuser"] ] ] ));
		if($book==""){
			$error="無此圖書ID";
		}
		else if($acct==""){
			$error="無此使用者";
		}
		else {
			UPDATE( "booklist",[["lend",0] ],[["id",$_POST["bookid"]]]);
			$message=$acct["user"]."(".$acct["name"].") 已歸還圖書 ".$_POST["bookid"]."(".$book["name"].")";
		}
	}
}
?>
<head>
<meta charset="UTF-8">
<title>還書-TFcisELMS</title>
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
	if($data["power"]>=2){
?>
<center>

<table width="0" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td height="50" colspan="1">&nbsp;</td>
</tr>
<tr>
	<td colspan="1" style="text-align: center"><h1>還書</h1></td>
</tr>
<tr>
	<td>
		<form method="post">
		<table border="0" cellspacing="5" cellpadding="0">
		<tr>
			<td>書本ID</td>
			<td><input name="bookid" type="text" id="bookid" value="<?php echo $_GET["id"];?>"></td>
		</tr>
		<tr>
			<td>歸還使用者</td>
			<td><input name="borrowuser" type="text" id="borrowuser"></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input type="submit" value="還書"></td>
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