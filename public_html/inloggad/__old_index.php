<?php
if (!isset($_COOKIE['user'])) {
	header('Location: /index.php');
	exit;
}
if (isset($_GET['logout'])) {
	setcookie('user','', time() - 3600);
	header('Location: /index.php');
	exit;
}
session_start(); 
// Läs in kunddata samt korrekt språkfil
include 'read_in_user_data.php';
include 'db_settings.php';

if (!isset($_GET['lng'])) {
	if (isset($_COOKIE['lng'])) {
		include 'lang/'.$_COOKIE['lng'].'.php';
		$_GET['lng'] = $_COOKIE['lng'];
	} else {
		if (substr ($_SERVER ["HTTP_ACCEPT_LANGUAGE"], 0, 2) == "fi") {
			setcookie("lng", "fi", time() + 3600);
			$_GET['lng'] = "fi";
		} else {
			setcookie("lng", "sv", time() + 3600);
			$_GET['lng'] = "sv";
		}
		include 'lang/'.$_GET['lng'].'.php';
	}
} else {
	if ($_GET['lng'] == "fi") {
    	setcookie("lng", "fi", time() + 3600);
    	include 'lang/fi.php';
	} else {
		setcookie("lng", "sv", time() + 3600);
   	 include 'lang/sv.php';
	}
}

// Ta bort ur order

if (isset($_GET['deletefromorder'])) {
	mysql_query("DELETE FROM orders_content WHERE orders_id=".$_GET['orders_id']." AND product_art_id='".$_GET['product_art_id']."'");
	$checkemptyorder = mysql_query("SELECT * FROM orders_content WHERE orders_id = ".$_GET['orders_id']);
	$is_empty = mysql_num_rows($checkemptyorder);
	
	if ($is_empty <= 0) {
		mysql_query("DELETE FROM orders WHERE id=".$_GET['orders_id']);
		$_SESSION['errormessage'] = '<h6 style="color:red">'.$_ERROR_ORDER_EMPTY;
	}
	
	
	if ($customer_is_a_school == 0) {
		header('Location: index.php?ordercreate');
		exit;
	} else {
	 	header('Location: index.php?ordercreate&pupil_id='.$_GET['personalid']);
		exit;
	}
}

