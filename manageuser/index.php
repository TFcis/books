<html>
<?php
include_once(__DIR__."/../config/config.php");
include_once($config["path"]["sql"]);
include_once(__DIR__."/../func/checklogin.php");
include_once(__DIR__."/../func/log.php");
$error="";
$message="";
$data=checklogin();
if($data["login"]===false)header("Location: ".$data["url"]);
else if(!$data["power"]){
	$error="你沒有權限";
	insertlog($data["id"],0,"manageuser",false,"no power");
	?><script>setTimeout(function(){history.back();},1000);</script><?php
}
else if(isset($_POST["editid"])){
	$acct=login_system::getinfobyid($_POST["editid"]);
	if($data["id"]==$_POST["editid"]){
		$error="無法更改自己的權限";
		insertlog($data["id"],$_POST["editid"],"manageuser",false,"edit own");
	} else if($_POST["editpower"]>=1){
		$query=new query;
		$query->table="powerlist";
		$query->value=array(
			array("id",$_POST["editid"]),
			array("power",$_POST["editpower"])
		);
		INSERT($query);
		insertlog($data["id"],$_POST["editid"],"manageuser",true,"1");
		$message="已將 ".$acct["nickname"]."(".$acct["account"].") 的權限更改為管理員";
	} else if($_POST["editpower"]==0){
		$query=new query;
		$query->table="powerlist";
		$query->where=array("id",$_POST["editid"]);
		DELETE($query);
		insertlog($data["id"],$_POST["editid"],"manageuser",true,"0");
		$message="已移除 ".$acct["nickname"]."(".$acct["account"].") 的權限";
	} else {
		$error="Something went wrong.";
	}
}
?>
<head>
<meta charset="UTF-8">
<title>使用者管理-TFcisBooks</title>
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
	<td class="dfromh">&nbsp;</td>
</tr>
<tr>
	<td colspan="1" style="text-align: center"><h1>使用者管理</h1></td>
</tr>
<tr>
	<td align="center">
		<div style="display:none">
			<form method="post" id="edit">
				<input name="editid" type="hidden" id="editid">
				<input name="editpower" type="hidden" value="0">
			</form>
		</div>
		<table border="1" cellspacing="0" cellpadding="2">
		<tr>
			<td>ID</td>
			<td>姓名</td>
			<td>更改</td>
		</tr>
		<?php
		$query=new query;
		$query->table="powerlist";
		$row=SELECT($query);
		foreach($row as $powerlist){
			$acct=login_system::getinfobyid($powerlist["id"]);
			?>
			<tr>
				<td><?php echo $acct["id"]; ?></td>
				<td><?php echo $acct["realname"]; ?></td>
				<td><input type="button" value="移除" onClick="editid.value='<?php echo $acct["id"]; ?>';edit.submit();" ></td>
			</tr>
			<?php
		}
		?>
		</table>
	</td>
</tr>
<tr height="20"><td></td></tr>
<tr>
	<td align="center">
		<h3>增加管理員</h3>
		<form method="post">
			<input name="editid" type="number">
			<input name="editpower" type="hidden" value="1">
			<input type="submit" value="增加">
		</form>
	</td>
</tr>
</table>
</center>
<?php
	}
?>
</body>
</html>
