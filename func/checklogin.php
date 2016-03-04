<?php
include_once(__DIR__."/../config/config.php");
include_once($config["path"]["sql"]);
function checklogin(){
	include_once("login-php-sdk/Login.php");
	$login_system = new login_system;
	$status = $login_system->status();
	if($status->login===false){
		$data=(array)$status;
		$data["power"]=0;
		return $data;
	}
	$data["login"]=true;
	$data["id"]=$status->data->id;
	$data["account"]=$status->data->account;
	$data["email"]=$status->data->email;
	$data["nickname"]=$status->data->nickname;
	$data["url"]=$status->url;
	$query=new query;
	$query->column=array("*");
	$query->table="powerlist";
	$query->where=array("id",$status->data->id);
	$query->limit=array(0,1);
	$temp=fetchone(SELECT($query));
	if($temp==false)$data["power"]=0;
	else $data["power"]=$temp["power"];
	return $data;
}
?>