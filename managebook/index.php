<html>
<?php
include_once("../func/sql.php");
include_once("../func/checklogin.php");
include_once("../func/consolelog.php");
$error="";
$message="";
$data=checklogin();
if($data==false)header("Location: ../login");
else if($data["power"]<=1){
	$error="你沒有權限";
	?><script>setTimeout(function(){location="../"},1000);</script><?php
}
if(isset($_POST["catdelid"])){
	DELETE("category",[ ["id",$_POST["catdelid"]] ]);
	$message="已刪除分類 ID=".$_POST["catdelid"]." 名稱=".$_POST["catdelname"];
}
if(isset($_POST["addcat"])){
	$row=mfa(SELECT( "*","category",[ [ "id",$_POST["id"] ] ] ));
	if($_POST["id"]=="")$error="ID為空";
	else if($_POST["name"]=="")$error="名稱為空";
	else if($row)$error="已有此ID";
	else{
		INSERT( "category",[ [ "id",$_POST["id"] ],[ "name",$_POST["name"] ] ] );
		$message="已增加分類 ID=".$_POST["id"]." 名稱=".$_POST["name"];
	}
}
if(isset($_POST["editcat"])){
	$row=mfa(SELECT( "*","category",[ [ "id",$_POST["id"] ] ] ));
	if($_POST["id"]=="")$error="ID為空";
	else if(!$row)$error="無此ID";
	else if($_POST["name"]=="")$error="名稱為空";
	else{
		UPDATE( "category",[ ["name",$_POST["name"]] ],[ ["id",$_POST["id"]] ] );
		$message="已修改分類 ID=".$_POST["id"]." 名稱=".$_POST["name"];
	}
}
$row=SELECT("*","category");
while($temp=mfa($row)){
	$cate[$temp["id"]]=$temp["name"];
}
$bookavaltext=["隱藏","顯示"];
if(isset($_POST["avalid"])){
	UPDATE( "booklist",[ ["aval",(1-$_POST["aval"]) ] ],[ ["id",$_POST["avalid"]] ] );
	$message="已經圖書 ID=".$_POST["avalid"]." ".$bookavaltext[1-$_POST["aval"]];
}
if(isset($_POST["bookdelid"])){
	DELETE("booklist",[ ["id",$_POST["bookdelid"]] ]);
	$message="已刪除圖書 ID=".$_POST["bookdelid"];
}
if(isset($_POST["addbook"])){
	if($_POST["name"]=="")$error="書名為空";
	else if($_POST["cat"]=="")$error="分類為空";
	else{
		$row=mfa(SELECT(["MAX(id)"],"booklist"));
		$newid=$row[0]+1;
		INSERT( "booklist",[ [ "id",$newid ],[ "name",$_POST["name"]],[ "cat",$_POST["cat"] ] ] );
		$message="已增加圖書 ID=".$newid." 書名=".$_POST["name"]." 分類=".$cate[$_POST["cat"]];
	}
}
if(isset($_POST["editbook"])){
	if($_POST["id"]=="")$error="ID為空";
	else if($_POST["name"]=="")$error="書名為空";
	else if($_POST["cat"]=="")$error="分類為空";
	else{
		UPDATE( "booklist",[ ["name",(1-$_POST["name"]) ],["cat",$_POST["cat"] ] ],[ ["id",$_POST["id"]] ] );
		$message="已修改圖書 ID=".$_POST["id"]." 書名=".$_POST["name"]." 分類=".$cate[$_POST["cat"]];
	}
}
?>
<head>
<meta charset="UTF-8">
<title>圖書管理-TFcisELMS</title>
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
<td valign="top">

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td height="50" colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><h1>分類管理</h1></td>
</tr>
<tr>
	<td colspan="2" align="center">
		<table border="1" cellspacing="0" cellpadding="2">
		<div style="display:none">
			<form method="post" id="catdel">
				<input name="catdelid" type="hidden" id="catdelid">
				<input name="catdelname" type="hidden" id="catdelname">
			</form>
		</div>
		<tr>
			<td>ID</td>
			<td>名稱</td>
			<td>管理</td>
		</tr>
		<?php
			foreach($cate as $i => $temp){
		?>
			<tr>
			<td><?php echo $i; ?></td>
			<td><?php echo $temp; ?></td>
			<td><input type="button" value="刪除" onClick="catdelid.value='<?php echo $i; ?>';catdelname.value='<?php echo $temp; ?>';catdel.submit();" ></td>
			</tr>
		<?php
			}
		?>
	</table>
	</td>
