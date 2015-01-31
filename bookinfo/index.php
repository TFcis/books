<html>
<?php
include_once("../func/sql.php");
include_once("../func/checklogin.php");
include_once("../func/consolelog.php");
?>
<head>
<meta charset="UTF-8">
<title>圖書資料-TFcisELMS</title>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
	include_once("../header.php");
	$bookinfo=mfa(SELECT(["id","name","cat","source"],"booklist",[["id",$_GET["id"]]]));
?>
<center>
<table width="0" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td height="50" colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" style="text-align: center"><h1>圖書資料</h1></td>
</tr>
<tr>
	<td>
		<table width="0" border="0" cellspacing="5" cellpadding="0">
		<tr>
			<td>ID</td>
			<td><?php echo $bookinfo["id"]; ?></td>
		</tr>
		<tr>
			<td>書名</td>
			<td><?php echo $bookinfo["name"]; ?></td>
		</tr>
		<tr>
			<td>分類</td>
			<td><?php echo mfa(SELECT(["name"],"category",[["id",$bookinfo["cat"]]]))["name"]; ?></td>
		</tr>
		<tr>
			<td>來源</td>
			<td><?php echo $bookinfo["source"]; ?></td>
		</tr>
		</table>
	</td>
</tr>
<?php
	if(checklogin()["power"]>=2){
?>
<tr>
	<td height="20"></td>
</tr>
<tr>
	<td align="center"><a href="borrow/?id=<?php echo $bookinfo["id"]; ?>">借閱此書</a>
	</td>
</tr>
<tr>
	<td align="center"><a href="return/?id=<?php echo $bookinfo["id"]; ?>">歸還此書</a>
	</td>
</tr>
<?php
	}
?>
</table>
</center>
</body>
</html>