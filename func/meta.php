<?php
class meta{
	public $meta=array(
		"og:site_name" => "TFcis Books",
		"og:title" => "TFcis Books",
		"og:type" => "website",
		"og:description" => "目前此系統用來管理南一中資訊社TFcis社部內的圖書，若要借書請和社團幹部詢問，所有圖書資訊和出借狀態都可透過本系統查詢。",
		"og:url" => "",
		"fb:app_id" => "340933656095364"
	);

	function __construct(){
		include(__DIR__."/../config/config.php");
		$this->meta["og:url"] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}

	function config($arr){
		foreach ($arr as $key => $value) {
			$this->$meta[$key] = $value;
		}
	}

	function output(){
		foreach ($this->meta as $key => $value) {
			echo "<meta property=\"".$key."\" content=\"".$value."\">\n";
		}
		echo "<title>".$this->meta["og:title"]."</title>\n";
	}
}
?>