// Skicka in beställningen
if (isset($_GET['confirmorder'])) {
	mysql_query("UPDATE orders SET order_status_id = 2 WHERE id=".$_GET['orders_id']);
	mysql_query("UPDATE orders SET order_last_change_date = '".date('Y-m-d H:i:s', time())."' WHERE id=".$_GET['orders_id']);
	
	if ($customer_is_a_school == 0) {
	$result = mysql_query("SELECT orders_id, product_art_id, SUM(quantity_of_product) FROM orders_content WHERE orders_id=".$_GET['orders_id']." GROUP BY product_art_id ORDER BY product_art_id");
	
	$to = $customer_email;
	$subject = $_CONF_EMAIL_SUB;
	$body = $_CONF_EMAIL_HEAD . "\r\n";
	$salestotal = 0;
	while ($r = mysql_fetch_array($result)) {
		$body .= $r['SUM(quantity_of_product)'].' '.$r['product_art_id'].' ';
		$dresult = mysql_query("SELECT * FROM _products WHERE art_id ='".$r['product_art_id']."'");
		$dr = mysql_fetch_array($dresult);
		if ($customer_country_id == 2) {
			$body .= utf8_encode($dr['art_name_fi'])."\r\n";
		} else {
			$body .= utf8_encode($dr['art_name_sv'])."\r\n";
		}
		
		$salestotal_EUR = $salestotal_EUR + ($r['SUM(quantity_of_product)'] * $dr['price_fi']);
		$salestotal_SEK = $salestotal_SEK + ($r['SUM(quantity_of_product)'] * $dr['price_se']);
	}
	
	$earn_SEK = ($salestotal_SEK/10) * 3;
	$earn_EUR = ($salestotal_EUR/10) * 3;
	if ($customer_country_id == 2) {
		$body .= "\r\n".$_CONF_EMAIL_SUM." €".$salestotal_EUR." ".$_CONF_EMAIL_EARN." €".$earn_EUR.".";
	} else {
		$body .= "\r\n".$_CONF_EMAIL_SUM." ".$salestotal_SEK."kr ".$_CONF_EMAIL_EARN." ".$earn_SEK."kr.";
	}
	mail($to, $subject, $body, "From: noreply@emilstradgard.se");
	}
		
	if ($customer_is_a_school == 0) {
		header('Location: index.php?ordercreate');
		exit;
	} else {
	 	header('Location: index.php?ordercreate&pupil_id='.$_GET['pupil_id']);
	 	exit;
	}
}
// Kolla order och lägg till
if (isset($_GET['checkorder'])) {
	
//Se om artikel existerar eller finns i lager
	for ($i=0;$i<50;$i++) {
		setcookie("art_id_field_".$i, $_POST['art_id_field_'.$i], time() + 3600);
		setcookie("q_art_id_field_".$i, $_POST['q_art_id_field_'.$i], time() + 3600);
		$art_id = $_POST['art_id_field_'.$i];
		if ($art_id == "") continue;
		$result = mysql_query("SELECT * FROM _products WHERE art_id = '".$art_id."'");
		$num_rows = mysql_num_rows($result);
		$_SESSION['error_art_id'.$i] = 0;
		if ($num_rows == "1") {
			if ($_POST['q_art_id_field_'.$i] == "") {				 
				$_SESSION['errormessage'] = '<h6 style="color:red">'.$_ERROR_QUANTITY;
				$problem = 1;
				$_SESSION['error_art_id'.$i] = 1;
				 if ($customer_is_a_school == 0) {
					header("Location: " . $_SERVER['HTTP_REFERER']);
					exit;
				 } else {
				 	header("Location: " . $_SERVER['HTTP_REFERER'] . "&pupil_id=" .$_GET['personalid']);
				 	exit;
				 }
			}
		} else {
			$problem = 1;
			$_SESSION['errormessage'] = '<h6 style="color:red">'.$_ERROR_NO_SUCH_ARTICLE;
			$_SESSION['error_art_id'.$i] = 1;
				 if ($customer_is_a_school == 0) {
					header("Location: " . $_SERVER['HTTP_REFERER']);
					exit;
				 } else {
				 	header("Location: " . $_SERVER['HTTP_REFERER'] . "&pupil_id=" .$_GET['personalid']);
				 	exit;
				 }
			
			
		}
	}
	
	$latestOrderByCustomer = mysql_query("SELECT * FROM orders WHERE customer_personal_id =".$_GET['personalid']." ORDER BY order_created_date DESC LIMIT 1");
	$orders_num_rows = mysql_num_rows($latestOrderByCustomer);
	
	if ($orders_num_rows == 0) {
		mysql_query("INSERT INTO orders (customer_personal_id) VALUES ('".$_GET['personalid']."')");
		$latestOrderByCustomer = mysql_query("SELECT * FROM orders WHERE customer_personal_id =".$_GET['personalid']." ORDER BY order_created_date DESC LIMIT 1");
	}
	
	$latestOrder = mysql_fetch_array($latestOrderByCustomer);
	$latestOrderId = $latestOrder['id'];
	
	for ($i=0;$i<50;$i++) {
		$art_id = $_POST['art_id_field_'.$i];
		$q_art_id = $_POST['q_art_id_field_'.$i];
		//if (isset($_POST['art_id_field_'.$i])) {
			echo $i.'<br>';
			mysql_query("INSERT INTO orders_content (orders_id, product_art_id, quantity_of_product) VALUES (".$latestOrderId.",'".$art_id."',".$q_art_id.")") or exit(mysql_error());
		//}
		setcookie("art_id_field_".$i, "", time() - 3600);
		setcookie("q_art_id_field_".$i, "", time() - 3600);
	 }	
	 $_SESSION['errormessage'] = '<h6 style="color:green">'.$_ORDER_DONE_MESSAGE;
	 if ($customer_is_a_school == 0) {
		//header('Location: index.php?ordercreate');
		exit;
	 } else {
	 	//header('Location: index.php?ordercreate&pupil_id='.$_GET['personalid']);
	 	exit;
	 }
}

