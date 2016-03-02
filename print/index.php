<html>
<?php
include_once(__DIR__."/../config/config.php");
include_once($config["path"]["sql"]);
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
if (!isset($_GET["start"]) || !isset($_GET["end"])) {
	echo "Not given start or end";
} else {
	$query=new query;
	$query->table="category";
	$row=SELECT($query);
	foreach ($row as $temp) {
		$category[$temp["id"]]=$temp["name"];
	}

	$query=new query;
	$query->table="booklist";
	$query->where=array(
		array("id",@$_GET["start"],">="),
		array("id",@$_GET["end"],"<=")
	);
	$row=SELECT($query);
	?>
	<table border="1" cellspacing="0" cellpadding="5">
	<tr>
		<?php
		$count=0;
		foreach ($row as $temp) {
		?>
		<td align="center">
		<img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=http://books.tfcis.org/b?<?php echo $temp["id"]; ?>"><br>
		ID:<?php echo $temp["id"]; ?>,Cat:<?php echo $category[$temp["cat"]]; ?>
		</td>
		<?php
		$count++;
		if($count%4==0)echo "</tr><tr>";
		}
		?>
	</tr>
	</tr>
	</table>
<?php
}
?>
</center>
</body>
</html>