<html>
<?php
include_once(__DIR__."/../func/sql.php");
include_once(__DIR__."/../func/url.php");
include_once(__DIR__."/../func/checklogin.php");
include_once(__DIR__."/../func/log.php");
$error="";
$message="";
$data=checklogin();
if($data["login"]===false)header("Location: ".$data["url"]);
else if(!$data["power"]){
	$error="你沒有權限";
	insertlog(@$data["id"],0,"view log",false,"no power");
}
?>
<head>
<meta charset="UTF-8">
<title>Log-TFcisBooks</title>
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
	if($data["power"]>0){
?>
<center>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td colspan="2" height="20"></td>
</tr>
<tr>
	<td colspan="2" align="center"><h1>log</h1></td>
</tr>
<tr>
	<td align="center">
		<table border="0" cellspacing="3" cellpadding="0">
		<tr>
			<td>
			<form action="" method="get">
				<input name="page" type="hidden" value="<?php echo (@$_GET["page"]-1); ?>">
				<input name="" type="submit" value="上一頁" <?php echo (@$_GET["page"]==0?"style='display:none;'":""); ?>>
			</form>
			</td>
			<td>
			<form action="" method="get"><input name="page" type="hidden" value="<?php echo (@$_GET["page"]+1); ?>"><input name="" type="submit" value="下一頁"></form>
			</td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td colspan="2" align="center">
		<table border="1" cellspacing="0" cellpadding="2">
		<tr>
			<td>operate</td>
			<td>affect</td>
			<td>type</td>
			<td>result</td>
			<td>action</td>
			<td>time</td>
		</tr>
		<?php
		$page=0;
		if(is_numeric(@$_GET["page"]))$page=@$_GET["page"];
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
