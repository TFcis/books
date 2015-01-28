<html>
<?php
include_once("../func/sql.php");
DELETE("session",[ ["cookie",$_COOKIE["ELMScookie"]] ]);
setcookie("ELMScookie", "", time(), "/");
?>
<head>
<meta charset="UTF-8">
<title>登出-TFcisELMS</title>
<link href="../res/css.css" rel="stylesheet" type="text/css">
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
	include_once("../header.php");
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center" valign="middle" bgcolor="#0A0" class="message">已登出
		</td>
	</tr>
</table>
<script>setTimeout(function(){location="../";},1000)</script>
</body>
</html>