<?php
function consolelog($text){
	echo "<script>var str=function(){/*";
	print_r($text);
	echo "*/}.toString().slice(14,-3);
	console.log(str);
	</script>";
}
?>