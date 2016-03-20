<?php
include(__DIR__."/../config/config.php");
include_once($config["path"]["sql"]);
include_once(__DIR__."/../func/log.php");
include_once(__DIR__."/../func/msgbox.php");
$msgbox=new msgbox;
include_once(__DIR__."/../func/checklogin.php");
$login=checklogin();
include(__DIR__."/../func/meta.php");
$meta=new meta;
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="../res/jquery.min.js"></script>
<script src="../res/bootstrap/js/bootstrap.min.js"></script>
<link href="../res/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="../res/css.css" rel="stylesheet" type="text/css">
