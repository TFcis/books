<html>
<?php
include_once(__DIR__."/../config/config.php");
include_once($config["path"]["sql"]);
include_once(__DIR__."/../func/checklogin.php");
$login=checklogin();
if(!$login["login"])header("Location: ".$login["url"]);
?>
<head>
<meta charset="UTF-8">
<title>館藏查詢-TFcisBooks</title>
<?php
include_once(__DIR__."/../res/meta.php");
meta();
?>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
	include_once(__DIR__."/../res/header.php");
	if(@$_GET["bookid"]!="")header("Location: ../bookinfo/?id=".@$_GET["bookid"]);
	$query=new query;
	$query->table="category";
	$row=SELECT($query);
	foreach($row as $temp){
		$cate[$temp["id"]]=$temp["name"];
	}
	$temp=array();
	if(isset($_GET["lend"])&&$_GET["lend"]!="all")array_push($temp,["aval",@$_GET["lend"]]);
	if(@$_GET["bookname"]!="")array_push($temp,["name",str_replace($_GET["bookname"],"+","[+]"),"REGEXP"]);
	if(@$_GET["bookcat"]!="")array_push($temp,["cat",@$_GET["bookcat"]]);
	$query=new query;
	$query->table="booklist";
	$query->where=$temp;
	$query->order=[["cat"],["name"]];
	$row=SELECT($query);
	foreach($row as $temp){
		if(@$booklist[$temp["name"]]["id"]!="")$booklist[$temp["name"]]["id"].=",";
		@$booklist[$temp["name"]]["id"].=$temp["id"];
		@$booklist[$temp["name"]]["count"]++;
		@$booklist[$temp["name"]]["ISBN"]=$temp["ISBN"];
		@$booklist[$temp["name"]]["cat"]=$temp["cat"];
		if($temp["lend"]==0)@$booklist[$temp["name"]]["aval"]++;
	}
?>
<center>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="dfromh" colspan="4">&nbsp;</td>
</tr>
<tr>
	<td colspan="3" align="center" valign="top">
		<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td colspan="2" align="center"><h2>篩選器</h2></td>
		</tr>
		<tr>
			<td valign="top">
			<form method="get">
				<table border="0" cellspacing="3" cellpadding="0">
				<tr>
					<td>書名</td>
					<td><input name="bookname" type="text" id="bookname" value="<?php echo @$_GET["bookname"];?>"></td>
				</tr>
				<tr>
					<td>分類</td>
					<td>
					<select name="bookcat">
						<option value=""<?php echo(@$_GET["bookcat"]==""?" selected='selected'":""); ?>>所有分類</option>
					<?php
						foreach($cate as $i => $name){
					?>
						<option value="<?php echo $i; ?>"<?php echo($i==@$_GET["bookcat"]?" selected='selected'":""); ?>><?php echo $name; ?></option>
					<?php
						}
					?>
					</select>
					</td>
				</tr>
				<tr>
					<td>借閱狀態</td>
					<td>
					<select name="lend">
						<option value="all"<?php echo(@$_GET["lend"]=="all"?" selected='selected'":""); ?>>所有</option>
						<option value="0"<?php echo(@$_GET["lend"]=="0"?" selected='selected'":""); ?>>在館內</option>
						<option value="1"<?php echo(@$_GET["lend"]=="1"?" selected='selected'":""); ?>>借閱中</option>
					</select>
					</td>
				</tr>
				<tr>
					<td>編號</td>
					<td><input name="bookid" type="number" min="1" id="bookid"></td>
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
		<table border="0" cellspacing="10" cellpadding="0">
		<tr>
			<td>分類</td>
			<td>ID</td>
			<td>書名</td>
			<td>數量</td>
			<td>借用</td>
			<td>ISBN</td>
		</tr>
		<?php
		if(is_array($booklist)){
			foreach($booklist as $index => $book){
				if(@$_GET["lend"]=="0"&&$book["aval"]==0)continue;
				if(@$_GET["lend"]=="1"&&$book["count"]==$book["aval"])continue;
			?>
			<tr>
				<td><?php echo $cate[$book["cat"]]; ?></td>
				<td><?php
					$bookidlist=explode(",",$book["id"]);
					foreach($bookidlist as $temp){
				?>
					<a href="../bookinfo/?id=<?php echo $temp; ?>"><?php echo $temp; ?></a>
				<?php } ?>
				</td>
				<td><?php echo $index; ?></td>
				<td><?php echo $book["count"]; ?></td>
				<td><?php
				if($book["count"]==1){
					if(@$book["aval"]==0)echo "已借出";
					else echo "在館內";
				}else {
					if($book["aval"]==0)echo "已全部借出";
					else echo $book["aval"]."本在館內";
				}
				?></td>
				<td><a href="https://books.google.com.tw/books?vid=<?php echo $book["ISBN"]; ?>" target="_blank"><?php echo $book["ISBN"]; ?></a></td>
			</tr>
			<?php
			}
		}else {
			?>
			<tr><td colspan="5" align="center">查無任何結果</td></tr>
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