<?php
session_start();
if (isset($_GET['check'])) {
	include('../inloggad/db_settings.php');
	$result = mysql_query("SELECT * FROM eadmin");  
	while ($row = mysql_fetch_array($result)) {
		if ($row['username'] == $_POST['user'] && $row['password'] == $_POST['password']) {
			setcookie("adminuser", $_POST['user'], time() + 3600);
			//$_SESSION['adminuser'] = $_POST['user'];
			header('Location: index.php');
			exit;
		}		
	}
}
?>
<html>
<head>
<title>Emils Trädgård - Admin</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta charset="utf-8">
</head>
<body height="100%" style="background:none;">
<link rel="stylesheet" href="../css/styles.css" type="text/css" />
<p>&nbsp;</p><p>&nbsp;</p>
<div style="width:100%; height:250px; text-align:center;" class="loginscreen">
<h2 class="caption">Emils Trädgård Administration</h2>
		
<form method="post" action="?check" enctype="multipart/form-data">
<p>Användare: <br/><input type="text" name="user" id="user"></p>
<p>Lösenord: <br/><input type="password" name="password" id="password"></p>
<p><input type="submit" value="Logga in" class="whitebutton"></p>
</form>
</div>
