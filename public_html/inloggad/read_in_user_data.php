<?php
//echo date("Y-m-d H:i:s", time()); 
include('db_settings.php');
	$result = mysql_query("SELECT * FROM customers WHERE usrname = '". $_COOKIE['user']."'");
	$row = mysql_fetch_array($result);
	$customerId = $row['id'];
	$customerTypeId = $row['customer_type'];
	if ($row['customer_type'] == 1) { 
		$result2 = mysql_query("SELECT * FROM customers_youth WHERE customer_id = ".$customerId."");
		$row2 = mysql_fetch_array($result2);
		
		$customer_is_a_school = 0;
		$customer_fullname = utf8_encode($row2['forename']) ." ". utf8_encode($row2['lastname']);
		$customer_goes_to_school_id = $row2['school_id'];
		$customer_personal_id = $row2['personal_id'];
		$customer_country_id = $row2['country_id'];
		$customer_email = $row2['email'];
		
	} else { 
		$result2 = mysql_query("SELECT * FROM customers_school WHERE customer_id = ".$customerId."");
		$row2 = mysql_fetch_array($result2);
		
		$customer_fullname = utf8_encode($row2['contactperson']);
		$customer_is_a_school = 1;
		$customer_school_id = $row2['id'];
		$customer_school_name = utf8_encode($row2['school_name']);
		$customer_country_id = $row2['country_id'];
		$customer_email = $row2['email'];
	}
	
	$customer_type_id = $row['customer_type'];
?>
