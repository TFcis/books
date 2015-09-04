<?php

class login_system{
	
	static $url = "http://www.tfcis.org/login/";
	
	public static function status(){
		@session_start();
		if(@$_GET["logout"]==true){
			unset($_SESSION["user"]);
			header("Location:".self::geturl("logout",$_GET["redirect_url"]));
		}
		if(isset($_SESSION["user"])){
			return (object)array( "login"=>true, "data"=>$_SESSION["user"] );
		}else if(isset($_GET["cookie"])){
			$cookie = $_GET["cookie"];
			$data = file_get_contents(self::$url."api/user.php?cookie=".$cookie);
			$data = json_decode($data);
			if($data->status === "success"){
				$_SESSION["user"] = $data->result;
				header("Location:".$_SERVER['PHP_SELF']);
			}else if($data->status === "error"){
				if($data->result === "notfound"){
					// cookie not found
				}else{
					throw new exception("Login API returned an error status");
				}
			}else{
				throw new exception("Unexpected API result");
			}
		}else{
			return (object)array( "login"=>false, "data"=>null );
		}
	}
	
	public static function getLoginUrl($redirect_url=""){
		return self::geturl("login",$redirect_url);
	}
	
	public static function getLogoutUrl($redirect_url=""){
		return "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?logout=true&redirect_url=".$redirect_url;
	}
	
	private static function geturl($page,$redirect_url=""){
		if($redirect_url == "") $redirect_url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		return self::$url . "$page.php?continue=" . urlencode($redirect_url);
	}
}