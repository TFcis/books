<?php
function sql($query,$select=true){
	$db=file_get_contents("../config/db.dat");
	$db=explode($db);
	$link = mysqli_connect($db[0],$db[1],$db[2],$db[3]);
	$result = mysqli_query($link, $query);
	echo "<script>console.log(\"".$query."\");</script>";
	if($select)return mysqli_fetch_array($result, MYSQLI_NUM);
}
?>