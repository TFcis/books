<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
<?php
if (isset($_GET["bookid"]) && is_numeric($_GET["bookid"]))
	header("Location: ../bookinfo/?id=".$_GET["bookid"]);

include(__DIR__."/../res/comhead.php");

$meta->output();

if (!isset($_GET["bookname"])) {
	$_GET["bookname"]="";
}
if (!isset($_GET["bookcat"])) {
	$_GET["bookcat"]="all";
}
if (!isset($_GET["lend"])) {
	$_GET["lend"]="all";
}

$query = new query;
$query->table = "category";
$row = SELECT($query);
foreach($row as $temp){
	$cate[$temp["id"]] = $temp["name"];
}
$temp=array(array("aval","1"));
if($_GET["bookname"] != "")
	$temp[] = array("name",str_replace("+","[+]",$_GET["bookname"]),"REGEXP");
if(is_numeric($_GET["bookcat"]))
	$temp[] = array("cat",$_GET["bookcat"]);

$query = new query;
$query->table = "booklist";
$query->where = $temp;
$query->order = array(array("cat"),array("name"));
$row = SELECT($query);
$booklist=array();
foreach($row as $temp){
	if (!isset($booklist[$temp["name"]])) {
		$booklist[$temp["name"]] = array(
			"id"	=> array(),
			"ISBN"	=> "",
			"cat"	=> 0,
			"count"	=> array(
				"aval" => 0,
				"lend" => 0,
				"total" => 0
			)
		);
	}
	if (!isset($_GET["lend"]) || $_GET["lend"] == "all" || ($_GET["lend"] == 1 && $temp["lend"] != 0) || ($_GET["lend"] == 0 && $temp["lend"] == 0))
		$booklist[$temp["name"]]["id"][]=$temp["id"];
	$booklist[$temp["name"]]["count"]["total"]++;
	$booklist[$temp["name"]]["ISBN"] = $temp["ISBN"];
	$booklist[$temp["name"]]["cat"] = $temp["cat"];
	if($temp["lend"] == 0) $booklist[$temp["name"]]["count"]["aval"]++;
	else $booklist[$temp["name"]]["count"]["lend"]++;
}
if (count($booklist)==0)
	$msgbox->add("warning","查無任何結果");
?>
</head>
<body>
<?php include(__DIR__."/../res/header.php"); ?>
<div class="row">
	<div class="col-lg-3">
		<h2>篩選器</h2>
		<form method="get">
			<div class="input-group">
				<span class="input-group-addon">書名</span>
				<input class="form-control" name="bookname" type="text" value="<?php echo $_GET["bookname"];?>">
				<span class="input-group-addon glyphicon glyphicon-font"></span>
			</div>
			<div class="input-group">
				<span class="input-group-addon">分類</span>
				<select class="form-control" name="bookcat">
					<option value=""<?php echo($_GET["bookcat"]==""?" selected='selected'":""); ?>>所有分類</option>
				<?php
					foreach($cate as $i => $name){
				?>
					<option value="<?php echo $i; ?>"<?php echo($i==$_GET["bookcat"]?" selected='selected'":""); ?>><?php echo $name; ?></option>
				<?php
					}
				?>
				</select>
				<span class="input-group-addon glyphicon glyphicon-inbox"></span>
			</div>
			<div class="input-group">
				<span class="input-group-addon">借閱狀態</span>
				<select class="form-control" name="lend">
					<option value="all"<?php echo($_GET["lend"]=="all"?" selected='selected'":""); ?>>所有</option>
					<option value="0"<?php echo($_GET["lend"]=="0"?" selected='selected'":""); ?>>在館內</option>
					<option value="1"<?php echo($_GET["lend"]=="1"?" selected='selected'":""); ?>>借閱中</option>
				</select>
				<span class="input-group-addon glyphicon glyphicon-ok"></span>
			</div>
			<div class="input-group">
				<span class="input-group-addon">編號</span>
				<input class="form-control" name="bookid" type="number" min="1">
				<span class="input-group-addon glyphicon glyphicon-calendar"></span>
			</div>
			<div class="input-group">
				<button name="input" type="submit" class="btn btn-success">
					<span class="glyphicon glyphicon-search"></span>
					搜尋 
				</button>
			</div>
		</form>
	</div>
	<div class="col-lg-9">
		<h2>館藏查詢</h2>
		<div class="table-responsive">
			<?php
			if(count($booklist)>0){
			?>
			<table class="table table-hover table-condensed">
			<thead>
				<tr>
					<th rowspan="2">分類</th>
					<th rowspan="2">ID</th>
					<th rowspan="2">書名</th>
					<th colspan="3">數量</th>
				</tr>
				<tr>
					<th>館內</th>
					<th>借出</th>
					<th>合計</th>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach($booklist as $name => $book){
				if (isset($_GET["lend"])) {
					if ($_GET["lend"]=="0" && $book["count"]["aval"]==0) continue;
					else if ($_GET["lend"]=="1" && $book["count"]["lend"]==0) continue;
				}
			?>
				<tr>
					<td><?php echo $cate[$book["cat"]]; ?></td>
					<td <?php echo (count($book["id"])>5?'style="line-height: 25px;"':'');?>>
					<?php
					foreach($book["id"] as $count => $temp){
					?>
						<a href="../bookinfo/?id=<?php echo $temp; ?>"><?php echo $temp; ?></a>&nbsp;&nbsp;&nbsp;&nbsp;
					<?php
						if($count%5==4) echo "<br>";
					}
					?>
					</td>
					<td><?php echo $name; ?></td>
					<td><?php echo $book["count"]["aval"]; ?></td>
					<td><?php echo $book["count"]["lend"]; ?></td>
					<td><?php echo $book["count"]["total"]; ?></td>
				</tr>
			<?php
			}
			?>
			</tbody>
			</table>
		<?php
		}
		?>
		</div>
	</div>
</div>
<?php include(__DIR__."/../res/footer.php"); ?>
</body>
</html>
