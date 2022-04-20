<?php
session_start();
if (isset($_GET['check'])) {
	include('../inloggad/db_settings.php');
	$result = mysql_query("SELECT * FROM eadmin");  
	while ($row = mysql_fetch_array($result)) {
		if ($row['username'] == $_POST['user'] && $row['password'] == $_POST['password']) {
			setcookie("adminuser", $_POST['user'], time() + 3600);
			$_SESSION['adminuser'] = $_POST['user'];
			header('Location: index.php');
			exit;
		}		
	}
}
?>
<body height="100%" style="background:none;">
<link rel="stylesheet" href="../css/styles.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<h2 class="grid_12 caption clearfix">Emils Trädgård Administration</h2>
		
		<div class="hr dotted clearfix" size="400">&nbsp;</div>

		<div class="grid_8">

<form method="post" action="?check" enctype="multipart/form-data">
<p>Användare: <br/><input type="text" name="user" id="user"></p>
<p>Lösenord: <br/><input type="password" name="password" id="password"></p>
<p><input type="submit" value="Logga in" class="button"></p>
</form>
