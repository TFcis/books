<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
<?php
include(__DIR__."/../res/comhead.php");
$meta->output();
$msgbox->add("success","Welcome!");
?>
</head>
<body>
<?php include(__DIR__."/../res/header.php"); ?>
<?php @include(__DIR__."/information.php"); ?>
<?php include(__DIR__."/../res/footer.php"); ?>
</body>
</html>
