<?php
include('../inloggad/db_settings.php');
$antal_orders = 0;
$result = mysql_query("SELECT * FROM orders"); 
 
while ($row = mysql_fetch_array($result)) {
	$result2 = mysql_query("SELECT * FROM orders_content WHERE orders_id=".$row['id']);	
	$num_rows = mysql_num_rows($result2);
	if ($num_rows == 0) {
		mysql_query("DELETE FROM orders WHERE id=".$row['id']);	
		$antal_orders++;
	}
}
		echo '<div style="margin-bottom:2px; padding:10px; padding-bottom:5px; border:1px dotted #888; text-align:center; background-color:#a5cc52;" id="deleted_id">';
		echo '<span align="center"><b>'.$antal_orders.'st</b> tomma ordrar raderades ur systemet!</span>';
		echo '</div>';
		


?>