// Välkomstmeddelande och kundmenyer
?>
<html>
<head>
<title><?php echo $_TITLE; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../css/styles.css" type="text/css" />

   <SCRIPT language=Javascript>
   
      <!--
     
      function validateForm()
      {
	  	if (document.forms["pupilform"]["elev_fornamn"].value.length<2)
	  	{
	  		alert("<?php echo $_ERROR_PUPIL_FORENAME; ?>");
	  		return false;
	  	}
	  	if (document.forms["pupilform"]["elev_efternamn"].value.length<2)
	  	{
	  		alert("<?php echo $_ERROR_PUPIL_LASTNAME; ?>");
	  		return false;
	  	}
 	  	if (document.forms["pupilform"]["elev_personnr"].value.length < 12 || document.forms["pupilform"]["elev_personnr"].value.length > 12 )
	  	{
	  		alert("<?php echo $_ERROR_PUPIL_PID; ?>");
	  		return false;
	  	}
	  	
	  }
	  
	  function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode;
         if (charCode==8 || (charCode >= 48 && charCode <= 57) || (charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122)) {
            return true;
         } else {
         return false;
      }   }
      //-->
   </SCRIPT>


</head>
<body height="100%" style="background:none;">
<?php
echo '<h3>'.$_WELCOME.'<a href="?'.$_SERVER['QUERY_STRING'].'&lng=fi"><img src="../images/flag_fi.png" boder="0"></a> <a href="?'.$_SERVER['QUERY_STRING'].'&lng=sv"><img src="../images/flag_sv.png" boder="0"></a><br/>';
echo '<span style="font-size:14px;">'.$customer_school_name.'</span></h3>';
if ($customer_is_a_school == 0) {
	echo '<p><a href="?ordercreate">'.$_ORDER_CREATE.'</a> | <!--<a href="?orderoverview">'.$_ORDER_OVERVIEW.'</a> |--> <a href="?changemyinfo">'.$_CHANGE_MY_INFO.'</a> | <a href="?logout">'.$_LOG_ME_OUT.'</a></p>';
} else { 
	echo '<p><a href="?changemyinfo">'.$_CHANGE_MY_INFO_SCHOOL.'</a> | <a href="?pupilsoverview">'.$_PUPILS_OVERVIEW.'</a> | <a href="?logout">'.$_LOG_ME_OUT.'</a></p>';
}

// Lägg till elev
if (isset($_GET['pupilcreate'])) {
	if (isset($_GET['addpupil'])) {
		$pupil_result = mysql_query("SELECT * FROM customers_youth WHERE personal_id =".$_POST['elev_personnr']);
		$pupils = mysql_num_rows($pupil_result);
		if ($pupils == 1) {
			echo '<h6 style="color:red">'.$_ERROR_PUPIL_PID_EXISTS.'</h6>';
		} else {
			mysql_query("INSERT INTO customers_youth (customer_id, personal_id, school_id, country_id, forename, lastname) VALUES (0, '".$_POST['elev_personnr']."', ".$customer_school_id.", '".$customer_country_id."', '".$_POST['elev_fornamn']."', '".$_POST['elev_efternamn']."')");
			echo '<h6 style="color:green">'.$_PUPIL_CREATED.'</h6>';
		}
	}
	echo '<h4>'.$_PUPIL_REG_TITLE.'</h4>';
	echo '<hr size="2" align="left" width="800" style="color:#BBBBBB";>';
	?>
	
	<form method="post" name="pupilform" onsubmit="return validateForm()" id="pupilform" action="index.php?pupilcreate&addpupil">
	<p><?php echo $_PUPIL_REG_NAME;?>:<br/>
	<input type="text" name="elev_fornamn"></p>
	<p><?php echo $_PUPIL_REG_LASTNAME;?>:<br/>
	<input type="text" name="elev_efternamn"></p>
	<p><?php echo $_PUPIL_REG_PERSONALID;?>:<br/>
	<input type="text" name="elev_personnr" onkeypress="return isNumberKey(event)"></p>
	<p><input type="submit" class="button" value="<?php echo $_PUPIL_REG_SUBMIT;?>"></p>
	</form>
	
	<?php
}

// Ändra kontaktinformation
if (isset($_GET['changemyinfo'])) {
	if ($customer_is_a_school == 0) {
		echo '<h6>'.$_CHANGE_MY_INFO.'</h6>';
	} else {
		echo '<h6>'.$_CHANGE_MY_INFO_SCHOOL.'</h6>';
	}
	echo $_FUNCTION_NOT_COMPLETED;
}

