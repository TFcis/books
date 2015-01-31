<html>
<?php
include_once("../func/checklogin.php");
include_once("../func/sql.php");
?>
<head>
<meta charset="UTF-8">
<title>首頁-TFcisELMS</title>
</head>
<body topmargin="0" leftmargin="0" bottommargin="0">
<?php
include_once("../header.php");
?>
<center>
<table width="0" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height="50" colspan="3"></td>
	</tr>
	<tr>
		<td width="420" valign="top">
		<strong>開發者</strong>
		<hr>
		<table width="0" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td valign="top"><img src = 'https://fbcdn-sphotos-h-a.akamaihd.net/hphotos-ak-xpf1/v/t1.0-9/1619090_288040611401620_7201738893173011477_n.jpg?oh=6104f5511cf012c7e6a486d95f7d0760&oe=5552D150&__gda__=1432210833_ffaa247f6cabe37a277a98f32a1cb670' width="100" height="100"></img></td>
				<td>
				<p>
				<strong>Xiplus</strong><br>
				迷戀於各種API，並且已經產生了許多有用的代碼片段，其中一個包括透過Facebook來提醒你記得訂午餐（譯者注：這個沒有使用API）。
				</p>
				<p>
				如果看到拿著藍牙鍵盤在電腦教室的一端，而筆電在教室另一端，那麼很可能只是在測試無線鍵盤距離多遠還能夠收得到訊號。
				</p>
				<p style="text-align: right">
				<a title = "facebook" href = "http://www.facebook.com/profile.php?id=100005870494945" class = 'icon' style = "margin: 0 4px" target="_blank">Facebook</a>
				<a title = "github" href = "http://github.com/xi-plus" class = 'icon' style = "margin: 0 4px" target="_blank">GitHub</a>
				</p>
				<p style="text-align: right">
				撰寫：John / 翻譯：Xiplus &amp; Google
				</p>
				</td>
			</tr>
		</table>
		</td>
		<td width="100"></td>
		<td width="320" valign="top">
		<strong>簡介</strong>
		<hr>
		<p><strong>E-LMS (E-Library Management System)</strong>是一個開放原始碼專案，原始碼皆可在<a href = "https://github.com/TFcis/ELMS" target="_blank">GitHub</a>上找到。</p>
		<p>目前此系統用來管理南一中資訊社TFcis社部內的圖書，若要借書請和社團幹部詢問，所有圖書資訊和出借狀態都可透過本系統查詢。</p>
		<p>如果有興趣的話，你可以關注我們的發展或是對於此專案建立一個分支來做出貢獻。</p>
		
		<strong>更新日誌</strong>
		<hr>
		<table width="0" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td valign="top">2015.01.30</td>
			<td>權限檢查<br>
				登入後返回上一頁
				</td>
		</tr>
		<tr>
			<td valign="top">2015.01.29</td>
			<td>快捷鍵:Alt+數字<br>
				圖書資訊<br>
				借還書功能
				</td>
		</tr>
		<tr>
			<td valign="top">2015.01.28</td>
			<td>封禁功能<br>
				帳戶、圖書管理<br>
				SQL安全性修正
				</td>
		</tr>
		<tr>
			<td valign="top">2015.01.27</td>
			<td>密碼救援<br>
				資料驗證</td>
		</tr>
		<tr>
			<td valign="top">2015.01.26</td>
			<td>修改個人資料功能</td>
		</tr>
		<tr>
			<td width="100" valign="top">2015.01.25</td>
			<td>開始開發<br>
				基本介面<br>
				登入登出功能</td>
		</tr>
		</table>
	</td>
</tr>
</table>
</center>
</body>
</html>
