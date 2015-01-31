<?php
function url(){
	$url=$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	return substr($url,0,strrpos($url,"/")+1);
}
?>