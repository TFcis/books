<?php
include_once(__DIR__."/../config/config.php");
include_once($config["path"]["sql"]);
function checklogin(){
	global $msgbox;
	include_once("login-php-sdk/Login.php");
	include("../config/config.php");
	$login_system = new login_system;
	$status = $login_system->status();
	if($status->login===false){
		$data=(array)$status;
		$data["power"]=0;
		if($config["debug"]["login"]){
			$data["login"]=true;
			$data["id"]=0;
			$data["nickname"]="Debug Mode";
			$msgbox->add("danger","Debug Mode: Force Login");
		}
		if($config["debug"]["admin"]){
			$data["power"]=1;
			$msgbox->add("danger","Debug Mode: Force Admin");
		}
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
	if($config["debug"]["admin"]){
		$data["power"]=1;
		$msgbox->add("danger","Debug Mode: Force Admin");
	}
	return $data;
}
?>