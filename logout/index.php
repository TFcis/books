<html>
<?php
include_once("../func/sql.php");
include_once("../func/url.php");
include_once("../func/checklogin.php");
include_once("../func/log.php");
$data=checklogin();
if($data==false)header("Location: ../login/?from=managebook");
insertlog($data["id"],$data["id"],"logout");
DELETE("session",[ ["cookie",$_COOKIE["ELMScookie"]] ]);
setcookie("ELMScookie", "", time(), "/");
?>
<head>
<meta charset="UTF-8">
<title>登出-TFcisBooks</title>
<?php
include_once("../res/meta.php");
meta();
?>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
	include_once("../res/header.php");
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center" valign="middle" bgcolor="#0A0" class="message">已登出
		</td>
	</tr>
</table>
<script>setTimeout(function(){location="../home";},1000)</script>
</body>
</html>