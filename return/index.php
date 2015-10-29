<html>
<?php
include_once(__DIR__."/../config/config.php");
include_once($config["path"]["sql"]);
include_once(__DIR__."/../func/checklogin.php");
include_once(__DIR__."/../func/log.php");
$error="";
$message="";
$data=checklogin();
if($data["login"]===false){
	header("Location: ../login/?from=return");
}else if(!$data["power"]){
	$error="你沒有權限";
	insertlog($data["id"],0,"return",false,"no power");
	?><script>setTimeout(function(){history.back();},1000)</script><?php
}else if(isset($_POST["bookid"])){
	if(@$_POST["bookid"]==""){
		$error="圖書ID為空";
		insertlog($data["id"],0,"return",false,"bookid empty");
	}else if(@$_POST["borrowuser"]==""){
		$error="借閱使用者為空";
		insertlog($data["id"],0,"return",false,"user empty");
	}else{
		$query=new query;
		$query->column=array("*");
		$query->table="booklist";
		$query->where=array("id",@$_POST["bookid"]);
		$query->limit=array(0,1);
		$book=fetchone(SELECT($query));
		$acct=login_system::getinfobyaccount(@$_POST["borrowuser"]);
		if($book==""){
			$error="無此圖書ID";
			insertlog($data["id"],0,"return",false,"no bookid:".@$_POST["bookid"]);
		}else if($acct==""){
			$error="無此使用者";
			insertlog($data["id"],0,"return",false,"no user:".@$_POST["borrowuser"]);
		}else if($book["lend"]!=$acct["id"]){
			$error="使用者 ".$acct["nickname"]." 沒有借閱圖書 ".$book["id"]." ".$book["name"];
			insertlog($data["id"],$acct["id"],"return",false,"no lead:".@$_POST["bookid"]);
		}else{
			$query=new query;
			$query->table="booklist";
			$query->value=array("lend",0);
			$query->where=array("id",@$_POST["bookid"]);
			UPDATE($query);
			insertlog($data["id"],$acct["id"],"return",true,"book id=".@$_POST["bookid"]);
			$message=$acct["nickname"]." 已歸還圖書 ".@$_POST["bookid"]."(".$book["name"].")";
		}
	}
}
?>
<head>
<meta http-equiv="Content-Type" charset="UTF-8" name="viewport" content="width=device-width,user-scalable=yes">
<title>還書-TFcisBooks</title>
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
	if($data["power"]>0){
?>
<center>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="dfromh">&nbsp;</td>
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
			<td><input name="bookid" type="number" min="1" id="bookid" value="<?php echo @$_GET["id"];?>"></td>
		</tr>
		<tr>
			<td>歸還使用者</td>
			<?php
			if(isset($_GET["id"])){
				$query=new query;
				$query->column=array("id","name","lend");
				$query->table="booklist";
				$query->where=array("id",@$_GET["id"]);
				$query->limit=array(0,1);
				$book=fetchone(SELECT($query));
				$booklend=$book["lend"];
				if($booklend==0){$booklend="";}
				else{
					$query=new query;
					$query->column=array("id","user","name");
					$query->table="account";
					$query->where=array("id",$booklend);
					$query->limit=array(0,1);
					$acct=fetchone(SELECT($query));
					$booklend=$acct["user"];
				}
			}else $booklend="";
			?>
			<td><input name="borrowuser" type="text" id="borrowuser" value="<?=$booklend?>"></td>
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