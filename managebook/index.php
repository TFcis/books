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
}else if(isset($_POST["catdelid"])){
	$query=new query;
	$query->table="category";
	$query->where=array("id",$_POST["catdelid"]);
	DELETE($query);
	insertlog($login["id"],0,"managebook",true,"del cat:".$_POST["catdelid"]." ".$_POST["catdelname"] );
	$msgbox->add("success","已刪除分類 ID=".$_POST["catdelid"]." 名稱=".$_POST["catdelname"]);
}else if(isset($_POST["addcat"])){
	$query=new query;
	$query->column=array("id");
	$query->table="category";
	$query->where=array("id",$_POST["id"]);
	$query->limit=array(0,1);
	$row=fetchone(SELECT($query));
	if($_POST["id"]=="")$error="ID為空";
	else if($_POST["name"]=="")$error="名稱為空";
	else if($row)$error="已有此ID";
	else{
		$query=new query;
		$query->table="category";
		$query->value=array(
			array("id",$_POST["id"]),
			array("name",$_POST["name"]) 
		);
		INSERT($query);
		$msgbox->add("success","已增加分類 ID=".$_POST["id"]." 名稱=".$_POST["name"]);
		insertlog($login["id"],0,"managebook",true,"add cat:".$_POST["id"]." ".$_POST["name"] );
	}
}
else if(isset($_POST["editcat"])){
	$query=new query;
	$query->column=array("id");
	$query->table="category";
	$query->where=array("id",$_POST["id"]);
	$query->limit=array(0,1);
	$row=fetchone(SELECT($query));
	if($_POST["id"]=="")$error="ID為空";
	else if(!$row)$error="無此ID";
	else if($_POST["name"]=="")$error="名稱為空";
	else{
		$query=new query;
		$query->table="category";
		$query->value=array("name",$_POST["name"]);
		$query->where=array("id",$_POST["id"]);
		UPDATE($query);
		insertlog($login["id"],0,"managebook",true,"edit cat:".$_POST["id"]." ".$_POST["name"] );
		$msgbox->add("success","已修改分類 ID=".$_POST["id"]." 名稱=".$_POST["name"]);
	}
}
$query=new query;
$query->table="category";
$row=SELECT($query);
foreach($row as $temp){
	$cate[$temp["id"]]=$temp["name"];
}
$bookavaltext=["隱藏","顯示"];
if(isset($_POST["avalid"])){
	$query=new query;
	$query->table="booklist";
	$query->value=array("aval",(1-$_POST["aval"]));
	$query->where=array("id",$_POST["avalid"]);
	$row=UPDATE($query);
	insertlog($login["id"],0,"managebook",true,"edit book aval:".(1-$_POST["aval"]) );
	$msgbox->add("success","已將圖書 ID=".$_POST["avalid"]." ".$bookavaltext[1-$_POST["aval"]]);
}
else if(isset($_POST["bookdelid"])){
	$query=new query;
	$query->table="booklist";
	$query->where=array("id",$_POST["bookdelid"]);
	DELETE($query);
	insertlog($login["id"],0,"managebook",true,"del book:".$_POST["bookdelid"] );
	$msgbox->add("success","已刪除圖書 ID=".$_POST["bookdelid"]);
}
else if(isset($_POST["addbook"])){
	if($_POST["name"]=="")$error="書名為空";
	else if($_POST["cat"]=="")$error="分類為空";
	else{
		$booknames = explode(",",$_POST["name"]);
		foreach($booknames as $name){
			$query=new query;
			$query->column=array("MAX(id)");
			$query->table="booklist";
			$row=fetchone(SELECT($query));
			$newid=$row["MAX(id)"]+1;
			for($i=0;$i<$_POST["number"];$i++){
				$query=new query;
				$query->table="booklist";
				$query->value=array(
					array("id",$newid),
					array("name",$name),
					array("ISBN",$_POST["ISBN"]),
					array("cat",$_POST["cat"]),
					array("year",$_POST["year"]),
					array("source",$_POST["source"]),
					array("note",$_POST["note"])
				);
				INSERT($query);
				insertlog($login["id"],0,"managebook",true,"add book:".$newid);
				$msgbox->add("success","已增加圖書 ID=".$newid." 書名=".$name." 分類=".$cate[$_POST["cat"]]." 年份=".$_POST["year"]." 來源=".$_POST["source"]." 註記=".$_POST["note"]);#
				$newid++;
			}
		}
	}
}
else if(isset($_POST["editbook"])){
	$editid=explode(",",$_POST["id"]);
	foreach($editid as $id){
		if($_POST["name"]!=""){
			$query=new query;
			$query->table="booklist";
			$query->value=array("name",$_POST["name"]);
			$query->where=array("id",$id);
			UPDATE($query);
		}
		if($_POST["ISBN"]!=""){
			$query=new query;
			$query->table="booklist";
			$query->value=array("ISBN",$_POST["ISBN"]);
			$query->where=array("id",$id);
			UPDATE($query);
		}
		if($_POST["cat"]!=0){
			$query=new query;
			$query->table="booklist";
			$query->value=array("cat",$_POST["cat"]);
			$query->where=array("id",$id);
			$row=UPDATE($query);
		}
		if($_POST["year"]!=""){
			$query=new query;
			$query->table="booklist";
			$query->value=array("year",$_POST["year"]);
			$query->where=array("id",$id);
			$row=UPDATE($query);
		}
		if($_POST["source"]!=""){
			$query=new query;
			$query->table="booklist";
			$query->value=array("source",$_POST["source"]);
			$query->where=array("id",$id);
			$row=UPDATE($query);
		}
		if($_POST["note"]!=""){
			$query=new query;
			$query->table="booklist";
			$query->value=array("note",$_POST["note"]);
			$query->where=array("id",$id);
			$row=UPDATE($query);
		}
	}
	$query=new query;
	$query->table="booklist";
	$query->where=array("id",$_POST["id"]);
	$row=fetchone(SELECT($query));
	insertlog($login["id"],0,"managebook",true,"edit book:".$_POST["id"]);
	$msgbox->add("success","已修改圖書 ID=".$_POST["id"]." 書名=".$row["name"]." 分類=".$cate[$row["cat"]]." 年份=".$row["year"]." 來源=".$row["source"]." ISBN=".$row["ISBN"]." 數量=".count($editid)." 註記=".$_POST["note"]);
}
$query=new query;
$query->table="account";
$row=SELECT($query);
foreach($row as $temp){
	$acct[$temp["id"]]=$temp["name"];
}
?>
</head>
<body style="text-align:center;">
<?php
include_once("../res/header.php");
if($ok){
?>
<div class="row">
<div class="col-lg-3"><h2>分類管理</h2>
	<div class="row">
	<div class="col-lg-12">
		<form method="post">
			<input name="addcat" type="hidden" value="">
			<h3>新增</h3>
			<div class="input-group">
				<span class="input-group-addon">ID</span>
				<input class="form-control" name="id" type="number" min="1">
				<span class="input-group-addon glyphicon glyphicon-tags"></span>
			</div>
			<div class="input-group">
				<span class="input-group-addon">名稱</span>
				<input class="form-control" name="name" type="text">
				<span class="input-group-addon glyphicon glyphicon-font"></span>
			</div>
			<div class="input-group">
				<button name="input" type="submit" class="btn btn-success">
					<span class="glyphicon glyphicon glyphicon-plus"></span>
					新增 
				</button>
			</div>
		</form>
	</div>
	<div class="col-lg-12">
		<form method="post">
			<input name="editcat" type="hidden" value="">
			<h3>修改</h3>
			<div class="input-group">
				<span class="input-group-addon">ID</span>
				<select class="form-control" name="id">
				<?php
					foreach($cate as $i => $name){
				?>
					<option value="<?php echo $i; ?>"><?php echo $name; ?></option>
				<?php
					}
				?>
				</select>
				<span class="input-group-addon glyphicon glyphicon-tags"></span>
			</div>
			<div class="input-group">
				<span class="input-group-addon">名稱</span>
				<input class="form-control" name="name" type="text">
				<span class="input-group-addon glyphicon glyphicon-font"></span>
			</div>
			<div class="input-group">
				<button name="input" type="submit" class="btn btn-success">
					<span class="glyphicon glyphicon glyphicon-pencil"></span>
					修改 
				</button>
			</div>
		</form>
	</div>
	<div class="col-lg-12">
		<div style="display:none">
			<form method="post" id="catdel">
				<input name="catdelid" type="hidden" id="catdelid">
				<input name="catdelname" type="hidden" id="catdelname">
			</form>
		</div>
		<div class="table-responsive">
		<table class="table table-hover table-condensed">
		<thead>
		<tr>
			<th>ID</th>
			<th>名稱</th>
			<th>管理</th>
		</tr>
		</thead>
		<tbody>
		<?php
			foreach($cate as $i => $temp){
		?>
			<tr>
			<td><?php echo $i; ?></td>
			<td><?php echo $temp; ?></td>
			<td><button name="input" type="submit" class="btn btn-danger" onClick="if(!confirm('確認刪除?'))return false;catdelid.value='<?php echo $i; ?>';catdelname.value='<?php echo $temp; ?>';catdel.submit();"><span class="glyphicon glyphicon glyphicon-remove"></span>刪除</button></td>
			</tr>
		<?php
			}
		?>
		</tbody>
		</table>
		</div>
	</div>
	</div>
</div>
<div class="col-lg-9"><h2>圖書管理</h2>
	<div class="row">
	<div class="col-lg-6">
		<form method="post">
			<input name="addbook" type="hidden" value="true">
			<h3>新增</h3>
			<div class="input-group">
				<span class="input-group-addon">書名</span>
				<input class="form-control" name="name" type="text">
				<span class="input-group-addon glyphicon glyphicon-font"></span>
			</div>
			<div class="input-group">
				<span class="input-group-addon">ISBN</span>
				<input class="form-control" name="ISBN" type="number" value="0">
				<span class="input-group-addon glyphicon glyphicon-font"></span>
			</div>
			<div class="input-group">
				<span class="input-group-addon">分類</span>
				<select class="form-control" name="cat">
				<?php
					foreach($cate as $i => $name){
				?>
					<option value="<?php echo $i; ?>"<?php echo(isset($_POST["addbook"])&&$i==$_POST["cat"]?" selected='selected'":""); ?>><?php echo $name; ?></option>
				<?php
					}
				?>
				</select>
				<span class="input-group-addon glyphicon glyphicon-inbox"></span>
			</div>
			<div class="input-group">
				<span class="input-group-addon">年份</span>
				<input class="form-control" name="year" type="number" value="0">
				<span class="input-group-addon glyphicon glyphicon-calendar"></span>
			</div>
			<div class="input-group">
				<span class="input-group-addon">來源</span>
				<input class="form-control" name="source" type="text" value="不明">
				<span class="input-group-addon glyphicon glyphicon-user"></span>
			</div>
			<div class="input-group">
				<span class="input-group-addon">註記</span>
				<input class="form-control" name="note" type="text">
				<span class="input-group-addon glyphicon glyphicon-pencil"></span>
			</div>
			<div class="input-group">
				<span class="input-group-addon">數量</span>
				<input class="form-control" name="number" type="number" value="1">
				<span class="input-group-addon glyphicon glyphicon-th-list"></span>
			</div>
			<div class="input-group">
				<button name="input" type="submit" class="btn btn-success">
					<span class="glyphicon glyphicon-plus"></span>
					新增
				</button>
			</div>
		</form>
	</div>
	<div class="col-lg-6">
		<form method="post">
			<input name="editbook" type="hidden" value="true">
			<h3>修改</h3>
			<div class="input-group">
				<span class="input-group-addon">ID</span>
				<input class="form-control" name="id" type="text" placeholder="逗點分隔修改多本">
				<span class="input-group-addon glyphicon glyphicon-tags"></span>
			</div>
			<div class="input-group">
				<span class="input-group-addon">ISBN</span>
				<input class="form-control" name="ISBN" type="number">
				<span class="input-group-addon glyphicon glyphicon-font"></span>
			</div>
			<div class="input-group">
				<span class="input-group-addon">書名</span>
				<input class="form-control" name="name" type="text">
				<span class="input-group-addon glyphicon glyphicon-font"></span>
			</div>
			<div class="input-group">
				<span class="input-group-addon">分類</span>
				<select class="form-control" name="cat">
					<option value="0">不更改</option>
				<?php
					foreach($cate as $i => $name){
				?>
					<option value="<?php echo $i; ?>"><?php echo $name; ?></option>
				<?php
					}
				?>
				</select>
				<span class="input-group-addon glyphicon glyphicon-inbox"></span>
			</div>
			<div class="input-group">
				<span class="input-group-addon">年份</span>
				<input class="form-control" name="year" type="number">
				<span class="input-group-addon glyphicon glyphicon-calendar"></span>
			</div>
			<div class="input-group">
				<span class="input-group-addon">來源</span>
				<input class="form-control" name="source" type="text">
				<span class="input-group-addon glyphicon glyphicon-user"></span>
			</div>
			<div class="input-group">
				<span class="input-group-addon">註記</span>
				<input class="form-control" name="note" type="text">
				<span class="input-group-addon glyphicon glyphicon-pencil"></span>
			</div>
			<div class="input-group">
				<button name="input" type="submit" class="btn btn-success">
					<span class="glyphicon glyphicon glyphicon-pencil"></span>
					修改 
				</button>
			</div>
		</form>
	</div>
	<div class="col-lg-12">
		<div style="display:none">
			<form method="post" id="bookaval">
				<input name="avalid" type="hidden" id="avalid">
				<input name="aval" type="hidden" id="aval">
			</form>
			<form method="post" id="bookdel">
				<input name="bookdelid" type="hidden" id="bookdelid">
			</form>
		</div>
		<div class="table-responsive">
		<table class="table table-hover table-condensed">
		<thead>
		<tr>
			<th>分類</th>
			<th>ID</th>
			<th>書名</th>
			<th>借出</th>
			<th>資訊</th>
			<th>管理</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$query=new query;
		$query->table="booklist";
		$query->order=array("id","ASC");
		$row=SELECT($query);
		foreach($row as $book){
			?>
			<tr>
				<td><?php echo $cate[$book["cat"]]; ?></td>
				<td><a href="../bookinfo/?id=<?php echo $book["id"]; ?>"><?php echo $book["id"]; ?></a></td>
				<td><?php echo htmlspecialchars($book["name"],ENT_QUOTES); ?></td>
				<td><?php 
				if($book["lend"]!=0){
					$acct=login_system::getinfobyid($book["lend"]);
					echo $acct->nickname;
				}
				?></td>
				<td><?php echo ($book["aval"]==0?"隱藏":"")." ".$book["note"]; ?></td>
				<td>
					<?php
					if($book["aval"]){
						?><button name="input" type="submit" class="btn btn-warning" onClick="avalid.value=<?php echo $book["id"]; ?>;aval.value=<?php echo $book["aval"]; ?>;bookaval.submit();"><span class="glyphicon glyphicon glyphicon-eye-close"></span><?php echo $bookavaltext[0];?></button><?php
					}else {
						?><button name="input" type="submit" class="btn btn-info" onClick="avalid.value=<?php echo $book["id"]; ?>;aval.value=<?php echo $book["aval"]; ?>;bookaval.submit();"><span class="glyphicon glyphicon glyphicon-eye-open"></span><?php echo $bookavaltext[1];?></button><?php
					}
					?>
					<button name="input" type="submit" class="btn btn-danger" onClick="if(!confirm('確認刪除?'))return false;bookdelid.value=<?php echo $book["id"]; ?>;bookdel.submit();"><span class="glyphicon glyphicon glyphicon-remove"></span>刪除</button>
				</td>
			</tr>
			<?php
		}
		?>
		</tbody>
		</table>
		</div>
	</div>
	</div>
</div>
</div>
<?php
}
?>
<?php include(__DIR__."/../res/footer.php"); ?>
</body>
</html>