// Skapa ny order 
if (isset($_GET['ordercreate'])) {
	session_destroy();
	for ($i=0;$i<50;$i++) {
		setcookie("art_id_field_".$i, "", time() - 3600);
		setcookie("q_art_id_field_".$i, "", time() - 3600);
	} 
	echo '<h4>'.$_ORDER_TITLE.'</h4>';
	echo '<p style="width:800; text-align:justify;">'.$_ORDER_INFO.'</p>';
	echo '<hr size="2" align="left" width="800" style="color:#BBBBBB";>';
	if ($customer_is_a_school == 1) {
		$getpupilname = mysql_query("SELECT * FROM customers_youth WHERE personal_id =".$_GET['pupil_id']);
		$pupilrow = mysql_fetch_array($getpupilname);
		echo '<p><b>'.$_PUPIL_NAME.':</b> '.$pupilrow['forename'].' '.$pupilrow['lastname'].'</b></p>';
	}
	echo $_SESSION['errormessage'].'</h6>';
	
	include('db_settings.php');
	$latestOrderByCustomer = mysql_query("SELECT * FROM orders WHERE customer_personal_id =".$_GET['pupil_id'].$customer_personal_id." ORDER BY order_created_date DESC LIMIT 1");
	$orders_num_rows = mysql_num_rows($latestOrderByCustomer);
	$row = mysql_fetch_array($latestOrderByCustomer);
		if ($orders_num_rows == 1) {
			if($row['order_status_id'] == 1) {
			echo '<h6>'.$_ORDER_EXIST.'</h6><p>';
			if ($customer_is_a_school == 1) {
				$getpupilname = mysql_query("SELECT * FROM customers_youth WHERE personal_id =".$_GET['pupil_id']);
				$pupilrow = mysql_fetch_array($getpupilname);
				echo '<b>'.$_PUPIL_NAME.':</b> '.$pupilrow['forename'].' '.$pupilrow['lastname'].'</b><br/>';
			}
			echo '<i>'.$_ORDER_DELETE_ROW.'</i></p>';
			$result = mysql_query("SELECT orders_id, product_art_id, SUM(quantity_of_product) FROM orders_content WHERE orders_id= ".$row['id']." GROUP BY product_art_id ORDER BY product_art_id");
			echo '<table class="tabell" cellpadding="4"><tr>';
			while ($r = mysql_fetch_array($result)) {
				$orders_id = $r['orders_id'];
				$i++;
				if ($i % 5 == 0) echo '</tr><tr>';				
				echo '<td valign="top" align="right" onmouseout="style.backgroundColor=\'#dddddd\'" onmouseover="style.backgroundColor=\'#888888\'" style="cursor:pointer; background-color:#DDD;"><a href="?deletefromorder&orders_id='.$r['orders_id'].'&product_art_id='.$r['product_art_id'].'">'.$r['SUM(quantity_of_product)'].' '. $_ORDER_Q .' '.$r['product_art_id'].'</a></td>';
			}
			echo '</tr></table>';
		
			?>
			<p><input type="button" class="button" onClick="parent.location='index.php?confirmorder&pupil_id=<?php echo $_GET['pupil_id'];?>&orders_id=<?php echo $orders_id;?>'"  value="<?php echo $_BUTTON_SEND_ORDER;?>"></p>
			<?php
			echo '<hr size="1" align="left" width="800" style="color:#DDDDDD";>';
			echo '<h6>'.$_ORDER_ADD_MORE.'</h6>';
			
			} else { //ORDER INSKICKAD
				
			echo '<h6>'.$_ORDER_IS_ORDERED.'<span style="font-size:12px;">, #'.$row['id'].'</span></h6><p>';

			echo '<i>'.$_ORDER_IS_ORDERED_TEXT.'</i></p>';
			$result = mysql_query("SELECT orders_id, product_art_id, SUM(quantity_of_product) FROM orders_content WHERE orders_id= ".$row['id']." GROUP BY product_art_id ORDER BY product_art_id");
			echo '<table class="tabell" cellpadding="4">';
			while ($r = mysql_fetch_array($result)) {
				$i++;
				$result2 = mysql_query("SELECT * FROM _products WHERE art_id = '".$r['product_art_id']."'");
				$r2 = mysql_fetch_array($result2);
				$salestotal_EUR = $salestotal_EUR + ($r['SUM(quantity_of_product)'] * $r2['price_fi']);
				$salestotal_SEK = $salestotal_SEK + ($r['SUM(quantity_of_product)'] * $r2['price_se']);				
				echo '<tr><td align="right">'.$r['SUM(quantity_of_product)'].' '. $_ORDER_Q .'</td><td>'.$r['product_art_id'].'</td><td>'.utf8_encode($r2['art_name_'.$_COOKIE['lng']]).'</td></tr>';
			}
			

	
	
			$earn_SEK = ($salestotal_SEK/10) * 3;
			$earn_EUR = ($salestotal_EUR/10) * 3;
			if ($customer_country_id == 2) {
				echo "<tr><td></td><td></td><td align='left'>".$_CONF_EMAIL_SUM." <b>€".$salestotal_EUR."</b> ".$_CONF_EMAIL_EARN." <b>€".$earn_EUR."</b>.</td></tr>";
			} else {
				echo "<tr><td></td><td></td><td align='left'>".$_CONF_EMAIL_SUM." <b>".$salestotal_SEK."kr</b> ".$_CONF_EMAIL_EARN." <b>".$earn_SEK."kr</b>.</td></tr>";
			}
			//echo '<tr><td></td><td></td><td align="left">'.$_CONF_EMAIL_SUM.' <b>'.$salestotal_SEK.'kr (€'.$salestotal_EUR.')</b> '.$_CONF_EMAIL_EARN.' <b>'.$earn_SEK.'kr (€'.$earn_EUR.')</b></td></tr>';
			
			echo '</tr></table>';
			exit;
			}
		}
		
	unset($_SESSION['errormessage']);
	if (isset($_GET['pupil_id'])) {

	}
?>
<form method="post" enctype="text/plain" action="index.php?checkorder&personalid=<?php echo $_GET['pupil_id'].$customer_personal_id; ?>">
<table border="0" width="750" class="tabell">
<tr><td valign="top" align="left"><?php echo $_ORDER_ARTNO_QUANTITY; ?></td><td valign="top" align="left"><?php echo $_ORDER_ARTNO_QUANTITY; ?></td><td valign="top" align="left"><?php echo $_ORDER_ARTNO_QUANTITY; ?></td><td valign="top" align="left"><?php echo $_ORDER_ARTNO_QUANTITY; ?></td><td valign="top" align="left"><?php echo $_ORDER_ARTNO_QUANTITY; ?></td></tr>
<?php
echo '<tr>';
if (!isset($_SESSION['error_art_id'])) $_SESSION['error_art_id'] ="1000";
for ($i=0;$i<50;$i++) {
	if ($i == 5 || $i == 10 || $i == 15 || $i == 20 || $i == 25 || $i == 30 || $i == 35 || $i == 40 || $i == 45) echo '</tr><tr>';
//	if ($_SESSION['error_art_id'] == $i) {
//		$art_id_style = "style='color:red;'";
//	} else {
//		$art_id_style = "";
//	}
	if ($_SESSION['error_art_id'.$i] == 1) {
		$art_id_style = "style='color:red;'";
	} else {
		$art_id_style = "";
	}	
	echo '<td valign="top" align="left"><input size="5" type="text" id="art_id_field_'.$i.'" name="art_id_field_'.$i.'" value="'.$_COOKIE['art_id_field_'.$i].'" '.$art_id_style.'> <input size="5" type="text" id="q_art_id_field_'.($i).'" name="q_art_id_field_'.($i).'" value="'.$_COOKIE['q_art_id_field_'.$i].'" onkeypress="return isNumberKey(event)"></td>';

}
$_SESSION['error_art_id']="1000";
?>
</table>
<input type="submit" class="button" value="<?php echo $_BUTTON_SAVE; ?>">
</form> 
<?php
}



// Elevöversikt 
if (isset($_GET['pupilsoverview'])) {
	echo '<p><h5>'.$_PUPILS_OVERVIEW.' <span style="font-size:12px; font-weight:normal;"><a href="index.php?pupilcreate">'.$_PUPILS_CREATE.'</a></span></h5>'.$_PUPILS_OVERVIEW_TEXT.'</p>';
	echo '<table border="0" cellspacing="5" class="tabell">';
	$query = mysql_query("SELECT * FROM customers_youth WHERE school_id = ".$customer_school_id." ORDER BY lastname");
	while ($r = mysql_fetch_array($query)) {
		echo '<tr>';
		echo '<td valign="top" align="left">'.$r['forename'].' '.$r['lastname'].'</td>';
		echo '<td valign="top" align="left"> | <a href="index.php?ordercreate&school_id='.$r['school_id'].'&pupil_id='.$r['personal_id'].'">'.$_PUPILS_SC_ORDER.'</a> | <a href="index.php?pupildelete&pupil_id='.$r['personal_id'].'">'.$_PUPILS_DELETE.'</a></td>';
		echo '</tr>';
	}
	echo '</table>';
}
?>
</body>
</html>
