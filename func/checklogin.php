<?php
include_once("sql.php");
function checklogin(){
	if(@$_COOKIE["ELMScookie"]=="")return false;
	$query=new query;
	$query->column="id";
	$query->table="session";
	$query->where=array("cookie",$_COOKIE["ELMScookie"]);
	$query->limit=array(0,1);
	$row=fetchone(SELECT($query));
	if($row=="")return false;
	$query=new query;
	$query->column=array("id","user","name","email","power");
	$query->table="account";
	$query->where=array("id",$row["id"]);
	$query->limit=array(0,1);
	return fetchone(SELECT($query));
}
?>