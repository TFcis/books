<html>
<?php
include_once("../func/sql.php");
include_once("../func/url.php");
include_once("../func/checklogin.php");
include_once("../func/consolelog.php");
?>
<head>
<meta http-equiv="Content-Type" charset="UTF-8" name="viewport" content="width=device-width,user-scalable=yes">
<title>圖書資料-TFcisBooks</title>
<?php
	include_once("../res/meta.php");
	$bookinfo=mfa(SELECT(["id","name","cat","year","source","ISBN","lend"],"booklist",[["id",$_GET["id"]]]));
	$cate=mfa(SELECT(["name"],"category",[["id",$bookinfo["cat"]]]))["name"];
	meta([["description","TFcisBooks圖書資訊 ID=".$bookinfo["id"].",書名=".$bookinfo["name"].",分類=".$cate.",年份=".$bookinfo["year"].",來源=".$bookinfo["source"].",ISBN=".$bookinfo["ISBN"]]]);
?>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
	include_once("../res/header.php");
?>
<center>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="dfromh" colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" style="text-align: center"><h1>圖書資料</h1></td>
</tr>
<tr>
	<td>
		<table border="0" cellspacing="5" cellpadding="0">
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
			<td><a href="https://books.google.com.tw/books?vid=<?php echo $bookinfo["ISBN"]; ?>" target="_blank"><?php echo $bookinfo["ISBN"]; ?></a></td>
		</tr>
		<tr>
			<td>借出</td>
			<td><?php 
				echo ($bookinfo["lend"]==0?"在館內":"已借出");
				if($bookinfo["lend"]!=0&&checklogin()["power"]>=2){
					$acct=mfa(SELECT(["name","user"],"account",[["id",$bookinfo["lend"]]]));
					echo " ".$acct["user"]."(".$acct["name"].")";
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
		</table>
	</td>
</tr>
<tr>
	<td height="20"></td>
</tr>
<?php
	if(checklogin()["power"]>=2){
?>
<tr>
	<td align="center"><a href="../borrow/?id=<?php echo $bookinfo["id"]; ?>">借閱此書</a>
	</td>
</tr>
<tr>
	<td align="center"><a href="../return/?id=<?php echo $bookinfo["id"]; ?>">歸還此書</a>
	</td>
</tr>
<?php
	}else{
?>
<tr>
	<td align="center">欲借還書請找管理員</td>
</tr>
<?php
	}
?>
</table>
</center>
</body>
</html>