<?php
include_once("consolelog.php");
function SELECT($return,$table,$where=null,$order=null,$limit=[0,1]){
	$db=file_get_contents("../config/db.dat");
	$db=explode("\r\n",$db);
	$link = mysqli_connect($db[0],$db[1],$db[2],$db[3]);
	if(mysqli_connect_errno($link)){
		consolelog("Failed to connect to MySQL: " . iconv("big5","utf-8",mysqli_connect_error()));
	}
	$query="SELECT ";
	if($return=="*")$query.="* ";
	else {
		foreach($return as $index => $value){
			if($index!=0)$query.=",";
			$query.="$value ";
		}
	}
	$query.="FROM `$table` ";
	if($where){
		$query.="WHERE ";
		foreach($where as $index => $value){
			if($index!=0)$query.="AND ";
			if($value[2]=="REGEXP")$query.="`$value[0]` REGEXP '[".mysqli_real_escape_string($link,$value[1])."]' ";
			else if($value[2]==null)$query.="`$value[0]` = '".mysqli_real_escape_string($link,$value[1])."' ";
			else $query.="`$value[0]` $value[2] '".mysqli_real_escape_string($link,$value[1])."' ";
		}
	}
	if($order){
		$query.="ORDER BY ";
		foreach($order as $index => $value){
			if($index!=0)$query.=", ";
			$query.="`$value[0]` $value[1] ";
		}
	}
	if($limit!="all"){
		$query.="LIMIT $limit[0],$limit[1]";
	}
	consolelog($query);
	return mysqli_query($link, $query);
}
function INSERT($table,$value){
	$db=file_get_contents("../config/db.dat");
	$db=explode("\r\n",$db);
	$link = mysqli_connect($db[0],$db[1],$db[2],$db[3]);
	if(mysqli_connect_errno($link)){
		consolelog("Failed to connect to MySQL: " . iconv("big5","utf-8",mysqli_connect_error()));
	}
	$query="INSERT INTO ";
	$query.="`$table` ";
	$query.="(";
	foreach($value as $index => $temp){
		if($index!=0)$query.=",";
		$query.="`$temp[0]` ";
	}
	$query.=")VALUES(";
	foreach($value as $index => $temp){
		if($index!=0)$query.=",";
		$query.="'".mysqli_real_escape_string($link,$temp[1])."' ";
	}
	$query.=")";
	consolelog($query);
	return mysqli_query($link, $query);
}
function UPDATE($table,$value,$where=null){
	$db=file_get_contents("../config/db.dat");
	$db=explode("\r\n",$db);
	$link = mysqli_connect($db[0],$db[1],$db[2],$db[3]);
	if(mysqli_connect_errno($link)){
		consolelog("Failed to connect to MySQL: " . iconv("big5","utf-8",mysqli_connect_error()));
	}
	$query="UPDATE ";
	$query.="`$table` ";
	$query.="SET ";
	foreach($value as $index => $temp){
		if($index!=0)$query.=",";
		$query.="`$temp[0]`='".mysqli_real_escape_string($link,$temp[1])."' ";
	}
	$query.="WHERE ";
	if($where){
		foreach($where as $index => $value){
			if($index!=0)$query.="AND ";
			$query.="`$value[0]`='".mysqli_real_escape_string($link,$value[1])."' ";
		}
	}
	consolelog($query);
	return mysqli_query($link, $query);
}
function DELETE($table,$where=null){
	$db=file_get_contents("../config/db.dat");
	$db=explode("\r\n",$db);
	$link = mysqli_connect($db[0],$db[1],$db[2],$db[3]);
	if(mysqli_connect_errno($link)){
		consolelog("Failed to connect to MySQL: " . iconv("big5","utf-8",mysqli_connect_error()));
	}
	$query="DELETE FROM ";
	$query.="`$table` ";
	$query.="WHERE ";
	if($where){
		foreach($where as $index => $value){
			if($index!=0)$query.="AND ";
			$query.="`$value[0]`='".mysqli_real_escape_string($link,$value[1])."' ";
		}
	}
	consolelog($query);
	return mysqli_query($link, $query);
}
function mfa($result){
	return mysqli_fetch_array($result);
}
function het($text){
	return htmlentities($text,ENT_QUOTES);
}
?>