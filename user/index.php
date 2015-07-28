<html>
<?php
include_once("../func/checklogin.php");
include_once("../func/sql.php");
include_once("../func/url.php");
include_once("../func/log.php");
$login=checklogin();
if($login==false)header("Location: ../login/?from=user");
$editid=$login["id"];
if(is_numeric(@$_GET["id"]))$editid=@$_GET["id"];
$error="";
$message="";
$message2="";
$showdata=true;
$query=new query;
$query->column=array("name","email","power");
$query->table="account";
$query->where=array("id",$editid);
$query->limit=array(0,1);
$editdata=fetchone(SELECT($query));
if(isset($_POST["sid"])&&$editid!=@$_POST["sid"]){
	$error="有預設資料遭到修改，沒有任何修改動作被執行";
	insertlog($login["id"],$editid,"useredit",false,"attack");
	$showdata=false;
}
else if($editdata==""){
	$error="無此ID";
	insertlog($login["id"],$editid,"useredit",false,"no id");
	$showdata=false;
}
else{
	if($editid!=$login["id"]&&$login["power"]<=1){
		$error="你沒有權限更改別人的資料";
		insertlog($login["id"],$editid,"useredit",false,"no power");
		$showdata=false;
	}
	else if($login["power"]<$editdata["power"]){
		$error="無法更改較高權限的帳戶";
		insertlog($login["id"],$editid,"useredit",false,"higher power");
		$showdata=false;
	}
	else{
		if($editid!=$login["id"]){
			$message="注意!你正在更改其他人的資料";
		}
		$query=new query;
		$query->column=array("pwd");
		$query->table="account";
		$query->where=array("id",$login["id"]);
		$query->limit=array(0,1);
		$oldpwd=fetchone(SELECT($query))["pwd"];
		if(@$_POST['spwd1']!=""){
			if(crypt(@$_POST['spwd0'],$oldpwd)!=$oldpwd){
				$error="舊密碼錯誤";
				insertlog($login["id"],$editid,"useredit",false,"wrong old password");
			}else if(@$_POST["spwd1"]!=@$_POST["spwd2"]){
				$error="密碼不符";
				insertlog($login["id"],$editid,"useredit",false,"password not match");
			}else if(preg_match("/\s/", @$_POST["spwd1"])){
				$error="密碼不可有空白";
				insertlog($login["id"],$editid,"useredit",false,"password has space");
			}else if(!preg_match("/^.{4,}$/", @$_POST["spwd1"])){
				$error="密碼至少4個字";
				insertlog($login["id"],$editid,"useredit",false,"password length");
			}else{
				$query=new query;
				$query->table="account";
				$query->value=array("pwd",crypt(@$_POST['spwd1']));
				$query->where=array("id",$editid);
				UPDATE($query);
				insertlog($login["id"],$editid,"useredit",true,"edit password");
				if($message2=="")$message2.="已更新以下資料";
				$message2.=" 密碼";
			}
		}
		if(@$_POST['sname']!=""&&@$_POST['sname']!=$editdata["name"]){
			$query=new query;
			$query->table="account";
			$query->value=array("name",@$_POST['sname']);
			$query->where=array("id",$editid);
			UPDATE($query);
			insertlog($login["id"],$editid,"useredit",true,"edit name");
			if($message2=="")$message2.="已更新以下資料";
			$message2.=" 姓名";
		}
		if(@$_POST['semail']!=""&&@$_POST['semail']!=$editdata["email"]){
			if(!preg_match("/^[_a-z0-9-]+([.][_a-z0-9-]+)*@[a-z0-9-]+([.][a-z0-9-]+)*$/", @$_POST["semail"])){
				$error="郵件位址不正確";
				insertlog($login["id"],$editid,"useredit",false,"email format");
			}else{
				$query=new query;
				$query->table="account";
				$query->value=array("email",@$_POST['semail']);
				$query->where=array("id",$editid);
				UPDATE($query);
				insertlog($login["id"],$editid,"useredit",true,"edit email");
				if($message2=="")$message2.="已更新以下資料";
				$message2.=" 郵件 注意!你需要重新驗證郵件";
				$verifycode=md5(uniqid(rand(),true));
				$query=new query;
				$query->table="account";
				$query->value=array("verify",$verifycode);
				$query->where=array("id",$editid);
				UPDATE($query);
				mail($row["email"], "ELMS 帳戶驗證", "你剛剛更改了ELMS ( http://books.tfcis.org/ ) 的郵件\n需要重新驗證帳戶\n請點選此連結驗證帳戶: http://books.tfcis.org/verify/?code=".$verifycode."\n若沒有註冊請不要點選!!", "From: t16@tfcis.org");
				$query=new query;
				$query->table="session";
				$query->where=array("id",$editid);
				DELETE($query);
				$showdata=false;
				?><script>setTimeout(function(){location="../login"},5000);</script><?php
			}
		}
	}
}
if($message!=""&&$message2!="")$message.="<br>";
$message.=$message2;
$query=new query;
$query->column=array("name","email");
$query->table="account";
$query->where=array("id",$editid);
$query->limit=array(0,1);
$editdata=fetchone(SELECT($query));
?>
<head>
<meta charset="UTF-8">
<title>讀者資料查詢-TFcisBooks</title>
<link href="user.css" rel="stylesheet" type="text/css">
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
	if($showdata){
?>
<center>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="dfromh" colspan="3">&nbsp;</td>
</tr>
<tr>
	<td valign="top">
		<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td align="center"><h1>目前借閱</h1></td>
		</tr>
		<tr>
			<td>
				<table border="0" cellspacing="10" cellpadding="0">
				<tr>
					<td>分類</td>
					<td>ID</td>
					<td>書名</td>
					<td>來源</td>
				</tr>
				<?php
				$query=new query;
				$query->table="category";
				$row=SELECT($query);
				foreach($row as $temp){
					$cate[$temp["id"]]=$temp["name"];
				}
				$query=new query;
				$query->column=array("id","name","cat","lend","source");
				$query->table="booklist";
				$query->where=array("lend",$editid);
				$row=SELECT($query);
				consolelog($row);
				$noborrow=true;
				foreach($row as $book){
					$noborrow=false;
				?>
				<tr>
					<td><?php echo $cate[$book["cat"]]; ?></td>
					<td><a href="../bookinfo/?id=<?php echo $book["id"]; ?>"><?php echo $book["id"]; ?></a></td>
					<td><?php echo $book["name"]; ?></td>
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
		<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td align="center"><h1>更新資料</h1></td>
		</tr>
		<tr>
			<td>
				<form method="post">
					<input name="sid" type="hidden" id="sid" value="<?php echo $editid;?>">
					<table border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td valign="top" class="inputleft">舊密碼：</td>
							<td valign="top" class="inputright"><input name="spwd0" type="password" id="spwd0"></td>
						</tr>
						<tr>
							<td valign="top" class="inputleft">新密碼：</td>
							<td valign="top" class="inputright"><input name="spwd1" type="password" id="spwd1"></td>
						</tr>
						<tr>
							<td valign="top" class="inputleft">再確認：</td>
							<td valign="top" class="inputright"><input name="spwd2" type="password" id="spwd2"></td>
						</tr>
						<tr>
							<td valign="top" class="inputleft">姓名：</td>
							<td valign="top" class="inputright"><input name="sname" type="text" id="sname" value="<?php echo het($editdata["name"]);?>" maxlength="32"></td>
						</tr>
						<tr>
							<td valign="top" class="inputleft">郵件：</td>
							<td valign="top" class="inputright"><input name="semail" type="email" id="semail" value="<?php echo $editdata["email"];?>" maxlength="64"></td>
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