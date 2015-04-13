<?php
include_once("sql.php");
function insertlog($operate,$affect,$type,$result=true,$action=null){
	$query=new query;
	$query->table="log";
	$query->value=array(
		array("operate",$operate),
		array("affect",$affect),
		array("type",$type),
		array("result",($result?"success":"fail")),
		array("action",$action),
		array("randcode",md5(uniqid(rand(),true)))
	);
	INSERT($query);
}
?>