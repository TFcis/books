<html>
<?php
include_once("../func/sql.php");
include_once("../func/checklogin.php");
include_once("../func/consolelog.php");
?>
<head>
<meta charset="UTF-8">
<title>館藏查詢-TFcisELMS</title>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
	include_once("../header.php");
?>
<center>
<table width="0" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td height="50" colspan="4">&nbsp;</td>
</tr>
<tr>
	<td colspan="3" style="text-align: center"><h1>館藏查詢</h1></td>
	<td rowspan="4" valign="top" style="text-align: center">
	<?php
	if($_GET["bookname"]!=""||$_GET["bookcat"]!=""||$_GET["bookid"]!=""){
		$temp=[["aval","0","!="]];
		if($_GET["bookname"]!="")array_push($temp,["name",htmlspecialchars($_GET["bookname"]),"REGEXP"]);
		if($_GET["bookcat"]!="")array_push($temp,["cat",$_GET["bookcat"]]);
		if($_GET["bookid"]!="")array_push($temp,["id",$_GET["bookid"]]);
		consolelog($temp);
		$row=SELECT("*","booklist",$temp);
		?>
		<table width="0" border="0" cellspacing="10" cellpadding="0">
		<tr>
			<td>分類</td>
			<td>ID</td>
			<td>書名</td>
			<td>借出</td>
		</tr>
		<?php
		while($book=mfa($row)){
			?>
			<tr>
				<td><?php echo $book["cat"]; ?></td>
				<td><?php echo $book["id"]; ?></td>
				<td><?php echo ($book["name"]); ?></td>
				<td><?php echo $book["lend"]; ?></td>
			</tr>
			<?php
		}
		?>
		</table>
		<?php
	}
	?>
	</td>
</tr>
<tr>
	<td valign="top" style="border-right-style: solid; border-left-width: thin; border-bottom-width: thin; border-right-width: thin; border-top-width: thin;">
		<table width="0" border="0" cellspacing="3" cellpadding="0">
		<tr>
			<td colspan="2">分類代碼</td>
		</tr>
		<?php
			$row=SELECT("*","category");
			while($cate=mfa($row)){
		?>
		<tr>
			<td><?php echo $cate["id"]; ?></td>
			<td><?php echo $cate["name"]; ?></td>
		</tr>
		<?php
		}
		?>
		</table>
	</td>
	<td valign="top">
		<form method="get">
		<table width="0" border="0" cellspacing="3" cellpadding="0">
		<tr>
			<td>書名</td>
			<td><input name="bookname" type="text" id="bookname"></td>
		</tr>
		<tr>
			<td>分類</td>
			<td><input name="bookcat" type="text" id="bookcat"></td>
		</tr>
		<tr>
			<td>編號</td>
			<td><input name="bookid" type="text" id="bookid"></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input type="submit" value="搜尋"></td>
		</tr>
		</table>
		</form>
	</td>
</tr>
</table>
</form>
</td>
</tr>
</table>
</center>
</body>
</html>