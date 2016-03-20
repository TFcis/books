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
}else if(isset($_POST["editpower"])){
	if($_POST["editpower"]>=1){
		$edit=login_system::getinfobyaccount($_POST["editaccount"]);
		if($login["id"]==$edit->id){
			$msgbox->add("danger","無法更改自己的權限");
			insertlog($login["id"],$edit->id,"manageuser",false,"edit own");
		} else {
			$query=new query;
			$query->table="powerlist";
			$query->value=array(
				array("id",$edit->id),
				array("power",$_POST["editpower"])
			);
			INSERT($query);
			insertlog($login["id"],$edit->id,"manageuser",true,"1");
			$msgbox->add("success","已將 ".$edit->nickname."(".$edit->account.") 的權限更改為管理員");
		}
	} else if($_POST["editpower"]==0){
		$edit=login_system::getinfobyid($_POST["editid"]);
		if($login["id"]==$edit->id){
			$msgbox->add("danger","無法更改自己的權限");
			insertlog($login["id"],$edit->id,"manageuser",false,"edit own");
		} else {
			$query=new query;
			$query->table="powerlist";
			$query->where=array("id",$edit->id);
			DELETE($query);
			insertlog($login["id"],$edit->id,"manageuser",true,"0");
			$msgbox->add("success","已移除 ".$edit->nickname."(".$edit->account.") 的權限");
		}
	} else {
		$msgbox->add();
	}
}
?>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
include_once("../res/header.php");
if($ok){
?>
<div class="row">
	<div class="col-lg-4"></div>
	<div class="col-lg-4"><h2>使用者管理</h2>
		<div style="display:none">
			<form method="post" id="edit">
				<input name="editid" type="hidden" id="editid">
				<input name="editpower" type="hidden" value="0">
			</form>
		</div>
		<div class="table-responsive">
		<table class="table table-hover table-condensed">
		<tr>
			<th>ID</th>
			<th>姓名</th>
			<th>更改</th>
		</tr>
		<?php
		$query=new query;
		$query->table="powerlist";
		$row=SELECT($query);
		foreach($row as $powerlist){
			$edit=login_system::getinfobyid($powerlist["id"]);
			?>
			<tr>
				<td><?php echo $edit->id; ?></td>
				<td><?php echo $edit->nickname."(".$edit->account.")"; ?></td>
				<td><button name="input" type="submit" class="btn btn-danger" onClick="editid.value='<?php echo $edit->id; ?>';edit.submit();"><span class="glyphicon glyphicon glyphicon-remove"></span>移除</button>
			</tr>
			<?php
		}
		?>
		</table>
		<h3>增加管理員</h3>
		<form method="post">
			<input name="editpower" type="hidden" value="1">
			<div class="input-group">
				<span class="input-group-addon">使用者</span>
				<input class="form-control" name="editaccount" type="text" required>
				<span class="input-group-addon glyphicon glyphicon-user"></span>
			</div>
			<div class="input-group">
				<button name="input" type="submit" class="btn btn-success">
					<span class="glyphicon glyphicon glyphicon-plus"></span>
					增加
				</button>
			</div>
		</form>
		</div>
	</div>
	<div class="col-lg-4"></div>
</div>
<?php
}
include(__DIR__."/../res/footer.php");
?>
</body>
</html>
