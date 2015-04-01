<?php
require_once("consolelog.php");
function WHERE($link,$where){
	if($where){
		$query="WHERE ";
		foreach($where as $index => $value){
			if($index!=0)$query.="AND ";
			if($value[2]=="REGEXP")$query.="`$value[0]` REGEXP '".mysqli_real_escape_string($link,str_replace("+","[+]",$value[1]))."' ";
			else if($value[2]==null)$query.="`$value[0]` = '".mysqli_real_escape_string($link,$value[1])."' ";
			else $query.="`$value[0]` $value[2] '".mysqli_real_escape_string($link,$value[1])."' ";
		}
		return $query;
	}
	else
		return "";
}
function LIMIT($limit){
	if($limit=="all"||$limit==null)
		return "";
	else if(is_array($limit))
		return "LIMIT ".$limit[0].",".$limit[1]." ";
	else
		return "LIMIT ".$limit." ";
}
function SELECT($dbname,$return,$table,$where=null,$order=null,$limit=array(0,1),$group=null){
	require("../config/db.php");
	$link = mysqli_connect($db[0],$db[1],$db[2],$dbname);
	if(mysqli_connect_errno($link))
		consolelog("Failed to connect to MySQL: " . iconv("big5","utf-8",mysqli_connect_error()));
	$query="SELECT ";
	if($return=="*")
		$query.="* ";
	else{
		foreach($return as $index => $value){
			if($index!=0)$query.=",";
			$query.="$value ";
		}
	}
	$query.="FROM `$table` ".WHERE($link,$where);
	if($group){
		$query.="GROUP BY ";
		foreach($group as $index => $value){
			if($index!=0)$query.=",";
			$query.="`$value` ";
		}
	}
	if($order){
		$query.="ORDER BY ";
		foreach($order as $index => $value){
			if($index!=0)$query.=", ";
			$query.="`$value[0]` ".($value[1]==""?"ASC":$value[1])." ";
		}
	}
	$query.=LIMIT($limit);
	//consolelog($query);
	$result=mysqli_query($link, $query);
	mysqli_close($link);
	return $result;
}
function INSERT($dbname,$table,$value){
	require("../config/db.php");
	$link = mysqli_connect($db[0],$db[1],$db[2],$dbname);
	if(mysqli_connect_errno($link))
		consolelog("Failed to connect to MySQL: " . iconv("big5","utf-8",mysqli_connect_error()));
	$query="INSERT INTO `$table` (";
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
	//consolelog($query);
	$result=mysqli_query($link, $query);
	mysqli_close($link);
	return $result;
}
function UPDATE($dbname,$table,$value,$where=null,$limit=1){
	require("../config/db.php");
	$link = mysqli_connect($db[0],$db[1],$db[2],$dbname);
	if(mysqli_connect_errno($link))
		consolelog("Failed to connect to MySQL: " . iconv("big5","utf-8",mysqli_connect_error()));
	$query="UPDATE `$table` SET ";
	foreach($value as $index => $temp){
		if($index!=0)$query.=",";
		$query.="`$temp[0]`='".mysqli_real_escape_string($link,$temp[1])."' ";
	}
	$query.=WHERE($link,$where).LIMIT($limit);
	//consolelog($query);
	$result=mysqli_query($link, $query);
	mysqli_close($link);
	return $result;
}
function DELETE($dbname,$table,$where=null,$limit=1){
	require("../config/db.php");
	$link = mysqli_connect($db[0],$db[1],$db[2],$dbname);
	if(mysqli_connect_errno($link))
		consolelog("Failed to connect to MySQL: " . iconv("big5","utf-8",mysqli_connect_error()));
	$query="DELETE FROM `$table` ".WHERE($link,$where).LIMIT($limit);
	//consolelog($query);
	$result=mysqli_query($link, $query);
	mysqli_close($link);
	return $result;
}
function SQL($dbname,$query){
	require("../config/db.php");
	$link = mysqli_connect($db[0],$db[1],$db[2],$dbname);
	if(mysqli_connect_errno($link))
		consolelog("Failed to connect to MySQL: " . iconv("big5","utf-8",mysqli_connect_error()));
	//consolelog($query);
	$result=mysqli_query($link, $query);
	mysqli_close($link);
	return $result;
}
function mfa($result){
	return mysqli_fetch_array($result);
}
function het($text){
	return htmlentities($text,ENT_QUOTES);
}
?>