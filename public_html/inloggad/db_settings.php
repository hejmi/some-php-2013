<?php
$dbhost = "localhost"; 
$dbuser = "emilstra_webb";
$dbpass = "etanno2012";
$databas = "emilstra_a2012";
$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Fel vid anslutning med databasen!');
mysql_select_db($databas);
//mysql_query("SET NAMES utf8");
//mysql_query("SET CHARACTER SET utf8"); 

?>