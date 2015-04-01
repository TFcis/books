<?php
include_once("sql.php");
function checklogin(){
	if($_COOKIE["ELMScookie"]=="")return false;
	$row = mfa(SELECT("ELMS",["id"],"session",[["cookie",$_COOKIE["ELMScookie"]]],null,[0,1]));
	if($row=="")return false;
	return mfa(SELECT("ELMS",["id","user","name","email","power"],"account",[["id",$row["id"]]],null,[0,1]));
}
?>