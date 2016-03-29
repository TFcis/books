<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
<?php
include(__DIR__."/../res/comhead.php");

$meta->output();

$ok=true;

if($login["login"]===false){
	$ok=false;
	$msgbox->add("danger","你必須先登入");
}

if($ok){
	$editid=$login["id"];
	if(isset($_GET["id"])) {
		if ($login["power"]>0) {
			$edit=login_system::getinfobyaccount($edit);
			if ($edit===false) {
				$msgbox->add("danger","無此用戶");
				$ok=false;
			} else {
				$editid=$edit->id;
			}
		} else {
			$msgbox->add("danger","你沒有權限");
			$ok=false;
		}
	}
}
if ($ok) {
	$query=new query;
	$query->table="category";
	$row=SELECT($query);
	foreach($row as $temp)
		$cate[$temp["id"]]=$temp["name"];

	$query=new query;
	$query->column=array("id","name","cat");
	$query->table="booklist";
	$query->where=array("lend",$editid);
	$borrowlist=SELECT($query);

	if (count($borrowlist)==0){
		$msgbox->add("warning","沒有借閱");
		$ok=false;
	}
}
?>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
include(__DIR__."/../res/header.php");
if($ok){
?>
<div class="row">
	<div class="col-lg-3"></div>
	<div class="col-lg-6"><h2>目前借閱</h2>
		<?php
		if (count($borrowlist)>0){
		?>
		<div class="table-responsive">
			<table class="table table-hover table-condensed">
				<tr>
					<th>分類</th>
					<th>ID</th>
					<th>書名</th>
				</tr>
				<?php
				foreach($borrowlist as $book){
				?>
				<tr>
					<td><?php echo $cate[$book["cat"]]; ?></td>
					<td><a href="../bookinfo/?id=<?php echo $book["id"]; ?>"><?php echo $book["id"]; ?></a></td>
					<td><?php echo $book["name"]; ?></td>
				</tr>
				<?php
				}
				?>
			</table>
		</div>
		<?php
		}
		?>
	</div>
	<div class="col-lg-3"></div>
</div>
<?php 
}
include(__DIR__."/../res/footer.php");
?>
</body>
</html>