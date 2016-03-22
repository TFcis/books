<div id="wrap" sytle="min-height: 100%;">
	<header class="navbar navbar-static-top bs-docs-nav" id="top" role="banner" style="background-color: #FFFFFF; margin: 0;">
		<div class="container">
			<div class="navbar-header" style="background-color: #FFFFFF;">
				<button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse" style="background-color:#337AB7;">
					<span class="sr-only" style="background-color:#FFFFFF;">巡覽切換</span>
					<span class="icon-bar" style="background-color:#FFFFFF;"></span>
					<span class="icon-bar" style="background-color:#FFFFFF;"></span>
					<span class="icon-bar" style="background-color:#FFFFFF;"></span>
				</button>
				<a href="../home" class="navbar-brand">
					<div style="float: left;">
						<img src="../res/icon.png" alt="臺南一中資訊社TFcis" height="30px">
					</div>
					<div style="float: left;">
						&nbsp;&nbsp;<span style="font-weight: bold; font-size: px; font-family: '標楷體';">電子化圖書管理系統</span><br>
					</div>
				</a>
			</div>
			<nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
				<ul class="nav navbar-nav">
					<li>
						<a href="../home">首頁</a>
					</li>
					<li>
						<a href="../search">館藏查詢</a>
					</li>
					<li>
						<a href="../user">目前借閱</a>
					</li>
					<li>
						<a href="../borrow">借書</a>
					</li>
					<?php 
					if($login["power"]>0){ ?>
					<li>
						<a href="../return">還書</a>
					</li>
					<li>
						<a href="../managebook">圖書管理</a>
					</li>
					<li>
						<a href="../manageuser">使用者管理</a>
					</li>
					<li>
						<a href="../log">Log</a>
					</li>
					<?php } ?>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<?php
					if($login["login"]===false){
					?>
						
						<li><a href="<?php echo $login["url"]; ?>">登入</a></li>
					<?php
					}
					else{
						?><a href="<?php echo $login["url"]; ?>"><?php echo "<li>目前登入: ".$login["nickname"];
					?>
						&nbsp;&nbsp;登出</a></li>
					<?php
					}
					?>
				</ul>
			</nav>
		</div>
	</header>
<?php
	$msgbox->show();
?>
	<div class="container-fluid">