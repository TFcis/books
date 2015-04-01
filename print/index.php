<html>
<?php
include_once("../func/sql.php");
include_once("../func/url.php");
include_once("../func/checklogin.php");
include_once("../func/consolelog.php");
?>
<head>
<meta charset="UTF-8">
<title>PRINT-TFcisBooks</title>
<link rel="icon" href="../res/icon.ico" type="image/x-icon">
<?php
include_once("../res/meta.php");
meta();
?>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
	include_once("../res/header.php");
?>
<center>
<h1>PRINT</h1>
<hr>
<?php
$row=SELECT("ELMS","*","category",null,null,"all");
while($temp=mfa($row)){
	$cate[$temp["id"]]=$temp["name"];
}
$row=SELECT("ELMS","*","booklist",null,null,"all");
?>
<table border="0" cellspacing="0" cellpadding="5">
<tr>
	<?php
	$count=0;
	while($bookinfo=mfa($row)){
	?>
	<td align="center">
	<?php
	/*$im = imagecreatefrompng("https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=http://books.tfcis.org/b?".$bookinfo["id"]);
	$im2 = ImageCreateTrueColor(100,100);
	imagecopyresized($im2,$im,0,0,20,20,100,100,110,110);
	imagepng($im2,"./".$bookinfo["id"].".png");
	imagedestroy($im);
	imagedestroy($im2); */
	?>
	<img src="<?php echo $bookinfo["id"];?>.png"><br>
	ID:<?php echo $bookinfo["id"]; ?>,Cat:<?php echo $cate[$bookinfo["cat"]]; ?>
	</td>
	<?php
	$count++;
	if($count%4==0)echo "</tr><tr>";
	}
	?>
</tr>
</tr>
</table>
</center>
</body>
</html>