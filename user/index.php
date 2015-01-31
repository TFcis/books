<html>
<?php
include_once("../func/checklogin.php");
include_once("../func/sql.php");
$login=checklogin();
if($login==false)header("Location: ../login/?from=user");
$editid=$login["id"];
if(is_numeric($_GET["id"]))$editid=$_GET["id"];
$error="";
$message="";
$message2="";
$showdata=true;
$editdata=mfa(SELECT(["name","email","power"],"account",[["id",$editid]]));
if(isset($_POST["sid"])&&$editid!=$_POST["sid"]){
	$error="有預設資料遭到修改，沒有任何修改動作被執行";
	$showdata=false;
}
else if($editdata==""){
	$error="無此ID";
	$showdata=false;
}
else{
	if($editid!=$login["id"]&&$login["power"]<=1){
		$error="你沒有權限更改別人的資料";
		$showdata=false;
	}
	else if($login["power"]<$editdata["power"]){
		$error="無法更改較高權限的帳戶";
		$showdata=false;
	}
	else{
		if($editid!=$login["id"])$message="注意!你正在更改其他人的資料";
		if($_POST['spwd']!=""){
			if($_POST["spwd"]!=$_POST["spwd2"]){
				$error="密碼不符";
			}else if(preg_match("/\s/", $_POST["spwd"])){
				$error="密碼不可有空白";
			}else if(!preg_match("/^.{4,}$/", $_POST["spwd"])){
				$error="密碼至少4個字";
			}else{
				UPDATE("account",[ ["pwd",crypt($_POST['spwd'])] ],[ ["id",$editid] ]);
				if($message2=="")$message2.="已更新以下資料";
				$message2.=" 密碼";
			}
		}
		if($_POST['sname']!=""&&$_POST['sname']!=$editdata["name"]){
			UPDATE("account",[ ["name",$_POST['sname']] ],[ ["id",$editid] ]);
			if($message2=="")$message2.="已更新以下資料";
			$message2.=" 姓名";
		}
		if($_POST['semail']!=""&&$_POST['semail']!=$editdata["email"]){
			if(!preg_match("/^[_a-z0-9-]+([.][_a-z0-9-]+)*@[a-z0-9-]+([.][a-z0-9-]+)*$/", $_POST["semail"])){
				$error="郵件位址不正確";
			}else{
				UPDATE("account",[ ["email",$_POST['semail']] ],[ ["id",$editid] ]);
				if($message2=="")$message2.="已更新以下資料";
				$message2.=" 郵件";
			}
		}
	}
}
if($message!=""&&$message2!="")$message.="<br>";
$message.=$message2;
$editdata=mfa(SELECT(["name","email"],"account",[["id",$editid]]));
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
	if($showdata){
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
			<td align="center"><h1>目前借閱</h1></td>
		</tr>
		<tr>
			<td>
				<table width="0" border="0" cellspacing="10" cellpadding="0">
				<tr>
					<td>分類</td>
					<td>ID</td>
					<td>書名</td>
					<td>來源</td>
				</tr>
				<?php
				$row=SELECT("*","category",null,null,"all");
				while($temp=mfa($row)){
					$cate[$temp["id"]]=$temp["name"];
				}
				$row=SELECT(["id","name","cat","lend","source"],"booklist",[["lend",$login["id"]]],null,"all");
				$noborrow=true;
				while($book=mfa($row)){
					$noborrow=false;
				?>
				<tr>
					<td><?php echo $cate[$book["cat"]]; ?></td>
					<td><a href="../bookinfo/?id=<?php echo $book["id"]; ?>"><?php echo $book["id"]; ?></a></td>
					<td><?php echo ($book["name"]); ?></td>
					<td><?php echo $book["source"]; ?></td>
				</tr>
				<?php
				}
				if($noborrow){
				?>
				<tr>
					<td colspan="4" align="center">無任何借閱</td>
				</tr>
				<?php
				}
				?>
				</table>
			</td>
		</tr>
		</table>
	</td>
	<td width="40"></td>
	<td valign="top">
		<table width="0" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td height="29">&nbsp;</td>
		</tr>
		<tr>
			<td align="center"><h1>更新資料</h1></td>
		</tr>
		<tr>
			<td>
				<form method="post">
					<input name="sid" type="hidden" id="sid" value="<?php echo $editid;?>">
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
							<td valign="top" class="inputright"><input name="sname" type="text" id="sname" value="<?php echo het($editdata["name"]);?>" maxlength="32"></td>
						</tr>
						<tr>
							<td valign="top" class="inputleft">郵件：</td>
							<td valign="top" class="inputright"><input name="semail" type="text" id="semail" value="<?php echo $editdata["email"];?>" maxlength="64"></td>
						</tr>
						<tr>
							<td align="center" colspan="2"><input type="submit" value="更新資料"></td>
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