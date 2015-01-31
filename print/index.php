<html>
<?php
include_once("../func/sql.php");
include_once("../func/checklogin.php");
include_once("../func/consolelog.php");
?>
<head>
<meta charset="UTF-8">
<title>PRINT-TFcisELMS</title>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
	include_once("../header.php");
?>
<center>
<h1>PRINT</h1>
<hr>
<?php
$row=SELECT("*","category",null,null,"all");
while($temp=mfa($row)){
	$cate[$temp["id"]]=$temp["name"];
}
$row=SELECT("*","booklist",null,null,"all");
?>
<table width="0" border="0" cellspacing="0" cellpadding="0">
<tr>
	<?php
	$count=0;
	while($bookinfo=mfa($row)){
	?>
	<td>
	<img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=http://www.tfcis.org/ELMS/bookinfo/?id=<?php echo $bookinfo["id"];?>"><br>
	ＩＤ：<?php echo $bookinfo["id"]; ?><br>
	書名：<?php echo $bookinfo["name"]; ?><br>
	分類：<?php echo $cate[$bookinfo["cat"]]; ?><br>
	來源：<?php echo $bookinfo["source"]; ?><br>
	</td>
	<?php
	$count++;
	if($count%2==0)echo "</tr><tr>";
	}
	?>
</tr>
</tr>
</table>
</center>
</body>
</html>