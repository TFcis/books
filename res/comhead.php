<?php
include(__DIR__."/../config/config.php");
include_once(__DIR__."/../func/SQL-function/sql.php");
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
<script async src="../res/bootstrap/js/bootstrap.min.js"></script>
<link href="../res/bootstrap/css/bootstrap.min.css" rel="stylesheet">
