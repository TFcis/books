<html>
<?php
include_once(__DIR__."/../config/config.php");
include_once($config["path"]["sql"]);
include_once(__DIR__."/../func/checklogin.php");
include_once(__DIR__."/../func/log.php");
$error="";
$message="";
$data=checklogin();
if(!$data["login"]){
	header("Location: ".$data["url"]);
}else if(isset($_POST["bookid"])){
	if(!$data["power"])@$_POST["borrowuser"]=$data["account"];
	if(@$_POST["bookid"]==""){
		$error="圖書ID為空";
		insertlog($data["id"],0,"borrow",false,"bookid empty");
	}else if(@$_POST["borrowuser"]==""){
		$error="借閱使用者為空";
		insertlog($data["id"],0,"borrow",false,"user empty");
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
			insertlog($data["id"],0,"borrow",false,"no bookid:".@$_POST["bookid"]);
		}else if($acct==false){
			$error="無此使用者";
			insertlog($data["id"],0,"borrow",false,"no user:".@$_POST["borrowuser"]);
		}else if($book["lend"]!="0"){
			$error=$book["name"]."(".$_POST["bookid"].") 已有人借閱";
			insertlog($data["id"],$acct->id,"borrow",false,"already lead:".@$_POST["bookid"]);
		}else{
			$query=new query;
			$query->table="booklist";
			$query->value=array("lend",$acct->id);
			$query->where=array("id",@$_POST["bookid"]);
			UPDATE($query);
			insertlog($data["id"],$acct->id,"borrow",true,"book id=".@$_POST["bookid"]);
			$message="已將圖書 ".$book["name"]."(".$_POST["bookid"].") 借給 ".$acct->nickname."(".$acct->account.")";
			if($data["power"]<=1){
				$query=new query;
				$query->table="account";
				$query->where=array("power",2,">=");
				$query->limit="all";
				$row=SELECT($query);
				foreach($row as $temp){
					consolelog(mail($temp["email"], "ELMS 借閱通知", $acct["name"]." 剛剛借閱了".@$_POST["bookid"]."(".$book["name"].")\n圖書資料: http://books.tfcis.org/bookinfo/?id=".@$_POST["bookid"], "From: t16@tfcis.org"));
				}
			}
		}
	}
}
?>
<head>
<meta http-equiv="Content-Type" charset="UTF-8" name="viewport" content="width=device-width,user-scalable=yes">
<title>借書-TFcisBooks</title>
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
			<td><input name="bookid" type="number" min="1" id="bookid" value="<?php echo @$_GET["id"];?>"></td>
		</tr>
		<?php 
		if($data["power"]){
		?>
		<tr>
			<td>借閱使用者</td>
			<td><input name="borrowuser" type="text" id="borrowuser" value="<?php echo $data["account"];?>"></td>
		</tr>
		<?php 
		}
		?>
		<tr>
			<td colspan="2" align="center"><input type="submit" value="借書"></td>
		</tr>
		</table>
		</form>
	</td>
</tr>
</table>
</center>
</body>
</html>