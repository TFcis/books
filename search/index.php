<html>
<?php
include_once("../func/sql.php");
include_once("../func/url.php");
include_once("../func/checklogin.php");
include_once("../func/consolelog.php");
?>
<head>
<meta charset="UTF-8">
<title>館藏查詢-TFcisBooks</title>
<link href="../res/css.css" rel="stylesheet" type="text/css">
<link rel="icon" href="../res/icon.ico" type="image/x-icon">
<?php
include_once("../fbmeta.php");
?>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
	include_once("../header.php");
	$row=SELECT("*","category",null,null,"all");
	while($temp=mfa($row)){
		$cate[$temp["id"]]=$temp["name"];
	}
?>
<center>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="dfromh" colspan="4">&nbsp;</td>
</tr>
<tr>
	<td colspan="3" align="center" valign="top">
		<table width="0" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td colspan="2" align="center"><h2>篩選器</h2></td>
		</tr>
		<tr>
			<td valign="top">
			<form method="get">
				<table width="0" border="0" cellspacing="3" cellpadding="0">
				<tr>
					<td>書名</td>
					<td><input name="bookname" type="text" id="bookname" value="<?php echo $_GET["bookname"];?>"></td>
				</tr>
				<tr>
					<td>分類</td>
					<td>
					<select name="bookcat">
						<option value=""<?php echo($_GET["bookcat"]==""?" selected='selected'":""); ?>>所有分類</option>
					<?php
						foreach($cate as $i => $name){
					?>
						<option value="<?php echo $i; ?>"<?php echo($i==$_GET["bookcat"]?" selected='selected'":""); ?>><?php echo $name; ?></option>
					<?php
						}
					?>
					</select>
					</td>
				</tr>
				<tr>
					<td>編號</td>
					<td><input name="bookid" type="text" id="bookid" value="<?php echo $_GET["bookid"];?>" placeholder="僅可輸入數字" onKeyUp="this.value=this.value.replace(/[^\d]/g,'')"></td>
				</tr>
				<tr>
					<td colspan="2" align="center"><input type="submit" value="搜尋"></td>
				</tr>
				</table>
				</form>
			</td>
		</tr>
		</table>
	</td>
	<td rowspan="4" valign="top" style="text-align: center">
		<table width="0" border="0" cellspacing="10" cellpadding="0">
		<tr>
			<td>分類</td>
			<td>ID</td>
			<td>書名</td>
			<td>借出</td>
			<td>來源</td>
			<td>ISBN</td>
		</tr>
		<?php
		$temp=[["aval","0","!="]];
		if($_GET["bookname"]!="")array_push($temp,["name",htmlspecialchars($_GET["bookname"]),"REGEXP"]);
		if($_GET["bookcat"]!="")array_push($temp,["cat",$_GET["bookcat"]]);
		if($_GET["bookid"]!="")array_push($temp,["id",$_GET["bookid"]]);
		$row=SELECT(["id","name","cat","lend","source","ISBN"],"booklist",$temp,null,"all");
		while($book=mfa($row)){
		?>
		<tr>
			<td><?php echo $cate[$book["cat"]]; ?></td>
			<td><a href="../bookinfo/?id=<?php echo $book["id"]; ?>"><?php echo $book["id"]; ?></a></td>
			<td><?php echo ($book["name"]); ?></td>
			<td><?php echo ($book["lend"]==0?"在館內":"借閱中"); ?></td>
			<td><?php echo $book["source"]; ?></td>
			<td><a href="https://books.google.com.tw/books?vid=<?php echo $book["ISBN"]; ?>" target="_blank"><?php echo $book["ISBN"]; ?></a></td>
		</tr>
		<?php
		}
		?>
		</table>
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