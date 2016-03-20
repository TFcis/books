<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
<?php
include(__DIR__."/../res/comhead.php");
$meta->output();
$msgbox->add("success","Welcome!");
?>
</head>
<body topmargin="0" leftmargin="0" bottommargin="0">
<?php include(__DIR__."/../res/header.php"); ?>
<center>
<?php @include(__DIR__."/information.php"); ?>
</center>
<?php include(__DIR__."/../res/footer.php"); ?>
</body>
</html>