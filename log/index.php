<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
<?php
include(__DIR__."/../res/comhead.php");

$meta->output();

$ok=true;

if($login["login"]===false){
	$msgbox->add("danger","你必須先登入");
	$ok=false;
}else if ($login["power"]==0){
	$ok=false;
	$msgbox->add("danger","你沒有權限");
	insertlog($login["id"],0,"return",false,"no power");
}
if (!isset($_GET["page"])||!is_numeric($_GET["page"])) {
	$_GET["page"]=0;
}
?>
</head>
<body style="text-align:center;">
<?php
include_once("../res/header.php");
if($ok){
?>
<div class="row">
	<div class="col-lg-3"></div>
	<div class="col-lg-6"><h2>Log</h2>
		<div class="row">
		<div class="col-lg-2">
		<form method="get">
			<input name="page" type="hidden" value="<?php echo ($_GET["page"]-1); ?>">
			<button type="submit" class="btn btn-success" <?php echo ($_GET["page"]==0?"style='display:none;'":""); ?>>上一頁</button>
		</form>
		</div>
		<div class="col-lg-2">
		<form method="get">
			<input name="page" type="hidden" value="<?php echo ($_GET["page"]+1); ?>">
			<button type="submit" class="btn btn-success">下一頁</button>
		</form>
		</div>
		</div>
		<div class="table-responsive">
		<table class="table table-hover table-condensed">
		<thead>
		<tr>
			<th>operate</th>
			<th>affect</th>
			<th>type</th>
			<th>result</th>
			<th>action</th>
			<th>time</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$page=0;
		if(is_numeric($_GET["page"]))$page=$_GET["page"];
		$query=new query;
		$query->table="log";
		$query->order=array("time","DESC");
		$query->limit=array(($page*30),30);
		$row=SELECT($query);
		foreach($row as $temp){
		?>
			<tr>
			<td><?php echo $temp["operate"]; ?></td>
			<td><?php echo $temp["affect"]; ?></td>
			<td><?php echo $temp["type"]; ?></td>
			<td><?php echo $temp["result"]; ?></td>
			<td><?php echo $temp["action"]; ?></td>
			<td><?php echo $temp["time"]; ?></td>
			</tr>
		<?php
		}
		?>
		</tbody>
		</table>
		</div>
	</div>
	<div class="col-lg-3"></div>
</div>
<?php
}
include(__DIR__."/../res/footer.php");
?>
</body>
</html>