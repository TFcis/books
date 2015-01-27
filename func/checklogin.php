<?php
include_once("sql.php");
function checklogin(){
	if($_COOKIE["ELMScookie"]==""){
		return false;
	}
	$row = sql("SELECT * FROM `session` WHERE `cookie` = '".$_COOKIE["ELMScookie"]."' LIMIT 0,1");
	if($row==""){
		return false;
	}
	$id=$row[0];
	$row = sql("SELECT * FROM `account` WHERE `id` = '".$id."' LIMIT 0,1");
	return $row;
}
?>