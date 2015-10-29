<html>
<?php
include_once(__DIR__."/../config/config.php");
include_once($config["path"]["sql"]);
include_once(__DIR__."/../func/checklogin.php");
?>
<head>
<meta charset="UTF-8">
<link rel="icon" href="../res/icon.ico" type="image/x-icon">
<link rel="icon" href="../res/icon.ico" type="image/x-icon">
<?php
include_once("../res/meta.php");
meta();
?>
<title>TFcis Books</title>
<link href="../res/css.css" rel="stylesheet" type="text/css">
</head>
<body topmargin="0" leftmargin="0" bottommargin="0">
<?php
include_once("../res/header.php");
?>
<center>
<?php
@include_once("information.php");
?>
</center>
</body>
</html>
