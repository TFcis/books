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
} else if(isset($_POST["bookid"])){
	$edit=login_system::getinfobyaccount($_POST["user"]);
	if ($edit===false) {
		$msgbox->add("danger","無此用戶");
	} else if (!is_numeric($_POST["bookid"])) {
		$msgbox->add("danger","圖書ID錯誤");
		insertlog($login["id"],0,"borrow",false,"bookid empty");
	} else {
		$query=new query;
		$query->table="booklist";
		$query->where=array("id",$_POST["bookid"]);
		$book=fetchone(SELECT($query));
		if($book===null){
			$msgbox->add("danger","無此圖書ID");
			insertlog($login["id"],0,"borrow",false,"no bookid:".$_POST["bookid"]);
		}else if($book["lend"]!="0"){
			$msgbox->add("danger",$book["name"]."(".$_POST["bookid"].") 已有人借閱");
			insertlog($login["id"],$edit->id,"borrow",false,"already lead:".$_POST["bookid"]);
		}else{
			$query=new query;
			$query->table="booklist";
			$query->value=array("lend",$edit->id);
			$query->where=array("id",$_POST["bookid"]);
			UPDATE($query);
			$msgbox->add("success","已將圖書 ".$book["name"]."(".$_POST["bookid"].") 借給 ".$edit->nickname."(".$edit->account.")");
			insertlog($login["id"],$edit->id,"borrow",true,"book id=".$_POST["bookid"]);
		}
	}
}
?>
</head>
<body style="text-align:center;">
<?php
include(__DIR__."/../res/header.php");
if ($ok) {
?>
<div class="row">
	<div class="col-lg-4"></div>
	<div class="col-lg-4"><h2>借書</h2>
		<form method="post">
			<div class="input-group">
				<span class="input-group-addon">書本ID</span>
				<input class="form-control" name="bookid" type="number" min="1" required <?php echo (isset($_GET["id"])?"value=\"".$_GET["id"]."\"":"");?> >
				<span class="input-group-addon glyphicon glyphicon-book"></span>
			</div>
			<div class="input-group">
				<span class="input-group-addon">借閱使用者</span>
				<input class="form-control" name="user" type="text" required>
				<span class="input-group-addon glyphicon glyphicon-user"></span>
			</div>
			<div class="input-group">
				<button name="input" type="submit" class="btn btn-success">
					<span class="glyphicon glyphicon glyphicon-log-out"></span>
					借閱
				</button>
			</div>
		</form>
	</div>
	<div class="col-lg-4"></div>
</div>
<?php 
}
include(__DIR__."/../res/footer.php");
?>
</body>
</html>