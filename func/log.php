<?php
include_once("sql.php");
function insertlog($operate,$affect,$type,$result=true,$action=null){
	INSERT("ELMS","log",[["operate",$operate],["affect",$affect],["type",$type],["result",($result?"success":"fail")],["action",$action],["randcode",md5(uniqid(rand(),true))]]);
}
?>