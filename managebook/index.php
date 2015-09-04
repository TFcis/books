<html>
<?php
include_once("../func/sql.php");
include_once("../func/url.php");
include_once("../func/log.php");
include_once("../func/checklogin.php");
include_once("../func/consolelog.php");
$error="";
$message="";
$data=checklogin();
if($data==false)header("Location: ../login/?from=managebook");
else if($data["power"]<=1){
	$error="你沒有權限";
	insertlog($data["id"],0,"managebook",false,"no power");
	?><script>setTimeout(function(){history.back();},1000);</script><?php
}
else if(isset($_POST["catdelid"])){
	$query=new query;
	$query->table="category";
	$query->where=array("id",@$_POST["catdelid"]);
	DELETE($query);
	insertlog($data["id"],0,"managebook",true,"del cat:".@$_POST["catdelid"]." ".@$_POST["catdelname"] );
	$message="已刪除分類 ID=".@$_POST["catdelid"]." 名稱=".@$_POST["catdelname"];
}
else if(isset($_POST["addcat"])){
	$query=new query;
	$query->column=array("id");
	$query->table="category";
	$query->where=array("id",@$_POST["id"]);
	$query->limit=array(0,1);
	$row=fetchone(SELECT($query));
	if(@$_POST["id"]=="")$error="ID為空";
	else if(@$_POST["name"]=="")$error="名稱為空";
	else if($row)$error="已有此ID";
	else{
		$query=new query;
		$query->table="category";
		$query->value=array(
			array("id",@$_POST["id"]),
			array("name",@$_POST["name"]) 
		);
		INSERT($query);
		insertlog($data["id"],0,"managebook",true,"add cat:".@$_POST["id"]." ".@$_POST["name"] );
		$message="已增加分類 ID=".@$_POST["id"]." 名稱=".@$_POST["name"];
	}
}
else if(isset($_POST["editcat"])){
	$query=new query;
	$query->column=array("id");
	$query->table="category";
	$query->where=array("id",@$_POST["id"]);
	$query->limit=array(0,1);
	$row=fetchone(SELECT($query));
	if(@$_POST["id"]=="")$error="ID為空";
	else if(!$row)$error="無此ID";
	else if(@$_POST["name"]=="")$error="名稱為空";
	else{
		$query=new query;
		$query->table="category";
		$query->value=array("name",@$_POST["name"]);
		$query->where=array("id",@$_POST["id"]);
		UPDATE($query);
		insertlog($data["id"],0,"managebook",true,"edit cat:".@$_POST["id"]." ".@$_POST["name"] );
		$message="已修改分類 ID=".@$_POST["id"]." 名稱=".@$_POST["name"];
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
	$query->value=array("aval",(1-@$_POST["aval"]));
	$query->where=array("id",@$_POST["avalid"]);
	$row=UPDATE($query);
	insertlog($data["id"],0,"managebook",true,"edit book aval:".(1-@$_POST["aval"]) );
	$message="已將圖書 ID=".@$_POST["avalid"]." ".$bookavaltext[1-@$_POST["aval"]];
}
else if(isset($_POST["bookdelid"])){
	$query=new query;
	$query->table="booklist";
	$query->where=array("id",@$_POST["bookdelid"]);
	DELETE($query);
	insertlog($data["id"],0,"managebook",true,"del book:".@$_POST["bookdelid"] );
	$message="已刪除圖書 ID=".@$_POST["bookdelid"];
}
else if(isset($_POST["addbook"])){
	if(@$_POST["name"]=="")$error="書名為空";
	else if(@$_POST["cat"]=="")$error="分類為空";
	else{
		$booknames = explode(",",@$_POST["name"]);
		foreach($booknames as $name){
			$query=new query;
			$query->column=array("MAX(id)");
			$query->table="booklist";
			$row=fetchone(SELECT($query));
			$newid=$row["MAX(id)"]+1;
			$isbn=json_decode(@file_get_contents("https://www.googleapis.com/books/v1/volumes?q=isbn:".$name),true);
			for($i=0;$i<@$_POST["number"];$i++){
				if($isbn["totalItems"]==1){
					$query=new query;
					$query->table="booklist";
					$query->value=array(
						array("id",$newid),
						array("name",$isbn["items"][0]["volumeInfo"]["title"]),
						array("cat",@$_POST["cat"]),
						array("year",$isbn["items"][0]["volumeInfo"]["publishedDate"]),
						array("source",@$_POST["source"]),
						array("ISBN",$name)
					);
					INSERT($query);
					insertlog($data["id"],0,"managebook",true,"add book:".$newid);
					$message="已增加圖書 ID=".$newid." 書名=".$isbn["items"][0]["volumeInfo"]["title"]." 分類=".$cate[@$_POST["cat"]]." 來源=".@$_POST["source"]." ISBN=".$name;
				}
				else {
					$query=new query;
					$query->table="booklist";
					$query->value=array(
						array("id",$newid),
						array("name",$name),
						array("cat",@$_POST["cat"]),
						array("year",@$_POST["year"]),
						array("source",@$_POST["source"])
					);
					INSERT($query);
					insertlog($data["id"],0,"managebook",true,"add book:".$newid);
					$message="已增加圖書 ID=".$newid." 書名=".$name." 分類=".$cate[@$_POST["cat"]]." 年份=".$row["year"]." 來源=".@$_POST["source"];
				}
				$newid++;
			}
		}
	}
}
else if(isset($_POST["editbook"])){
	$editid=explode(",",@$_POST["id"]);
	foreach($editid as $id){
		if(@$_POST["name"]!=""){
			$isbn=json_decode(@file_get_contents("https://www.googleapis.com/books/v1/volumes?q=isbn:".@$_POST["name"]),true);
			if($isbn["totalItems"]==1){
				$query=new query;
				$query->table="booklist";
				$query->value=array(
					array("name",$isbn["items"][0]["volumeInfo"]["title"]),
					array("ISBN",@$_POST["name"])
				);
				$query->where=array("id",$id);
				$row=UPDATE($query);
				UPDATE( "booklist",[ ["year",$isbn["items"][0]["volumeInfo"]["publishedDate"] ] ],[ ["id",$id] ] );
			}
			else {
				$query=new query;
				$query->table="booklist";
				$query->value=array("name",@$_POST["name"]);
				$query->where=array("id",$id);
				$row=UPDATE($query);
				if(@$_POST["year"]!=""){
					$query=new query;
					$query->table="booklist";
					$query->value=array("year",@$_POST["year"]);
					$query->where=array("id",$id);
					$row=UPDATE($query);
				}
			}
		}
		if(@$_POST["cat"]!=0){
			$query=new query;
			$query->table="booklist";
			$query->value=array("cat",@$_POST["cat"]);
			$query->where=array("id",$id);
			$row=UPDATE($query);
		}
		if(@$_POST["year"]!=""){
			$query=new query;
			$query->table="booklist";
			$query->value=array("year",@$_POST["year"]);
			$query->where=array("id",$id);
			$row=UPDATE($query);
		}
		if(@$_POST["source"]!=""){
			$query=new query;
			$query->table="booklist";
			$query->value=array("source",@$_POST["source"]);
			$query->where=array("id",$id);
			$row=UPDATE($query);
		}
	}
	$query=new query;
	$query->table="booklist";
	$query->where=array("id",@$_POST["id"]);
	$row=fetchone(SELECT($query));
	insertlog($data["id"],0,"managebook",true,"edit book:".@$_POST["id"]);
	$message="已修改圖書 ID=".@$_POST["id"]." 書名=".$row["name"]." 分類=".$cate[$row["cat"]]." 年份=".$row["year"]." 來源=".$row["source"]." ISBN=".$row["ISBN"]." 數量=".count($editid);
}
$query=new query;
$query->column=["*"];
$query->table="account";
$row=SELECT($query);
foreach($row as $temp){
	$acct[$temp["id"]]=$temp["name"];
}
?>
<head>
<meta charset="UTF-8">
<title>圖書管理-TFcisBooks</title>
<?php
include_once("../res/meta.php");
meta();
?>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
	include_once("../res/header.php");
	if($error!=""){
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center" valign="middle" bgcolor="#F00" class="message"><?php echo $error;?></td>
	</tr>
</table>
<?php
	}
	if($message!=""){
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center" valign="middle" bgcolor="#0A0" class="message"><?php echo $message;?></td>
	</tr>
</table>
<?php
	}
	if($data["power"]>=2){
?>
<center>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="dfromh" colspan="3">&nbsp;</td>
</tr>
<tr>
<td valign="top">
	<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center"><h1>分類管理</h1></td>
	</tr>
	<tr>
		<td align="center" valign="top">
		<form method="post">
			
			<input name="addcat" type="hidden" value="">
			<table border="0" cellspacing="3" cellpadding="0">
			<tr>
				<td colspan="2" align="center"><h2>新增</h2></td>
			</tr>
			<tr>
				<td>ID</td>
				<td><input name="id" type="number" min="1" id="id"></td>
			</tr>
			<tr>
				<td>名稱</td>
				<td><input name="name" type="text" id="name"></td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" value="新增"></td>
			</tr>
			</table>
		</form>
		</td>
	</tr>
	<tr>
		<td valign="top">
		<form method="post">
			<input name="editcat" type="hidden" value="">
			<table border="0" cellspacing="3" cellpadding="0">
			<tr>
				<td colspan="2" align="center"><h2>修改</h2></td>
			</tr>
			<tr>
				<td>ID</td>
				<td>
				<select name="id">
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
				<td>名稱</td>
				<td><input name="name" type="text" id="name"></td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" value="修改"></td>
			</tr>
			</table>
		</form>
		</td>
	</tr>
	<tr>
		<td align="center">
			<table border="1" cellspacing="0" cellpadding="2">
			<div style="display:none">
				<form method="post" id="catdel">
					<input name="catdelid" type="hidden" id="catdelid">
					<input name="catdelname" type="hidden" id="catdelname">
				</form>
			</div>
			<tr>
				<td>ID</td>
				<td>名稱</td>
				<td>管理</td>
			</tr>
			<?php
				foreach($cate as $i => $temp){
			?>
				<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $temp; ?></td>
				<td><input type="button" value="刪除" onClick="if(!confirm('確認刪除?'))return false;catdelid.value='<?php echo $i; ?>';catdelname.value='<?php echo $temp; ?>';catdel.submit();" ></td>
				</tr>
			<?php
				}
			?>
		</table>
		</td>
	</tr>
	</table>
</td>
<td width="20"></td>
<td valign="top">
	<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center" colspan="2"><h1>圖書管理</h1></td>
	</tr>
	<tr>
		<td align="center" valign="top">
		<form method="post">
			<input name="addbook" type="hidden" value="true">
			<table border="0" cellspacing="3" cellpadding="0">
			<tr>
				<td align="center" colspan="2"><h2>新增</h2></td>
			</tr>
			<tr>
				<td>書名/ISBN</td>
				<td><input name="name" type="text" id="name" placeholder="逗點分隔新增多本"></td>
			</tr>
			<tr>
				<td>分類</td>
				<td>
				<select name="cat">
				<?php
					foreach($cate as $i => $name){
				?>
					<option value="<?php echo $i; ?>"<?php echo(isset($_POST["addbook"])&&$i==@$_POST["cat"]?" selected='selected'":""); ?>><?php echo $name; ?></option>
				<?php
					}
				?>
				</select>
				</td>
			</tr>
			<tr>
				<td>年份</td>
				<td><input name="year" type="text" id="year" value="0"></td>
			</tr>
			<tr>
				<td>來源</td>
				<td><input name="source" type="text" id="source" value="不明"></td>
			</tr>
			<tr>
				<td>數量</td>
				<td><input name="number" type="number" min="1" id="number" value="1"></td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" value="新增"></td>
			</tr>
			</table>
		</form>
		</td>
		<td align="center" valign="top">
		<form method="post">
			<input name="editbook" type="hidden" value="true">
			<table border="0" cellspacing="3" cellpadding="0">
			<tr>
				<td align="center" colspan="2"><h2>修改</h2></td>
			</tr>
			<tr>
				<td>ID</td>
				<td><input name="id" type="text" id="id" placeholder="逗點分隔修改多本"></td>
			</tr>
			<tr>
				<td>書名/ISBN</td>
				<td><input name="name" type="text" id="name"></td>
			</tr>
			<tr>
				<td>分類</td>
				<td>
				<select name="cat">
					<option value="0">不更改</option>
				<?php
					foreach($cate as $i => $name){
				?>
					<option value="<?php echo $i; ?>"><?php echo $name; ?></option>
				<?php
					}
				?>
				</select>
				</td>
			</tr>
			<tr>
				<td>年份</td>
				<td><input name="year" type="text" id="year"></td>
			</tr>
			<tr>
				<td>來源</td>
				<td><input name="source" type="text" id="source"></td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" value="修改"></td>
			</tr>
			</table>
		</form>
		</td>
	</tr>
	<tr>
		<td valign="top" colspan="2">
			<div style="display:none">
				<form method="post" id="bookaval">
					<input name="avalid" type="hidden" id="avalid">
					<input name="aval" type="hidden" id="aval">
				</form>
				<form method="post" id="bookdel">
					<input name="bookdelid" type="hidden" id="bookdelid">
				</form>
			</div>
			<table border="1" cellspacing="0" cellpadding="2">
			<tr>
				<td>分類</td>
				<td>ID</td>
				<td>書名</td>
				<td>借出</td>
				<td>來源</td>
				<td>ISBN</td>
				<td>資訊</td>
				<td>管理</td>
			</tr>
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
					<td><?php echo @$acct[$book["lend"]]; ?></td>
					<td><?php echo $book["source"]; ?></td>
					<td><?php echo $book["ISBN"]; ?></td>
					<td><?php echo ($book["aval"]==0?"隱藏":""); ?></td>
					<td>
					<input type="button" value="<?php echo $bookavaltext[1-$book["aval"]];?>" onClick="avalid.value=<?php echo $book["id"]; ?>;aval.value=<?php echo $book["aval"]; ?>;bookaval.submit();">
					<input type="button" value="刪除" onClick="if(!confirm('確認刪除?'))return false;bookdelid.value=<?php echo $book["id"]; ?>;bookdel.submit();">
					</td>
				</tr>
				<?php
			}
			?>
		</table>
		</td>
	</tr>
	</table>
</td>
</tr>
</table>
</center>
<?php
	}
?>
</body>
</html>