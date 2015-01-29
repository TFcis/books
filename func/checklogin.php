<?php
include_once("sql.php");
function checklogin(){
	if($_COOKIE["ELMScookie"]==""){
		return false;
	}
	$row = mfa(SELECT("*","session",[["cookie",$_COOKIE["ELMScookie"]]],null,[0,1]));
	if($row==""){
		return false;
	}
	$id=$row[0];
	return mfa(SELECT("*","account",[["id",$id]],null,[0,1]));
}
?>