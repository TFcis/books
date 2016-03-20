<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
<?php
include(__DIR__."/../res/comhead.php");

$ok=true;

if (!isset($_GET["id"])) {
	$msgbox->add("danger","沒有ID");
	$ok=false;
} else if (!is_numeric($_GET["id"])) {
	$msgbox->add("danger","ID錯誤");
	$ok=false;
}
if ($ok) {
	$query=new query;
	$query->table="booklist";
	$query->where=array("id",$_GET["id"]);
	$query->limit=array(0,1);
	$bookinfo=fetchone(SELECT($query));
	if (!$bookinfo) {
		$msgbox->add("danger","沒有此書");
		$ok=false;
	}
}
if ($bookinfo["aval"]==0) {
	$msgbox->add("warning","此書目前隱藏中");
	$ok=false;
}
if ($ok) {
	$query=new query;
	$query->column="name";
	$query->table="category";
	$query->where=array("id",$bookinfo["cat"]);
	$query->limit=array(0,1);
	$cate=fetchone(SELECT($query))["name"];
	$meta->meta["og:description"]="TFcisBooks圖書資訊 ID=".$bookinfo["id"].",書名=".$bookinfo["name"].",分類=".$cate.",年份=".$bookinfo["year"].",來源=".$bookinfo["source"].",ISBN=".$bookinfo["ISBN"];
}
$meta->output();
?>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
include_once("../res/header.php");
if($ok){
?>
<div class="row">
	<div class="col-lg-4"></div>
	<div class="col-lg-4"><h2>圖書資料</h2>
		<div class="table-responsive">
		<table class="table table-hover table-condensed">
		<tr>
			<td>ID</td>
			<td><?php echo $bookinfo["id"]; ?></td>
		</tr>
		<tr>
			<td>書名</td>
			<td><?php echo $bookinfo["name"]; ?></td>
		</tr>
		<tr>
			<td>分類</td>
			<td><?php echo $cate; ?></td>
		</tr>
		<tr>
			<td>年份</td>
			<td><?php echo $bookinfo["year"]; ?></td>
		</tr>
		<tr>
			<td>來源</td>
			<td><?php echo $bookinfo["source"]; ?></td>
		</tr>
		<tr>
			<td>ISBN</td>
			<td><a href="https://zh.wikipedia.org/wiki/Special:网络书源/<?php echo $bookinfo["ISBN"]; ?>" target="_blank"><?php echo $bookinfo["ISBN"]; ?></a></td>
		</tr>
		<tr>
			<td>借出</td>
			<td><?php 
				if($bookinfo["lend"]!=0){
					$acct=login_system::getinfobyid($bookinfo["lend"]);
					echo $acct->nickname;
				} else {
					echo "在館內";
				}
			?></td>
		</tr>
		<tr>
			<td>圖片</td>
			<td>
			<?php
			if($bookinfo["ISBN"]!=""){
				$link=file_get_contents("http://search.books.com.tw/exep/prod_search.php?key=".$bookinfo["ISBN"]);
				if($link==false)echo "連接http://search.books.com.tw/exep/prod_search.php?key=".$bookinfo["ISBN"]."失敗";
				$start=strpos($link,"data-original")+15;
				$end=strpos($link,'width="85" height="120"')-3;
				$link=substr($link,$start,$end-$start);
				if(strpos($link,"www.books.com.tw/img/")){
					?><img src="<?php echo $link;?>"><?php
				}
				else echo "沒有提供圖片";
			}
			else echo "沒有圖片";
			?>
			</td>
		</tr>
		<?php
		if($bookinfo["lend"]==0){
		?>
		<tr>
			<td>操作</td><td><a href="../borrow/?id=<?php echo $bookinfo["id"]; ?>">借閱此書</a>
			</td>
		</tr>
		<?php
		}else if($login["power"]>0){
		?>
		<tr>
			<td>操作</td><td><a href="../return/?id=<?php echo $bookinfo["id"]; ?>">歸還此書</a>
			</td>
		</tr>
		<?php
		}else{
		?>
		<tr>
			<td>操作</td><td>欲還書請找管理員</td>
		</tr>
		<?php
		}
		?>
		</table>
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