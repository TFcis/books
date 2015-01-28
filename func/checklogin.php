<?php
include_once("sql.php");
function checklogin(){
	if($_COOKIE["ELMScookie"]==""){
		return false;
	}
	$row = mfa(SELECT("*","session",[["cookie",$_COOKIE["ELMScookie"]]],[0,1]));
	if($row==""){
		return false;
	}
	$id=$row[0];
	$row = mfa(SELECT("*","account",[["id",$id]],[0,1]));
	return $row;
}
?>