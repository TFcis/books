<?php
include_once("sql.php");
function checklogin(){
	include_once("login_sdk.php");
	$status = login_system::status();
	$data=array();
	$data["login"]=$status->login;
	if($data["login"]===false){
		return $data;
	}
	$query=new query;
	$query->column=array("*");
	$query->table="account";
	$query->where=array("id",$status->data->id);
	$query->limit=array(0,1);
	$temp=fetchone(SELECT($query));
	$data["id"]=$status->data->id;
	$data["user"]=$status->data->account;
	$data["email"]=$status->data->email;
	$data["name"]=$status->data->nickname;
	$data["power"]=$temp["power"];
	return $data;
}
?>