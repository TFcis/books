<?php
include_once("consolelog.php");
function sql($query,$oneline=true){
	$db=file_get_contents("../config/db.dat");
	$db=explode("\r\n",$db);
	$link = mysqli_connect($db[0],$db[1],$db[2],$db[3]);
	if(mysqli_connect_errno($link)){
		consolelog("Failed to connect to MySQL: " . iconv("big5","utf-8",mysqli_connect_error()));
	}
	$result = mysqli_query($link, $query);
	consolelog($query);
	consolelog($result);
	if(preg_match("/select/i",$query)){
		if($oneline)return mysqli_fetch_array($result);
		else return $result;
	}
}
function mfa($result){
	return mysqli_fetch_array($result);
}
?>