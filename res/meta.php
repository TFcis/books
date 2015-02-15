<link href="../res/css.css" rel="stylesheet" type="text/css">
<link rel="icon" href="../res/icon.ico" type="image/x-icon">
<?php
function meta($array=null){
	$meta["title"]="TFcis Books";
	$meta["type"]="website";
	$meta["description"]="目前此系統用來管理南一中資訊社TFcis社部內的圖書，若要借書請和社團幹部詢問，所有圖書資訊和出借狀態都可透過本系統查詢。";
	$meta["url"]="http://".url();
	$meta["image"]="http://books.tfcis.org/res/icon.png";
	if($array!=null){
		foreach($array as $temp){
			$meta[$temp[0]]=$temp[1];
		}
	}
	?>
	<meta property="og:title" content="<?php echo $meta["title"];?>"/>
	<meta property="og:type" content="<?php echo $meta["type"];?>"/>
	<meta property="og:description" content="<?php echo $meta["description"];?>"/>
	<meta property="og:url" content="<?php echo $meta["url"];?>"/>
	<meta property="og:image" content="<?php echo $meta["image"];?>"/>
<?php
}
?>