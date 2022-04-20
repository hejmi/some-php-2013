<?php
if (isset($_GET['check'])) {
	include('db_settings.php');
	$result = mysql_query("SELECT * FROM customers") or die (mysql_error());  
	while ($row = mysql_fetch_array($result)) {
		if ($row['usrname'] == $_POST['user'] && $row['passwd'] == $_POST['password']) {
			setcookie("user", $_POST['user'], time() + 3600);
			$_SESSION['user'] = $_POST['user'];
			header('Location: index.php');
			exit;
		} 
	}
	header('Location: /index.php');
	exit;
}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta charset="utf-8">
<!--
<form method="post" action="loginform.php?check" enctype="multipart/form-data">
<p>Personnummer: <small>(personnr anges 19901010XXXX)</small><br/><input type="text" name="user" id="user"></p>
<p>Lösenord: <br/><input type="password" name="password" id="password"></p>
<p><input type="submit" value="Logga in"></p>
</form>
-->
