<html>
<?php
include_once("../func/sql.php");
include_once("../func/url.php");
include_once("../func/checklogin.php");
include_once("../func/consolelog.php");
$error="";
$message="";
$data=checklogin();
$powername=array("封禁","使用者","管理員","系統管理員");
if($data==false)header("Location: ../login/?from=manageuser");
else if($data["power"]<=1){
	$error="你沒有權限";
	insertlog($data["id"],0,"manageuser",false,"no power");
	?><script>setTimeout(function(){history.back();},1000);</script><?php
}
else if(isset($_POST["editid"])){
	if($data["id"]==$_POST["editid"]){
		$error="無法更改自己的權限";
		insertlog($data["id"],$_POST["editid"],"manageuser",false,"edit own");
	}
	else{
		$row=mfa(SELECT( ["user","name","power"],"account",[ ["id",$_POST["editid"]] ]));
		if($row["power"]>$data["power"]){
			$error="無法更改比自己權限高的帳戶";
			insertlog($data["id"],$_POST["editid"],"manageuser",false,"edit other power higher");
		}
		else if($_POST["editpower"]>$data["power"]){
			$error="無法將權限調比自己高";
			insertlog($data["id"],$_POST["editid"],"manageuser",false,"edit own higher power");
		}
		else {
			UPDATE( "account",[ ["power",$_POST["editpower"]] ],[ ["id",$_POST["editid"]] ] );
			insertlog($data["id"],$_POST["editid"],"manageuser",true,$_POST["editpower"]);
			$message="已將 ".$row["user"]."(".$row["name"].") 的權限更改為 ".$powername[$_POST["editpower"]];
			if($_POST["editpower"]<=0){
				DELETE("session",[ ["id",$_POST["editid"] ] ],"all");
				insertlog($data["id"],$_POST["editid"],"logout",true,"block");
			}
		}
	}
}
?>
<head>
<meta charset="UTF-8">
<title>使用者管理-TFcisBooks</title>
<link href="../res/css.css" rel="stylesheet" type="text/css">
<link rel="icon" href="../res/icon.ico" type="image/x-icon">
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
	<td class="dfromh">&nbsp;</td>
</tr>
<tr>
	<td colspan="1" style="text-align: center"><h1>使用者管理</h1></td>
</tr>
<tr>
	<td valign="top">
		<div style="display:none">
			<form method="post" id="edit">
				<input name="editid" type="hidden" id="editid">
				<input name="editpower" type="hidden" id="editpower">
			</form>
		</div>
		<table border="1" cellspacing="0" cellpadding="2">
		<tr>
			<td>ID</td>
			<td>帳號</td>
			<td>姓名</td>
			<td>Email</td>
			<td>權限</td>
			<td colspan="4">更改</td>
		</tr>
		<?php
		$row=SELECT("*","account",null,[ ["id","ASC"] ],"all");
		while($acct=mfa($row)){
			?>
			<tr>
				<td><?php echo $acct["id"]; ?></td>
				<td><?php echo $acct["user"]; ?></td>
				<td><?php echo het($acct["name"]); ?></td>
				<td><?php echo $acct["email"]; ?></td>
				<td><?php echo $powername[$acct["power"]]; ?></td>
				<td>
				<?php
				for($i=0;$i<=3;$i++){
					?>
					<input type="button" value="<?php echo $powername[$i];?>" onClick="editid.value=<?php echo $acct["id"]; ?>;editpower.value=<?php echo $i; ?>;edit.submit();" >
					<?php
				}
				?>
				</td>
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