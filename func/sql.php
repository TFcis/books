<?php
include_once("consolelog.php");
function sql($query,$select=true){
	$db=file_get_contents("../config/db.dat");
	$db=explode("\r\n",$db);
	$link = mysqli_connect($db[0],$db[1],$db[2],$db[3]);
	if(mysqli_connect_errno($link)){
		consolelog("Failed to connect to MySQL: " . iconv("big5","utf-8",mysqli_connect_error()));
	}
	$result = mysqli_query($link, $query);
	consolelog($query);
	if($select)return mysqli_fetch_array($result, MYSQLI_NUM);
}
?>