</tr>
<tr>
	<td height="20" colspan="2"></td>
</tr>
<tr>
	<td align="center" valign="top">
	<form method="post">
		
		<input name="addcat" type="hidden" value="">
		<table width="0" border="0" cellspacing="3" cellpadding="0">
		<tr>
			<td colspan="2" align="center"><h2>新增</h2></td>
		</tr>
		<tr>
			<td>ID</td>
			<td><input name="id" type="text" id="id"></td>
		</tr>
		<tr>
			<td>名稱</td>
			<td><input name="name" type="text" id="name"></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input type="submit" value="新增"></td>
		</tr>
		</table>
	</form>
	</td>
</tr>
<tr>
	<td height="20" colspan="2"></td>
</tr>
<tr>
	<td valign="top">
	<form method="post">
		<input name="editcat" type="hidden" value="">
		<table width="0" border="0" cellspacing="3" cellpadding="0">
		<tr>
			<td colspan="2" align="center"><h2>修改</h2></td>
		</tr>
		<tr>
			<td>ID</td>
			<td><input name="id" type="text" id="id"></td>
		</tr>
		<tr>
			<td>名稱</td>
			<td><input name="name" type="text" id="name"></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input type="submit" value="修改"></td>
		</tr>
		</table>
	</form>
	</td>
</tr>
</table>

</td>
<td width="50"></td>
<td valign="top">

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td height="50">&nbsp;</td>
</tr>
<tr>
	<td align="center" ><h1>圖書管理</h1></td>
</tr>
<tr>
	<td valign="top">
		<div style="display:none">
			<form method="post" id="bookaval">
				<input name="avalid" type="hidden" id="avalid">
				<input name="aval" type="hidden" id="aval">
			</form>
			<form method="post" id="bookdel">
				<input name="bookdelid" type="hidden" id="bookdelid">
			</form>
		</div>
		<table border="1" cellspacing="0" cellpadding="2">
		<tr>
			<td>分類</td>
			<td>ID</td>
			<td>書名</td>
			<td>借出</td>
			<td>資訊</td>
			<td>管理</td>
		</tr>
		<?php
		$row=SELECT( "*","booklist",null,[["id","ASC"]] );
		while($book=mfa($row)){
			?>
			<tr>
				<td><?php echo $cate[$book["cat"]]; ?></td>
				<td><?php echo $book["id"]; ?></td>
				<td><?php echo htmlspecialchars($book["name"],ENT_QUOTES); ?></td>
				<td><?php echo $book["lend"]; ?></td>
				<td><?php echo($book["aval"]==0?"隱藏":""); ?></td>
				<td>
				<input type="button" value="<?php echo $bookavaltext[1-$book["aval"]];?>" onClick="avalid.value=<?php echo $book["id"]; ?>;aval.value=<?php echo $book["aval"]; ?>;bookaval.submit();">
				<input type="button" value="刪除" onClick="if(!confirm('確認刪除?'))return false;bookdelid.value=<?php echo $book["id"]; ?>;bookdel.submit();">
				</td>
			</tr>
			<?php
		}
		?>
	</table>
	</td>
</tr>
<tr>
	<td height="20"></td>
</tr>
<tr>
	<td align="center" valign="top">
	<form method="post">
		<input name="addbook" type="hidden" value="true">
		<table width="0" border="0" cellspacing="3" cellpadding="0">
		<tr>
			<td align="center" colspan="2"><h2>新增</h2></td>
		</tr>
		<tr>
			<td>書名</td>
			<td><input name="name" type="text" id="name"></td>
		</tr>
		<tr>
			<td>分類</td>
			<td><input name="cat" type="text" id="cat"></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input type="submit" value="新增"></td>
		</tr>
		</table>
	</form>
	</td>
</tr>
<tr>
	<td height="20"></td>
</tr>
<tr>
	<td align="center" valign="top">
	<form method="post">
		<input name="editbook" type="hidden" value="true">
		<table width="0" border="0" cellspacing="3" cellpadding="0">
		<tr>
			<td align="center" colspan="2"><h2>修改</h2></td>
		</tr>
		<tr>
			<td>ID</td>
			<td><input name="id" type="text" id="id"></td>
		</tr>
		<tr>
			<td>書名</td>
			<td><input name="name" type="text" id="name"></td>
		</tr>
		<tr>
			<td>分類</td>
			<td><input name="cat" type="text" id="cat"></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input type="submit" value="修改"></td>
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