<?php
session_start(); 
if (!isset($_COOKIE['adminuser'])) {
	header('Location: http://www.emilstradgard.se/eadmin/loginform.php');
	exit;
}
if (isset($_GET['logout'])) {
	setcookie('adminuser','', time() - 3600);
	header('Location: http://www.emilstradgard.se/eadmin/loginform.php');
	exit;
}

if (isset($_GET['pupilsoverview'])) {
	if (isset($_GET['orderconfirmed'])) echo '<h5 style="color:green;">'.$_PUPIL_ORDER_CONFIRMED.'</h5>';
	$order_completed = 0;
	$resultat = mysql_query("SELECT * FROM customers_youth WHERE school_id=".$customer_school_id);
	while ($r = mysql_fetch_array($resultat)) {
		$resultat2 = mysql_query("SELECT * FROM orders WHERE customer_personal_id='".$r['personal_id']."'");	
		$r2 = mysql_fetch_array($resultat2);
		$antal = mysql_num_rows($resultat2);
		if ($antal==0) continue;
		if ($r2['order_status_id'] != 1) {
			$order_completed = 1;
		}
	}
	if ($order_completed == 0) {
		echo '<p><h5>'.$_PUPILS_OVERVIEW.' </p><p><span style="font-size:12px; font-weight:normal;"><a href="index.php?pupilcreate">'.$_PUPILS_CREATE.'</a> | <a href="index.php?school_all_is_done">'.$_SCHOOL_FINNISHED.'</a></span><br/></h5>'.$_PUPILS_OVERVIEW_TEXT.'</p>';
	} else {
		echo '<p><h5>'.$_PUPILS_OVERVIEW.' </h5>'.$_PUPILS_OVERVIEW_TEXT.'</p>';
	}
	echo '<table border="0" cellspacing="5" class="tabell">';
	$query = mysql_query("SELECT * FROM customers_youth WHERE school_id = ".$customer_school_id." ORDER BY lastname");
	while ($r = mysql_fetch_array($query)) {
		echo '<tr>';
		echo '<td valign="top" align="left">'.utf8_encode($r['forename']).' '.utf8_encode($r['lastname']).'</td>';
		echo '<td valign="top" align="left"> | <a href="index.php?ordercreate&school_id='.$r['school_id'].'&pupil_id='.$r['personal_id'].'">'.$_PUPILS_SC_ORDER.'</a></td>'; //| <a href="index.php?pupildelete&pupil_id='.$r['personal_id'].'">'.$_PUPILS_DELETE.'</a>
		echo '</tr>';
	}
	echo '</table>';
}


include('../inloggad/db_settings.php');

//LÄGG TILL ARTIKEL
if (isset($_GET['addarticle'])) {

	unset($fail);
	
	$res = mysql_query("SELECT * FROM products WHERE art_id=".$_POST['art_id']);
	if(mysql_num_rows($res)>=1) { $fail = $fail . "&art_id_exists"; }
	if(strlen($_POST['art_category'])<=0) {$fail = $fail . "&art_category";}
	if(strlen($_POST['art_id'])<=1) {$fail = $fail ."&art_id";}
	if(strlen($_POST['art_name_sv'])<=2) {$fail = $fail . "&art_name_sv";}
	if(strlen($_POST['price_se'])<=0) {$fail = $fail . "&price_se";} 
	
	if (isset($fail)) {
		header("Location: http://www.emilstradgard.se/eadmin/index.php?artiklar&fail" . $fail);
	exit;
	}	
	
	mysql_query("INSERT INTO products (art_category, art_id, alt_art_id, art_name_sv, price_se, in_stock, in_stock_date) VALUES ('".$_POST['art_category']."', '".$_POST['art_id']."', '".$_POST['art_id']."', '".utf8_decode($_POST['art_name_sv'])."', '".$_POST['price_se']."',1,'".date('Y-m-d H:m:s')."')") or exit(mysql_error());
	header("Location: http://www.emilstradgard.se/eadmin/index.php?artiklar&art_added");
}
//UPPDATERA LAGER PÅ ALLA ARTIKLAR
if (isset($_GET['update_add_art'])) {

	if (isset($_GET['set_in_stock'])) {
		if (isset($_GET['art_id'])) {
			mysql_query("UPDATE products SET in_stock=1, in_stock_date='".date('Y-m-d H:m:s')."' WHERE art_id='".$_GET['art_id']."'");
		} else {
			mysql_query("UPDATE products SET in_stock=1, in_stock_date='".date('Y-m-d H:m:s')."'");
		}
	}
	if (isset($_GET['set_out_of_stock'])) {
		if (isset($_GET['art_id'])) {
			mysql_query("UPDATE products SET in_stock=0, in_stock_date='2000-01-01 00:00:00' WHERE art_id='".$_GET['art_id']."'");
		} else {
			mysql_query("UPDATE products SET in_stock=0, in_stock_date='2000-01-01 00:00:00'");
		}
	}
header("Location: http://www.emilstradgard.se/eadmin/index.php?artiklar");
}

//UPPDATERA ALLA ARTIKLAR
if (isset($_GET['update_all_art'])) {
	$i = 0;
	while(isset($_POST['art_id'.$i])) {
		mysql_query("UPDATE products SET art_id=".$_POST['art_id'.$i].", alt_art_id=".$_POST['alt_art_id'.$i].", art_name_sv='".utf8_decode($_POST['art_name_sv'.$i])."', price_se=".$_POST['price_se'.$i]." WHERE art_id=".$_POST['org_art_id'.$i]);
		//echo "UPDATE products SET art_id=".$_POST['art_id'.$i].", alt_art_id=".$_POST['alt_art_id'.$i].", art_name_sv='".$_POST['art_name_sv'.$i]."', price_se=".$_POST['price_se'.$i]." WHERE art_id=".$_POST['org_art_id'.$i];
		//echo "<br/>";
		$i++;
	}  
header("Location: http://www.emilstradgard.se/eadmin/index.php?artiklar");
}
//RADERA ARTIKEL
if (isset($_GET['delete_art'])) {
	mysql_query("DELETE FROM products WHERE art_id='".$_GET['del_art_id']."'");
	header("Location: http://www.emilstradgard.se/eadmin/index.php?artiklar");
}

//LÄGG TILL ELEV
if (isset($_GET['addpupil'])) {
	$pupil_result2 = mysql_query("SELECT * FROM customers_youth WHERE school_id = ".$_GET['addpupilsfor']);
	$pupils2 = mysql_num_rows($pupil_result2);
	$pupilid = $_GET['addpupilsfor'].'-'.($pupils2+1);
	mysql_query("INSERT INTO customers_youth (customer_id, personal_id, school_id, country_id, forename, lastname) VALUES (0, '".$pupilid."', ".$_GET['addpupilsfor'].", '1', '".utf8_decode($_POST['forename'])."', '".utf8_decode($_POST['lastname'])."')");
	mysql_query("INSERT INTO orders (customer_personal_id, order_status_id) VALUES ('".$pupilid."', 1)");
	header("Location: http://www.emilstradgard.se/eadmin/index.php?addschool&addpupilsfor=".$_GET['addpupilsfor']);
}

//UPPDATERA KUNDINFO
if (isset($_GET['updatecustomerinfo'])) {
	mysql_query("SET NAMES 'utf8'") or die(mysql_error());
	mysql_query("SET CHARACTER SET 'utf8'") or die(mysql_error()); 	
	mysql_query("UPDATE customers_youth SET contact_person='".$_POST['contact_person']."', address='".$_POST['address']."', postal='".$_POST['postal']."', city='".$_POST['city']."', phone='".$_POST['phone']."', email='".$_POST['email']."' WHERE personal_id='".$_GET['pid']."'");
	header("Location: http://www.emilstradgard.se/eadmin/index.php?kunder&dosearch&rollback_search=".$_GET['rollback_search']."&cust_updated");
}
//RADERA KUND, SKOLA, ELEV, UNGDOM
if (isset($_GET['delete_cust_pid'])) {

if ($_GET['onlypupil'] == 'yes') {
	$result = mysql_query("SELECT * FROM orders WHERE customer_personal_id = '".$_GET['delete_cust_pid']."'");
	$num_rows = mysql_num_rows($result);
		if($num_rows>=1) {
			header("Location: http://www.emilstradgard.se/eadmin/index.php?kunder&dosearch&rollback_search=".$_GET['rollback_search']."&fail=order_exist&pid=".$_GET['delete_cust_pid']);
		} else {
			$result2 = mysql_query("SELECT * FROM customers_youth WHERE personal_id = '".$_GET['delete_cust_pid']."'");
			$row2 = mysql_fetch_array($result2);
			if($row2['customer_id']>=1) {
				mysql_query("DELETE FROM customers WHERE id=".$row2['customer_id']) or exit(mysql_error());
			}
			mysql_query("DELETE FROM customers_youth WHERE personal_id='".$row2['personal_id']."'") or exit(mysql_error());
			//mysql_query("DELETE FROM customers_youth WHERE personal_id=".$row2['personal_id']) or exit(mysql_error());
			header("Location: http://www.emilstradgard.se/eadmin/index.php?kunder&dosearch&rollback_search=".$_GET['rollback_search']."&cust_deleted&pid=".$_GET['delete_cust_pid']);
		}
} else {
	$result = mysql_query("SELECT * FROM customers_youth WHERE personal_id = '".$_GET['delete_cust_pid']."'");
	$row = mysql_fetch_array($result);

	$result2 = mysql_query("SELECT * FROM customers_youth WHERE school_id = ".$row['school_id']);
	while ($row2 = mysql_fetch_array($result2)) {
		$result3 = mysql_query("SELECT * FROM orders WHERE customer_personal_id = '".$row['personal_id']."'");
		$num_rows = mysql_num_rows($result3);
		if($num_rows>=1) {
			header("Location: http://www.emilstradgard.se/eadmin/index.php?kunder&dosearch&rollback_search=".$_GET['rollback_search']."&fail=order_exist&pid=".$row2['personal_id']);
		} else {
			mysql_query("DELETE FROM customers_youth WHERE personal_id='".$row2['personal_id']."'") or exit(mysql_error());
		}
		
	}
	$result4 = mysql_query("SELECT * FROM customers_school WHERE id = ".$row['school_id']);
	$row4= mysql_fetch_array($result4);	
	mysql_query("DELETE FROM customers WHERE id=".$row4['customer_id']) or exit(mysql_error());
	mysql_query("DELETE FROM customers_school WHERE id=".$row['school_id']) or exit(mysql_error());
	header("Location: http://www.emilstradgard.se/eadmin/index.php?kunder&dosearch&rollback_search=".$_GET['rollback_search']."&school_deleted");
}

}
//RADERA ORDER
if (isset($_GET['delete_order_id'])) {

	mysql_query("DELETE FROM orders WHERE id=".$_GET['delete_order_id']) or exit(mysql_error());
	mysql_query("DELETE FROM orders_content WHERE orders_id=".$_GET['delete_order_id']) or exit(mysql_error());
	if (isset($_GET['rollback_search'])) {
		header("Location: http://www.emilstradgard.se/eadmin/index.php?orders&dosearch&rollback_search=".$_GET['rollback_search']."&id_deleted=".$_GET['delete_order_id']);
	}	else {
		header("Location: http://www.emilstradgard.se/eadmin/index.php?orders&id_deleted=".$_GET['delete_order_id']);
	}
	//header('Location: ' . $_SERVER['HTTP_REFERER'] . '&id_deleted=' . $_GET['delete_order_id']);

}
//RADERA ARTIKEL UR ORDER
if (isset($_GET['delete_article'])) {
mysql_query("DELETE FROM orders_content WHERE orders_id=".$_GET['orderid']." AND product_art_id='".$_GET['product_art_id']."' AND quantity_of_product='".$_GET['quantity_of_product']."' LIMIT 1") or exit(mysql_error());
header("Location: " . $_SERVER['HTTP_REFERER']);
}

//LÄGG TILL UNGDOM OCH ÄNDRA I ORDER
if (isset($_GET['checkedit'])) {
	for ($i=0;$i<40;$i++) {

		$_SESSION['art_id_field_'.$i] = $_POST['art_id_field_'.$i];
		$_SESSION['q_art_id_field_'.$i] = $_POST['q_art_id_field_'.$i];
		
		$art_id = $_POST['art_id_field_'.$i];
		if ($art_id == "") continue;
		$result = mysql_query("SELECT * FROM products WHERE art_id = '".$art_id."'");
		$num_rows = mysql_num_rows($result);
		$_SESSION['error_art_id'.$i] = 0;
		if ($num_rows == "1") {
			if ($_POST['q_art_id_field_'.$i] == "") {				 
				$_SESSION['errormessage'] .= '<h6 style="color:#900; padding-left:20px;">Fel artikelnummer eller antal angivet vid markering</h6>';
				$problem = 1;
				$_SESSION['error_art_id'.$i] = 1;
			}
		} else {
			$problem = 1;
			$_SESSION['errormessage'] .= '<h6 style="color:#900; padding-left:20px;">Fel artikelnummer eller antal angivet vid markering</h6>';
			$_SESSION['error_art_id'.$i] = 1;
		}
	}
	if ($problem == 1) {
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
	}
	
	mysql_query("SET NAMES 'utf8'") or die(mysql_error());
	mysql_query("SET CHARACTER SET 'utf8'") or die(mysql_error()); 	
	mysql_query("UPDATE orders SET order_status_id = ".$_POST['order_status']." WHERE id=".$_GET['editid']) or exit(mysql_error());
	
	for ($i=0;$i<40;$i++) {
		$art_id = $_POST['art_id_field_'.$i];
		$q_art_id = $_POST['q_art_id_field_'.$i];
		if ($art_id == "") continue;
		mysql_query("INSERT INTO orders_content (orders_id, product_art_id, quantity_of_product) VALUES (".$_GET['editid'].",'".$art_id."',".$q_art_id.")") or exit(mysql_error());
		unset($_SESSION['art_id_field_'.$i]);
		unset($_SESSION['q_art_id_field_'.$i]);
	 }	
	 header("Location: http://www.emilstradgard.se/eadmin/index.php?orders&dosearch&order_updated&rollback_search=".$_GET['rollback_search']);
	 exit;

}
// LÄGG TILL SKOLA - KONTROLL
if (isset($_GET['checkschool'])) {

	$fail="";
	unset($fail);
	if(strlen($_POST['school'])<3) {$fail = $fail . "&skolafail";}
	if(strlen($_POST['klass'])<=1) {$fail = $fail ."&klassfail";}
	if(strlen($_POST['contactperson'])<=3) {$fail = $fail . "&kontaktfail";}
	if(strlen($_POST['address'])<=3) {$fail = $fail . "&adressfail";}
	if(strlen($_POST['postal'])<>5) {$fail = $fail . "&postnrfail";}
	if(strlen($_POST['city'])<=2) {$fail = $fail . "&ortfail";}

	
	if (isset($fail)) {
		header("Location: http://www.emilstradgard.se/eadmin/index.php?addschool&fail" . $fail);
		exit;
	}
	mysql_query("SET NAMES 'utf8'") or die(mysql_error());
	mysql_query("SET CHARACTER SET 'utf8'") or die(mysql_error()); 
	mysql_query("INSERT INTO customers_school (school_name, class_name, address, postal, city, country_id, contactperson, phone, shipping_to) VALUES ('".$_POST['school']."', '".$_POST['klass']."', '".$_POST['address']."', '".$_POST['postal']."', '".$_POST['city']."', ".$_POST['country'].", '".$_POST['contactperson']."', '".$_POST['phone']."', '".$_POST['shipto']."')");

	$res = mysql_query("SELECT * FROM customers_school WHERE class_name = '".$_POST['klass']."' AND school_name = '".$_POST['school']."' ORDER BY created_date DESC LIMIT 1");
	$rows= mysql_fetch_array($res);
	$latestSchoolId = $rows['id'];
	
		header('Location: http://www.emilstradgard.se/eadmin/index.php?addschool&addpupilsfor='.$latestSchoolId);
}

//LÄGG TILL UNGDOM - KONTROLL
if (isset($_GET['checkadd'])) {
	
	setcookie("personalid", $_POST['personalid'], time() + 3600);
	setcookie("adult", $_POST['adult'], time() + 3600);
	setcookie("forename", $_POST['forename'], time() + 3600);
	setcookie("lastname", $_POST['lastname'], time() + 3600);
	setcookie("address", $_POST['address'], time() + 3600);
	setcookie("postal", $_POST['postal'], time() + 3600);
	setcookie("city", $_POST['city'], time() + 3600);
	setcookie("c_code", $_POST['c_code'], time() + 3600);
	setcookie("phone", $_POST['phone'], time() + 3600);
	
	$fail="";
	unset($fail);
	if(strlen($_POST['personalid'])<>12) {$fail = $fail . "&idfail";}
	if(strlen($_POST['adult'])<=2) {$fail = $fail ."&adultfail";}
	if(strlen($_POST['forename'])<=2) {$fail = $fail . "&fnfail";}
	if(strlen($_POST['lastname'])<=2) {$fail = $fail . "&lnfail";}
	if(strlen($_POST['address'])<=3) {$fail = $fail . "&addfail";}
	if(strlen($_POST['postal'])<>5) {$fail = $fail . "&postfail";}
	if(strlen($_POST['city'])<=2) {$fail = $fail . "&cityfail";}
	if(strlen($_POST['phone'])<=7) {$fail = $fail . "&phonefail";}
	
	if (isset($fail)) {
		header("Location: http://www.emilstradgard.se/eadmin/index.php?addorder&fail" . $fail);
		exit;
	}
		
	mysql_query("SET NAMES 'utf8'") or die(mysql_error());
	mysql_query("SET CHARACTER SET 'utf8'") or die(mysql_error()); 
	mysql_query("INSERT INTO customers_youth (personal_id, contact_person, forename, lastname, address, postal, city, country_id, phone) VALUES ('".$_POST['personalid']."', '".$_POST['adult']."', '".$_POST['forename']."', '".$_POST['lastname']."', '".$_POST['address']."', '".$_POST['postal']."', '".$_POST['city']."', ".$_POST['country'].", '".$_POST['phone']."')");
	
	mysql_query("INSERT INTO orders (customer_personal_id, order_status_id, order_last_change_date) VALUES ('".$_POST['personalid']."','2', '".date('Y-m-d H:i:s', time())."')");
	$latestOrderByCustomer = mysql_query("SELECT * FROM orders WHERE customer_personal_id ='".$_POST['personalid']."' ORDER BY order_created_date DESC LIMIT 1");
	$latestOrder = mysql_fetch_array($latestOrderByCustomer);
	$latestOrderId = $latestOrder['id'];
	
	 $_SESSION['errormessage'] = '<span style="padding-left:20px; color:green; font-size:14px">Kunden skapades!</span>';
	 setcookie("personalid", $_POST['personalid'], time() - 3600);
	 setcookie("adult", $_POST['adult'], time() - 3600);
	 setcookie("forename", $_POST['forename'], time() - 3600);
	 setcookie("lastname", $_POST['lastname'], time() - 3600);
	 setcookie("address", $_POST['address'], time() - 3600);
	 setcookie("postal", $_POST['postal'], time() - 3600);
	 setcookie("city", $_POST['city'], time() - 3600);
	 setcookie("c_code", $_POST['c_code'], time() - 3600);
	 setcookie("phone", $_POST['phone'], time() - 3600);
	 header('Location: http://www.emilstradgard.se/eadmin/index.php?editorder&editid='.$latestOrderId);
	 exit;

}

?>
<html>
<title>Emils Trädgård Admin</title>
<head>
<link rel="stylesheet" href="../css/styles.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta charset="utf-8">
<script language="JavaScrip">
<!--
function pop_up(url_add)
   {
   window.open(url_add,'Visning av order',
   'width=600,height=800,menubar=no,status=no,
   location=no,toolbar=no,scrollbars=yes');
   }
-->
</script>
<script type="text/javascript" language="JavaScript">
<!--
function HideContent(d) {
document.getElementById(d).style.display = "none";
}
function ShowContent(d) {
document.getElementById(d).style.display = "block";
}
//-->

</script>

</head>
<body height="100%" style="background:none;">
<?php
if (isset($_GET['showorder'])) {
} else {
?>
<h2 class="grid_12 caption clearfix">Emils Trädgård Administration <span style="font-size:12px;"><br/> - FUNGERAR BÄST MED CHROME OCH FIREFOX.</span></h2>
		
		<div class="hr dotted clearfix" style="width:1040px;">&nbsp;</div>
		
<?php
			$result = mysql_query("SELECT * FROM eadmin WHERE username = '". $_COOKIE['adminuser']."'") or exit (mysql_error());
			$row = mysql_fetch_array($result);
			
			//echo '<p>Välkommen, '.$row['name'].'</p>';
?>

<div class="grid_12 clearfix">
<input type="button" name="menuObject1" class="greenbutton" value="ORDERHANTERING" onClick="parent.location='?orders'">
<input type="button" name="menuObject1" class="greenbutton" value="KUNDHANTERING" onClick="parent.location='?kunder'">
<input type="button" name="menuObject1" class="greenbutton" value="ARTIKELHANTERING" onClick="parent.location='?artiklar'">
<input type="button" name="menuObject2" class="greenbutton" value="STATISTIK" onClick="parent.location='?statistics'">
<input type="button" name="menuObject3" class="greenbutton" value="LÄGG TILL UNGDOM" onClick="parent.location='?addorder'">
<input type="button" name="menuObject4" class="greenbutton" value="LÄGG TILL SKOLA" onClick="parent.location='?addschool'">
<input type="button" name="menuObject5" class="greenbutton" value="LOGGA UT" onClick="parent.location='?logout'">
<br/><br/>
</div>
<div class="hr dotted clearfix" style="width:1040px;">&nbsp;</div>
<?php
}

if (isset($_GET['statistics'])) {
 	echo '<div class="clearfix">&nbsp;</div><h2 style="padding-left:20px;">Statistik<span style="font-size:12px;">, totalt antal per artikel.<br/>Sortering : <a href="?statistics&sort=artid">Numerisk ordning</a> | <a href="?statistics&sort=sum">Mest sålda</a> | <a href="?statistics&sort=nya">Beställda, ej utskrivna</a></span></h2><div class="clearfix">&nbsp;</div><div class="grid_8">';
	if ($_GET['sort'] == "artid") {
		$result2 = mysql_query("SELECT product_art_id, SUM(quantity_of_product) AS summa FROM orders_content GROUP BY product_art_id ORDER BY product_art_id");
		echo '<table border=0 class="tabell"><tr><td>St.</td><td>Kat.</td><td>Art.nr</td><td>Art.namn</td></tr>';
		while ($row2 = mysql_fetch_array($result2)) {
			$result = mysql_query("SELECT * FROM products WHERE art_id = '".$row2['product_art_id']."'") or exit (mysql_error());
			$row = mysql_fetch_array($result);
			echo '<tr><td>'.$row2['summa'].'</td><td>'.$row['art_category'].'</td><td>'.$row['art_id'].'</td><td>'.utf8_encode($row['art_name_sv']).'</td></tr>';
		}
	} elseif ($_GET['sort'] == "nya") {
		echo '<table border=0 class="tabell"><tr><td>St.</td><td>Kat.</td><td>Art.nr</td><td>Art.namn</td></tr>';	
		$res2 = mysql_query("SELECT	products.art_category, products.art_id, products.art_name_sv, SUM(orders_content.quantity_of_product) AS summa FROM products, orders_content, orders WHERE products.art_id = orders_content.product_art_id AND orders_content.orders_id = orders.id AND orders.order_status_id = 2 GROUP BY products.art_id") or exit (mysql_error());
		while ($r2 = mysql_fetch_array($res2)) {
			echo '<tr><td>'.$r2['summa'].'</td><td>'.$r2['art_category'].'</td><td>'.$r2['art_id'].'</td><td>'.utf8_encode($r2['art_name_sv']).'</td></tr>';
		}
	} else {
		$result2 = mysql_query("SELECT product_art_id, SUM(quantity_of_product) AS summa FROM orders_content GROUP BY product_art_id ORDER BY summa DESC");
	echo '<table border=0 class="tabell"><tr><td>St.</td><td>Kat.</td><td>Art.nr</td><td>Art.namn</td></tr>';

	while ($row2 = mysql_fetch_array($result2)) {
		$result = mysql_query("SELECT * FROM products WHERE art_id = '".$row2['product_art_id']."'") or exit (mysql_error());
		$row = mysql_fetch_array($result);
		echo '<tr><td>'.$row2['summa'].'</td><td>'.$row['art_category'].'</td><td>'.$row['art_id'].'</td><td>'.utf8_encode($row['art_name_sv']).'</td></tr>';
	}
	}
	echo '</table>';
	echo '</div>';
}

if (isset($_GET['editorder'])) {
echo $_SESSION['errormessage']; 
$_SESSION['errormessage'] = "";
echo '<div class="clearfix">&nbsp;</div><h4 style="padding-left:20px;">Ta bort artiklar</h4>';

?>
<div class="grid_12 clearfix">
<form method="post" action="index.php?checkedit&rollback_search=<?php echo $_GET['rollback_search'];?>&editid=<?php echo $_GET['editid'];?>">
<?php
		$result = mysql_query("SELECT * FROM orders_content WHERE orders_id=".$_GET['editid']." ORDER BY product_art_id");
		while ($row = mysql_fetch_array($result)) {
			echo '<span style="font-size:11px;"> Artikel-ID: '.$row['product_art_id'].' Antal: '.$row['quantity_of_product'].'st. - <a href="?delete_article&orderid='.$_GET['editid'].'&product_art_id='.$row['product_art_id'].'&quantity_of_product='.$row['quantity_of_product'].'">Radera</a></span><br/>';
		}
?>
		

<?php
echo '<div class="clearfix">&nbsp;</div><h4>Lägg till fler artiklar</h4>';
?>
<table border="0" width="750" class="tabell">
<tr><td valign="top" align="left" style="font-size:11px;"><?php echo "Artikelnr/Antal"; ?></td><td valign="top" align="left" style="font-size:11px;"><?php echo "Artikelnr/Antal"; ?></td><td valign="top" align="left" style="font-size:11px;"><?php echo "Artikelnr/Antal"; ?></td><td valign="top" align="left" style="font-size:11px;"><?php echo "Artikelnr/Antal"; ?></td><td valign="top" align="left" style="font-size:11px;"><?php echo "Artikelnr/Antal"; ?></td>

<?php
for ($i=0;$i<40;$i++) {
	//if ($i == 5 || $i == 10 || $i == 15 || $i == 20 || $i == 25 || $i == 30 || $i == 35 || $i == 40 || $i == 45) echo '</tr><tr>';
	if ($i%5==0) echo '</tr><tr>';
	if ($_SESSION['error_art_id'.$i] == 1) {
		$art_id_style = "style='color:red;'";
	} else {
		$art_id_style = "";
	}	
	echo '<td valign="top" align="left"><input size="5" type="text" id="art_id_field_'.$i.'" name="art_id_field_'.$i.'" value="'.$_SESSION['art_id_field_'.$i].'" '.$art_id_style.'> <input size="5" type="text" id="q_art_id_field_'.($i).'" name="q_art_id_field_'.($i).'" value="'.$_SESSION['q_art_id_field_'.$i].'" onkeypress="return isNumberKey(event)"></td>';

}
$_SESSION['error_art_id']="1000";

$result = mysql_query("SELECT * FROM orders WHERE id=".$_GET['editid']."") or exit (mysql_error());
$row = mysql_fetch_array($result);
?>
</table>
<?php
echo '<h4>Ändra orderstatus</h2>';
?>
Sätt orderstatus (1 = ej inskickad, 2 = inskickad, 3 = fröer utskrivna, 5 = färdig order)<br/> <input type="text" name="order_status" value="<?php echo $row['order_status_id'];?>"><br/><br/>
<input type="submit" class="greenOKbutton" value="UPPDATERA ORDERN"> <a href="?orders&dosearch&rollback_search=<?php echo $_GET['rollback_search']; ?>" class="greenOKbutton" style="text-decoration:none;">AVBRYT</a>
</div>
<?php
}
if (isset($_GET['addschool'])) {
if (isset($_GET['addpupilsfor'])) {

	echo '<div class="clearfix">&nbsp;</div><h2 style="padding-left:20px;">Lägg till elever i klassen<br/><span style="font-size:12px;">Lägg till elever i klassen, ange både för- och efternamn!</span></h2>';
	
	$res = mysql_query("SELECT * FROM customers_school WHERE id = ".$_GET['addpupilsfor']);
	$rows= mysql_fetch_array($res);
	
	echo '<h4 style="padding-left:20px;">'.utf8_encode($rows['school_name']).', '.$rows['class_name'].' - <small>Skolans id:'.$rows['id'].'</small></b><br/>';
	
	$res2 = mysql_query("SELECT * FROM customers_youth WHERE school_id = ".$_GET['addpupilsfor']." ORDER BY created_date DESC");
	while ($rows2= mysql_fetch_array($res2)) {
		echo '<span style="font-size:11px; font-weight:normal;">'.utf8_encode($rows2['forename']).' '.utf8_encode($rows2['lastname']).' - order id: ';
		$res3 = mysql_query("SELECT * FROM orders WHERE customer_personal_id = '".$rows2['personal_id']."' ORDER BY id ASC");
		$i=0;
		while ($rows3= mysql_fetch_array($res3)) {
		if($i>=1) echo ', ';
			echo $rows3['id'];
			$i++;
		}
		echo '</span><br/>';
	}
	
?>
	<form method="post" name="pupilform" id="pupilform" action="?addpupil&addpupilsfor=<?php echo $_GET['addpupilsfor'];?>">
	<input type="hidden" name="school_id" value="<?php echo $_GET['addpupilsfor']; ?>">
	<p style="font-size:11px;">Förnamn<br/>
	<input type="text" name="forename"></p>
	<p style="font-size:11px;">Efternamn<br/>
	<input type="text" name="lastname"></p>
	
	<p><input type="submit" class="greenOKbutton" value="Skapa elev"></p>
	</form>
<?php
	} else {

echo '<div class="clearfix">&nbsp;</div><h2 style="padding-left:20px;">Lägg till skola & klass<br/><span style="font-size:12px;">Fält markerade med * är obligatoriska!</span></h2>';

if (isset($_GET['fail'])){
		echo '<div style="width:978px; margin-bottom:2px; padding:10px; padding-bottom:5px; border:1px dotted #888; text-align:center; background-color:#e4685d;" id="fail">';
		echo '<span align="center" style="font-size:14px;"><b>Skolan skapades inte!</b></span><br/>Alla fält markerade med * är obligatoriska, följande fält är tomma eller felaktiga:<br/><br/>';
		if (isset($_GET['skolafail'])) echo "<b>Skolnamn måste anges.</b><br/>";
		if (isset($_GET['klassfail'])) echo "<b>Klass måste anges.</b><br/>";
		if (isset($_GET['kontaktfail'])) echo "<b>Namn på ansvarig vuxen måste anges.</b><br/>";
		if (isset($_GET['adressfail'])) echo "<b>Gatuadress måste anges.</b><br/>";
		if (isset($_GET['postnrfail'])) echo "<b>Postnummer skall anges med 5 siffror utan blanksteg.</b><br/>";
		if (isset($_GET['ortfail'])) echo "<b>Ort måste anges</b><br/>";

		echo '</div>';
		}
		
		
?>

<div class="grid_12 clearfix" style="font-size:11px; font-weight:normal;">
<form method="post" action="index.php?checkschool">
<table border=0>
<tr><td align="left"><span style="font-size:11px; font-weight:normal;">Skola * <input type="text" name="school" value="<?php echo $_SESSION['school'];?>"> Klass * <input type="text" name="klass" value="<?php echo $_SESSION['klass'];?>" size="5"> Kontaktperson * <input type="text" name="contactperson"  value="<?php echo $_SESSION['contactperson'];?>"></td></tr>
<tr><td align="left"><span style="font-size:11px; font-weight:normal;">Mottagare (<i>anges om leveransadress inte är till skolan</i>) <input type="text" name="shipto" value="<?php echo $_SESSION['shipto'];?>"> Telefonnummer <input type="text" onkeyup="this.value=this.value.replace(/[^\d]/,'')" name="phone" value="<?php echo $_SESSION['phone'];?>"></td></tr>
<tr><td align="left"><span style="font-size:11px; font-weight:normal;">Gatuadress * <input type="text" name="address" size="35" value="<?php echo $_SESSION['address'];?>"> Postnummer * <input type="text" onkeyup="this.value=this.value.replace(/[^\d]/,'')" maxlength="5" name="postal" size="6" value="<?php echo $_SESSION['postal'];?>"> Ort * <input type="text" name="city" size="20"  value="<?php echo $_SESSION['city'];?>"></td></tr>
<input type="hidden" name="country" value="1">
</table>
<input type="submit" class="greenOKbutton" value="Spara skola"></p>
</div>

<?php
}
}
if (isset($_GET['addorder'])) {

echo '<div class="clearfix">&nbsp;</div><h2 style="padding-left:20px;">Lägg till ungdom och beställning</h2>';
 
if (isset($_GET['fail'])){
		echo '<div style="width:978px; margin-bottom:2px; padding:10px; padding-bottom:5px; border:1px dotted #888; text-align:center; background-color:#e4685d;" id="fail">';
		echo '<span align="center" style="font-size:14px;"><b>Kunden skapades inte!</b></span><br/>Alla fälten är obligatoriska för att skapa en kund, följande fält är tomma eller felaktiga:<br/><br/>';
		if (isset($_GET['idfail'])) echo "<b>Personnummer skall anges med 12 siffror, t.ex. 199001011234.</b><br/>";
		if (isset($_GET['adultfail'])) echo "<b>Namn på ansvarig vuxen måste anges.</b><br/>";
		if (isset($_GET['fnfail'])) echo "<b>Förnamn måste anges.</b><br/>";
		if (isset($_GET['lnfail'])) echo "<b>Efternamn måste anges.</b><br/>";
		if (isset($_GET['addfail'])) echo "<b>Gatuadress måste anges.</b><br/>";
		if (isset($_GET['postfail'])) echo "<b>Postnummer skall anges med 5 siffror utan blanksteg.</b><br/>";
		if (isset($_GET['cityfail'])) echo "<b>Ort måste anges</b><br/>";
		if (isset($_GET['phonefail'])) echo "<b>Telefonnummer skall anges med riktnummer utan bindestreck eller blanksteg.</b><br/>";
		echo '</div>';
		}
?>
<div class="grid_12 clearfix">
<form method="post" action="index.php?checkadd">
<table border=0>
<tr><td align="left" width=150><span style="font-size:11px; font-weight:normal;">Personnummer:  </td><td algin="left" colspan="5"><input type="text" onkeyup="this.value=this.value.replace(/[^\d]/,'')" name="personalid" maxlength="12" value="<?php echo $_COOKIE['personalid'];?>"></td></tr>
<tr><td align="left" width=150><span style="font-size:11px; font-weight:normal;">Förnamn:       </td><td align="left"><input type="text" name="forename" value="<?php echo $_COOKIE['forename'];?>"></td><td align="left"><span style="font-size:11px; font-weight:normal;">Efternamn:</td><td align="left"><input type="text" name="lastname" value="<?php echo $_COOKIE['lastname'];?>"></td><td align="left"><span style="font-size:11px; font-weight:normal;">Vuxen:</td><td align="left"><input type="text" name="adult"  value="<?php echo $_COOKIE['adult'];?>"></td></tr>
<tr><td align="left" width=150><span style="font-size:11px; font-weight:normal;">Telefonnummer: </td><td algin="left" colspan="5"><input type="text" name="phone" value="<?php echo $_COOKIE['phone'];?>"></td></tr>
<tr><td align="left" width=150><span style="font-size:11px; font-weight:normal;">Gatuadress:    </td><td align="left"><input type="text" name="address"  value="<?php echo $_COOKIE['address'];?>"></td><td align="left"><span style="font-size:11px; font-weight:normal;">Postnr:</td><td align="left"><input type="text" onkeyup="this.value=this.value.replace(/[^\d]/,'')" name="postal" maxlength="5" size="6" value="<?php echo $_COOKIE['postal'];?>"></td><td align="left"><span style="font-size:11px; font-weight:normal;">Ort:</td><td align="left"><input type="text" name="city" size="20"  value="<?php echo $_COOKIE['city'];?>"></td></tr>
<input type="hidden" name="country" value="1">
</table>

<!--
<table border="0" width="750" class="tabell">
<tr><td valign="top" align="left"><?php echo "Artikelnr/Antal"; ?></td><td valign="top" align="left"><?php echo "Artikelnr/Antal"; ?></td><td valign="top" align="left"><?php echo "Artikelnr/Antal"; ?></td><td valign="top" align="left"><?php echo "Artikelnr/Antal"; ?></td><td valign="top" align="left"><?php echo "Artikelnr/Antal"; ?></td>

<?php
for ($i=0;$i<120;$i++) {
	//if ($i == 5 || $i == 10 || $i == 15 || $i == 20 || $i == 25 || $i == 30 || $i == 35 || $i == 40 || $i == 45) echo '</tr><tr>';
	if ($i%5==0) echo '</tr><tr>';
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
-->
<input type="submit" class="greenOKbutton" value="SPARA KUND"> 
</div>
<?php
}

if (isset($_GET['showorder'])) {
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf8" />';
	if (isset($_GET['status'])) {
		mysql_query("UPDATE orders SET order_status_id=".$_GET['status']." WHERE id=".$_GET['id']) or exit (mysql_error());
		
		?>
<script language="javascript">
opener.location=('index.php?orders');
</script>
<?php
		
	}
	if (isset($_GET['inc_overview'])) {
		$res0 = mysql_query("SELECT * FROM orders WHERE id = ".$_GET['id']) or exit (mysql_error());
		$r0 = mysql_fetch_array($res0);
		$res1 = mysql_query("SELECT school_id FROM customers_youth WHERE personal_id = '".$r0['customer_personal_id']."'") or exit (mysql_error());
		$r1 = mysql_fetch_array($res1);
		$res11 = mysql_query("SELECT * FROM customers_school WHERE id = ".$r1['school_id']) or exit (mysql_error());
		$r11 = mysql_fetch_array($res11);
		echo '<span class="grid_12 clearfix" style="font-size:18px;">'.utf8_encode($r11['school_name']).' - Översikt<br/><span style="font-size:14px;">';
		$res2 = mysql_query("SELECT * FROM customers_youth WHERE school_id = ".$r1['school_id']." ORDER BY forename") or exit (mysql_error());
		echo '<p>&nbsp;</p><table cellpadding=0 cellspacing=0 class="tabell2" width="600" border=0><tr><td><b>Elev</td><td><b>Faktureras</td><td><b>Förtjänst</td><td><b>Summa</td></tr>';
		$salestotalschool = 0;
		while ($r2 = mysql_fetch_array($res2)) {
		$i++;
		if ($i%2==0) {
			$bgColor = 'style="padding-bottom:5px; padding-top:5px; background-color:#FFF;"';
		} else {
			$bgColor = 'style=" padding-bottom:5px; padding-top:5px; background-color:#EEE;"';
		}
			$res3 = mysql_query("SELECT * FROM orders WHERE customer_personal_id = '".$r2['personal_id']."'") or exit (mysql_error());
			$r3 = mysql_fetch_array($res3);
			$salestotalpupil = 0;
			$numofproducts = 0;			
			$res4 = mysql_query("SELECT orders_id, product_art_id, SUM(quantity_of_product) FROM orders_content WHERE orders_id= ".$r3['id']." GROUP BY product_art_id ORDER BY product_art_id");
			while ($r4 = mysql_fetch_array($res4)) {
			$res5 = mysql_query("SELECT * FROM products WHERE art_id = '".$r4['product_art_id']."'");
			$r5 = mysql_fetch_array($res5);
			if ($r2['country_id'] == 1) {
				$salestotalpupil = $salestotalpupil + ($r4['SUM(quantity_of_product)'] * $r5['price_se']);
				$salestotalschool = $salestotalschool + ($r4['SUM(quantity_of_product)'] * $r5['price_se']);
				$numofproducts = $numofproducts + $r4['SUM(quantity_of_product)'];
				
			} else {
				$salestotalpupil = $salestotalpupil + ($r4['SUM(quantity_of_product)'] * $r5['price_fi']);
				$salestotalschool = $salestotalschool + ($r4['SUM(quantity_of_product)'] * $r5['price_fi']);
				$numofproducts = $numofproducts + $r4['SUM(quantity_of_product)'];
			}
			}
		echo '<tr><td '.$bgColor.'>'.utf8_encode(ucfirst($r2['forename'])).' '.utf8_encode(ucfirst($r2['lastname'])).'</td><td '.$bgColor.'>'.number_format(($salestotalpupil * 0.7),2).'</td><td '.$bgColor.'>'.number_format(($salestotalpupil * 0.3),2).'</td><td '.$bgColor.'>'.number_format($salestotalpupil,2).'</td></tr>'; 
			
			
		}
	echo '<tr><td colspan="4" height="25"><b>Totalt belopp som kommer att faktureras: '.number_format(($salestotalschool * 0.7),2).'</td></tr>'; 
	echo '</table>';
	echo '</span></span><p class="clearfix">&nbsp;</p>';
	exit;
	
	}
	
	$result = mysql_query("SELECT * FROM orders WHERE id = ".$_GET['id']) or exit (mysql_error());
	$row = mysql_fetch_array($result);
		$salestotal_EUR = 0;
		$salestotal_SEK = 0;		
		$f_available = 0;
		$lt_available = 0;
	$result2 = mysql_query("SELECT * FROM customers_youth WHERE personal_id = '".$row['customer_personal_id']."'") or exit (mysql_error());
	$row2 = mysql_fetch_array($result2);
	if ($row2['school_id'] == 0) {
		echo '<span class="grid_12 clearfix" style="font-size:18px;"><b>ID #'.$_GET['id'].'</b><br/><span style="font-size:14px;">Personnummer: '.$row['customer_personal_id'].' | Telefon: '.$row2['phone'].'</b></span><br/><br/>';
		echo '<span style="font-size:14px;"><b>'.utf8_encode(ucfirst($row2['forename'])).' '.utf8_encode(ucfirst($row2['lastname'])); 
		echo ' <br/> '.utf8_encode(ucfirst($row2['contact_person'])).'</b><br/>'.utf8_encode(ucfirst($row2['address'])).'<br/>'.utf8_encode($row2['postal']).' '.utf8_encode(ucfirst($row2['city']));
	} else {
		$result22 = mysql_query("SELECT * FROM customers_school WHERE id = ".$row2['school_id']) or exit (mysql_error());
		$row22 = mysql_fetch_array($result22);
		$school_id_loop = $row2['school_id'];		
		echo '<span class="grid_12 clearfix" style="font-size:18px;"><b>ID #'.$_GET['id'].'</b><br/><span style="font-size:14px;">KundID: '.$row['customer_personal_id'].' | Telefon: '.$row22['phone'].'</b></span><br/>';
		echo '<span style="font-size:14px;">Elev: '.utf8_encode(ucfirst($row2['forename'])).' '.utf8_encode(ucfirst($row2['lastname'])); 
		echo '<br/><br/><span style="font-size:14px;"><b>'.utf8_encode(ucfirst($row22['school_name'])).'<br/> '.utf8_encode(ucfirst($row22['shipping_to'])); 
		echo ' <br/>att: '.utf8_encode(ucfirst($row22['contactperson'])).'</b><br/>'.utf8_encode(ucfirst($row22['address'])).'<br/>'.utf8_encode($row22['postal']).' '.utf8_encode(ucfirst($row22['city']));
	}		
	if ($row2['country_id'] == 1) { 
		echo '<br/>Sverige';
	} else {
		echo '<br/>Finland';
	}
	echo '</span><br/><br/>';
	
	$result3 = mysql_query("SELECT orders_id, product_art_id, SUM(quantity_of_product) FROM orders_content WHERE orders_id= ".$_GET['id']." GROUP BY product_art_id ORDER BY product_art_id");
	echo '<table border=0 cellpadding=0 cellspacing=0 class="tabell2" width="600">';
	$counted = 0;
	$i = 0;
	while ($row3 = mysql_fetch_array($result3)) {
		$i++;
		if ($i%2==0) {
			$bgColor = 'style="padding-left:10px; padding-bottom:2px; padding-top:2px; background-color:#FFF;"';
		} else {
			$bgColor = 'style="padding-left:10px; padding-bottom:2px; padding-top:2px; background-color:#EEE;"';
		}
		$result4 = mysql_query("SELECT * FROM products WHERE art_id = '".$row3['product_art_id']."'");
		$r4 = mysql_fetch_array($result4);
		if ($_GET['cats']=="L") {
			if ($r4['art_category'] == "F" || $r4['art_category'] == "T") continue;
		} elseif ($_GET['cats'] == "FT") {
			if ($r4['art_category'] == "HL" || $r4['art_category'] == "VL") continue;
		}  
		$counted = $counted + $row3['SUM(quantity_of_product)'];
		echo '<tr><td '.$bgColor.'>'.$r4['art_category'].'</td><td align="center" '.$bgColor.'>'.$row3['SUM(quantity_of_product)'].'</td>';
		if ($r4['alt_art_id']!=$r4['art_id']) { 
			echo '<td '.$bgColor.'><b><i>'.$r4['alt_art_id'].'</b></i> | ersätter '.$row3['product_art_id'].'</td>';
		} else {
			echo '<td '.$bgColor.'>'.$row3['product_art_id'].'</td>';
		}
		
		if ($row2['country_id'] == 1) {
			echo '<td '.$bgColor.'>'.utf8_encode($r4['art_name_sv']).'</td>';
			echo '<td width="100" '.$bgColor.'>á '.number_format($r4['price_se'],2).'</td>';
			$salestotal_SEK = $salestotal_SEK + ($row3['SUM(quantity_of_product)'] * $r4['price_se']);
			echo '<td '.$bgColor.'>'.number_format(($row3['SUM(quantity_of_product)'] * $r4['price_se']),2).'</td></tr>';
		} else {
			echo '<td '.$bgColor.'>'.utf8_encode($r4['art_name_fi']).'</td>';
			echo '<td width="100" '.$bgColor.'>á '.number_format($r4['price_fi'],2).'</td>';
			$salestotal_SEK = $salestotal_SEK + ($row3['SUM(quantity_of_product)'] * $r4['price_fi']);
			echo '<td '.$bgColor.'>'.number_format(($row3['SUM(quantity_of_product)'] * $r4['price_fi']),2).'</td></tr>';
		}
	}	
	echo '<tr><td colspan="6" height="2" style="background-color:#000;"></td></tr>';
	if ($row2['country_id'] == 1) {
		echo '<tr><td></td><td align="center"><b>'.$counted.'</td><td></td><td></td><td></td><td><b>'.number_format($salestotal_SEK,2).' kr</td></tr>';
	} else {
		echo '<tr><td></td><td align="center"><b>'.$counted.'</td><td></td><td></td><td></td><td><b>€ '.number_format($salestotal_SEK,2).'</td></tr>';
	}

	echo '</table>';
	if ($row2['country_id'] == 1) {
		echo '<span style="font-size:12px;"><b>Försäljningsförtjänst: '.number_format(($salestotal_SEK * 0.3),0).' kr</span><br/>';
		echo '<span style="font-size:12px;"><b>Belopp som kommer att faktureras: '.number_format(($salestotal_SEK * 0.7),0).' kr</span><br/>';
	} else {
		echo '<span style="font-size:12px;"><b>Försäljningsförtjänst: € '.number_format(($salestotal_SEK * 0.3),2).'</span><br/>';
		echo '<span style="font-size:12px;"><b>Belopp som kommer att faktureras: € '.number_format(($salestotal_SEK * 0.7),2).'</span><br/>';
	}	
	
	echo '<br/>[ <a href="javascript:print();" style="font-size:12px; color:black;">Skriv ut</a> ] ';

}

//ARTIKELFUNKTION

if (isset($_GET['artiklar'])) {

	echo '<div class="clearfix">&nbsp;</div><h2 style="padding-left:20px;">Artikelhantering</h2>';
if (isset($_GET['art_added'])) {
		echo '<div style="width:700px; padding-top:10px; margin-left:20px; margin-bottom:2px; padding-bottom:10px; border:1px dotted #888; text-align:center; background-color:#a5cc52;" id="art_added">';
		echo '<span align="center"><b>Artikeln skapades utan problem!</b></span>';
		echo '</div>';
}
if (isset($_GET['fail'])) {
		echo '<div style="width:700px; padding-top:10px; margin-left:20px; margin-bottom:2px; padding-bottom:10px; border:1px dotted #888; text-align:center; background-color:#e4685d;" id="art_add_fail">';
		if(isset($_GET['art_id_exists'])) {
		echo '<span align="center"><b>Det finns redan en artikel med detta artikelnummer!</b></span>';
		} else {
		echo '<span align="center"><b>Alla fält måste fyllas i för att artikeln skall kunna skapas!</b></span>';
		}
		echo '</div>';
}		
	echo '<span style="font-size:14px; padding-left:20px; font-weight:normal;">Lägg till ny artikel</span>';
	echo '<form method="post" action="?addarticle">';
	echo '<table width="700" style="padding-left:16px;" border="0" cellspacing="5">';
	echo '<tr><td valign="top" align="left" style="font-size:11px; font-weight:bold;">Kategori</td><td valign="top" align="left" style="font-size:11px; font-weight:bold;">Artikelnummer</td><td valign="top" align="left" style="font-size:11px; font-weight:bold;">Namn</td><td valign="top" align="left" style="font-size:11px; font-weight:bold;">Pris</td></tr>';
	echo '<tr><td valign="top" align="left" style="font-size:11px;"><input type="text" style="font-size:10px; width:80px;" name="art_category"></td><td valign="top" align="left" style="font-size:11px;"><input type="text" style="font-size:10px; width:80px;" name="art_id"></td><td valign="top" align="left" style="font-size:11px;"><input type="text" style="font-size:10px; width:300px;" name="art_name_sv"></td><td valign="top" align="left" style="font-size:11px;"><input style="font-size:10px; width:40px;" type="text" name="price_se"></td></tr>';
	echo '<input type="hidden" name="in_stock" value=1>';
	echo '<tr><td colspan="4"><input type="submit" class="greenOKbutton" value="Skapa artikel"></td></tr>';
	echo '</table>';
	echo '</form>';
	echo '<span style="font-size:14px; padding-left:20px; font-weight:normal;">Uppdatera artiklar</span>';
	echo '<br/><span style="padding-left:20px; font-weight:normal;"><a href="?update_add_art&set_in_stock" class="greenOKbutton" style="text-decoration:none;">Sätt alla artiklar i lager</a> <a href="?update_add_art&set_out_of_stock" class="greenOKbutton" style="text-decoration:none;">Sätt alla artiklar slut i lager</a><br/></span>';
	echo '<form method="post" action="?update_all_art">';
	echo '<table style="padding-left:16px;" border=0 cellspacing=5>';
	echo '<tr><td valign="top" align="left" style="font-size:11px; font-weight:bold;">Kategori</td><td valign="top" align="left" style="font-size:11px; font-weight:bold;">Artikelnummer</td><td valign="top" align="left" style="font-size:11px; font-weight:bold;">Alternativt art.nr</td><td valign="top" align="left" style="font-size:11px; font-weight:bold;">Namn</td><td valign="top" align="left" style="font-size:11px; font-weight:bold;">Pris</td><td valign="top" align="left" style="font-size:11px; font-weight:bold;">Tillgänglighet</td><td valign="top" align="left" style="font-size:11px; font-weight:bold;">Åtgärder</td></tr>';
	$result = mysql_query("SELECT * FROM products ORDER BY art_id");
	$i=0;
	while ($row = mysql_fetch_array($result)) {
		echo '<tr><td valign="top" align="left" style="font-size:11px;">'.$row['art_category'].'</td><td valign="top" align="left" style="font-size:11px;"><input type="hidden" name="org_art_id'.$i.'" value="'.$row['art_id'].'"><input type="text" style="font-size:10px; width:80px;" name="art_id'.$i.'" value="'.$row['art_id'].'"></td><td valign="top" align="left" style="font-size:11px;"><input type="text" style="font-size:10px; width:80px;" name="alt_art_id'.$i.'" value="'.$row['alt_art_id'].'"></td><td valign="top" align="left" style="font-size:11px;"><input type="text" style="font-size:10px; width:200px;" name="art_name_sv'.$i.'" value="'.utf8_encode($row['art_name_sv']).'"></td><td valign="top" align="left" style="font-size:11px;"><input style="font-size:10px; width:40px;" type="text" name="price_se'.$i.'" value="'.$row['price_se'].'"></td>';
		if ($row['in_stock']==1) {
			echo '<td valign="top" align="left" style="font-size:11px;">Finns i lager</td><td valign="top" align="left" style="font-size:11px;"><a href="?delete_art&del_art_id='.$row['art_id'].'">Radera</a> | <a href="?update_add_art&set_out_of_stock&art_id='.$row['art_id'].'">Slut i lager</a></td></tr>';
		} else {
			echo '<td valign="top" align="left" style="font-size:11px;">Finns ej i lager</td><td valign="top" align="left" style="font-size:11px;"><a href="?delete_art&del_art_id='.$row['art_id'].'">Radera</a> | <a href="?update_add_art&set_in_stock&art_id='.$row['art_id'].'">Återför i lager</a></td></tr>';
		}
		$i++;
	}
	echo '<tr><td colspan="7"><input type="submit" class="greenOKbutton" value="Uppdatera alla artiklar"></td></tr>';
	echo '</table>';
	echo '</form>';

}

if (isset($_GET['testar'])) {

	if (!isset($_GET['p'])) {
		$page = 0;
	} else {
		$page = $_GET['p'];
	}
	echo '<br/><div class="clearfix">&nbsp;</div><h2 style="padding-left:20px;">ORDERSAMMANSTÄLLNING</h2>';
	echo '<div class="clearfix">&nbsp;</div>';
	//echo '<div class="grid_12"><a href="?orders">Nya ordrar</a> | <a href="?orders&sort=f_printed">Ordrar där lökar inte skrivits ut</a> | <a href="?orders&sort=total_printed">Helt utskrivna ordrar</a></div><div class="clearfix">&nbsp;</div><br/><br/>';
	echo '<table border=0 width="1000" cellpadding=0 cellspacing=0 class="tabell" >';
	echo '<tr><td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:14px;"><b>Order ID</b></td>';
	echo '<td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:14px;"><b>Namn</b></td>';
	echo '<td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:14px;"><b>Personnummer</b></td>';
	echo '<td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:14px;"><b>Totalt att fakturera</b></td>';
	echo '<td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:14px;"><b></b></td></tr>';
	$result = mysql_query("SELECT * FROM orders WHERE order_status_id = 5 ORDER BY id LIMIT ".($page*500).",500") or exit (mysql_error());
	while ($row = mysql_fetch_array($result)) {
		$i++;
		if ($i%2==0) {
			$bgColor = 'style="padding-left:10px; padding-bottom:2px; padding-top:2px; background-color:#FFF;"';
		} else {
			$bgColor = 'style="padding-left:10px; padding-bottom:2px; padding-top:2px; background-color:#EEE;"';
		}
		echo '<tr><td valign="top" align="left" '.$bgColor.'><span style="font-size:14px;"><b>'.$row['id'].'</b></span></td>';		
		$result2 = mysql_query("SELECT * FROM customers_youth WHERE personal_id = '".$row['customer_personal_id']."'") or exit (mysql_error());
		$row2 = mysql_fetch_array($result2);
		if ($row2['school_id']==0) {
			echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:14px;"><b>'.utf8_encode(ucfirst($row2['forename'])).' '.utf8_encode(ucfirst($row2['lastname'])).'</b>';
		} else {
			$result22 = mysql_query("SELECT * FROM customers_school WHERE id = ".$row2['school_id']) or exit (mysql_error());
			$row22 = mysql_fetch_array($result22);
			echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:14px;"><b><span style="color:green;">'.utf8_encode(ucfirst($row22['school_name'])).'</span><br/> '.utf8_encode(ucfirst($row2['forename'])).' '.utf8_encode(ucfirst($row2['lastname'])).'</b>';
		}
		echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:14px;"><b>'.$row['customer_personal_id'].'</b></span></td>';

		$result3 = mysql_query("SELECT orders_id, product_art_id, SUM(quantity_of_product) FROM orders_content WHERE orders_id= ".$row['id']." GROUP BY product_art_id ORDER BY product_art_id");
		$salestotal_SEK=0;
		while ($row3 = mysql_fetch_array($result3)) {
			$result4 = mysql_query("SELECT * FROM products WHERE art_id = '".$row3['product_art_id']."'");
			$r4 = mysql_fetch_array($result4);
			$salestotal_SEK = $salestotal_SEK + ($row3['SUM(quantity_of_product)'] * $r4['price_se']);
		}	
		echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:14px;"><b>'.number_format(($salestotal_SEK),2).'</td></tr>';
		echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:12px;"><b>';
		echo '</td></tr>';	
	}
			
	echo '</table>';
	if (!$page==0) echo '<a href="?testar&p='.($page-1).'" class="button">Föregående sida</a> ';
	echo '<a href="?testar&p='.($page+1).'" class="button">Nästa sida</a>';
}
	if (isset($_GET['kunder'])) {

	echo '<div class="clearfix">&nbsp;</div><h2 style="padding-left:20px;">Kundhantering</div><span style="font-size:12px; font-weight:normal;">';
?>
<form method="post" action="index.php?kunder&dosearch">
<span style="font-size:14px; font-weight:bold;">Sök kund</span>
<br/>Sök kund genom att ange hela eller del av; efternamn, kontaktperson, personnummer, skolnamn eller skol-id
<p><input type="text" style="font-size:12px; height:22px" name="searchfrase"> <input type="submit" class="greenOKbutton" value="SÖK"></p>
</form>
<?php
if (isset($_GET['fail'])) {
		echo '<div style="width:978px; margin-bottom:2px; padding:10px; padding-bottom:5px; border:1px dotted #888; text-align:center; background-color:#e4685d;" id="deleted_id">';
		echo '<span align="center"><b>Kunden går ej att radera!</b><br/>Då det finns ordrar knutna till kunden går det inte att radera denne ur systemet.<br/>Radera kundens ordrar först och försök igen!</b></span>';
		echo '</div>';
}
if (isset($_GET['cust_deleted'])) {
		echo '<div style="width:978px; margin-bottom:2px; padding:10px; padding-bottom:5px; border:1px dotted #888; text-align:center; background-color:#a5cc52;" id="deleted_id">';
		echo '<span align="center"><b>Kunden raderades ur systemet!</b></span>';
		echo '</div>';
}
if (isset($_GET['cust_updated'])) {
		echo '<div style="width:978px; margin-bottom:2px; padding:10px; padding-bottom:5px; border:1px dotted #888; text-align:center; background-color:#a5cc52;" id="updated_id">';
		echo '<span align="center"><b>Kundens nya uppgifter har sparats i systemet!</b></span>';
		echo '</div>';
}
if (isset($_GET['school_deleted'])) {
		echo '<div style="width:978px; margin-bottom:2px; padding:10px; padding-bottom:5px; border:1px dotted #888; text-align:center; background-color:#a5cc52;" id="deleted_id">';
		echo '<span align="center"><b>Skolan med alla dess elever raderades ur systemet!</b></span>';
		echo '</div>';
}
if (isset($_GET['dosearch'])) {
	//sökning
	echo '<p><big>Sökresultat</big></p>';	
	echo '<table border=0 width="1000" cellpadding=0 cellspacing=0 class="tabell" >';
	echo '<td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:12px;"><b>Namn</b></td>';
	echo '<td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:12px;"><b>Personnr/Elev-ID</b></td>';
	echo '<td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:12px;"><b>Skola</b></td>';
	echo '<td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:12px;"><b>Kontaktperson</b></td>';
	echo '<td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:12px;"><b>Tillgängliga ordrar</b></td>';
	echo '<td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:12px;"><b>Åtgärder</b></td></tr>';
	if (isset($_GET['rollback_search'])) {
		$searchfrase = $_GET['rollback_search'];
	} else {
		$searchfrase = $_POST['searchfrase'];
	}
	mysql_query("SET NAMES 'utf8'") or die(mysql_error());
	mysql_query("SET CHARACTER SET 'utf8'") or die(mysql_error()); 	
	$result = mysql_query("SELECT emilstra_a2012.customers_school.class_name,emilstra_a2012.customers_youth.school_id,emilstra_a2012.customers_youth.address,emilstra_a2012.customers_youth.postal,emilstra_a2012.customers_youth.city,emilstra_a2012.customers_youth.phone,emilstra_a2012.customers_youth.email,emilstra_a2012.customers_youth.forename,emilstra_a2012.customers_youth.lastname,emilstra_a2012.customers_youth.personal_id,emilstra_a2012.customers_youth.contact_person,emilstra_a2012.customers_school.school_name,emilstra_a2012.customers_school.contactperson FROM emilstra_a2012.customers_school RIGHT JOIN emilstra_a2012.customers_youth ON emilstra_a2012.customers_school.id = emilstra_a2012.customers_youth.school_id WHERE emilstra_a2012.customers_school.id = '".$searchfrase."' OR emilstra_a2012.customers_school.contactperson LIKE '%".$searchfrase."%' OR emilstra_a2012.customers_school.school_name LIKE '%".$searchfrase."%' OR emilstra_a2012.customers_youth.personal_id LIKE '%".$searchfrase."%' OR emilstra_a2012.customers_youth.contact_person LIKE '%".$searchfrase."%' OR emilstra_a2012.customers_youth.lastname LIKE '%".$searchfrase."%'") or exit (mysql_error());

	while ($row = mysql_fetch_array($result)) {
		$i++;
		if ($i%2==0) {
			$bgColor = 'style="padding-left:10px; padding-bottom:2px; padding-top:2px; background-color:#FFF;"';
		} else {
			$bgColor = 'style="padding-left:10px; padding-bottom:2px; padding-top:2px; background-color:#EEE;"';
		}
		
		echo '<tr><td valign="top" align="left" '.$bgColor.'><span style="font-size:11px;">'.ucfirst($row['forename']).' '.ucfirst($row['lastname']).'</span></td>';	
		echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:11px;">'.$row['personal_id'].'</span></td>';	
		echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:11px;">'.$row['school_name'].', '.$row['class_name'].'</span></td>';	
		echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:11px;">'.$row['contact_person'].$row['contactperson'].'</span></td>';	
		echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:11px;">';		
		$result2 = mysql_query("SELECT * FROM orders WHERE customer_personal_id ='".$row['personal_id']."'") or exit (mysql_error());
		$j=0;
			while ($row2 = mysql_fetch_array($result2)) {
			if($j>=1) echo ", ";
			echo '<a href="?showorder&id='.$row2['id'].'">'.$row2['id'].'</a>';
			$j++;
			}
		echo '</td>';
		if (isset($row['school_name'])) { 
		$typeofcust = "&type=school"; 
		} else {
		$typeofcust = "&type=youth";
		}
		echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:11px;"><a href="javascript:ShowContent(\'change'.$row['personal_id'].'\');">Ändra kunduppgifter</a> | <a href="javascript:ShowContent(\'radera'.$row['personal_id'].'\');">Radera kund</a>';
		if(isset($row['school_name'])) {
			echo ' | <a href="?addschool&addpupilsfor='.$row['school_id'].'">Klassöversikt</a>';
		}
		echo '</span></td>';	
		echo '</tr>';
		echo '<div style="width:978px!important; margin-bottom:2px; padding:10px; padding-bottom:5px; border:1px dotted #888; background-color:#e4685d; display:none;" id="radera'.$row['personal_id'].'">';
			if (isset($row['school_name'])) { 
			echo "<b>".ucfirst($row['forename']).' '.ucfirst($row['lastname'])."</b> är en skolungdom. Vill du radera hela skolan eller bara eleven?";
			echo " &nbsp;&nbsp;<a href='?delete_cust_pid=".$row['personal_id']."&onlypupil=yes&rollback_search=".$searchfrase."' style='text-decoration:none;' class='whitebutton'>ELEV</a> <a href='?delete_cust_pid=".$row['personal_id']."&onlypupil=no&rollback_search=".$searchfrase."' style='text-decoration:none;' class='whitebutton'>SKOLA</a><span style='float:right;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href='javascript:HideContent(\"radera".$row['personal_id']."\");' style='text-decoration:none; font-size:12px;' class='whitebutton'>&nbsp;&nbsp;&nbsp; AVBRYT &nbsp;&nbsp;&nbsp;</a></span>";
			} else {
			echo "Är du säker på att <b>".ucfirst($row['forename']).' '.ucfirst($row['lastname'])."</b> skall raderas?";
			echo " &nbsp;&nbsp;<a href='?delete_cust_pid=".$row['personal_id']."&onlypupil=yes&rollback_search=".$searchfrase."' style='text-decoration:none;' class='whitebutton'>JA</a><span style='float:right;'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href='javascript:HideContent(\"radera".$row['personal_id']."\");' style='text-decoration:none; font-size:12px;' class='whitebutton'>&nbsp;&nbsp;&nbsp; AVBRYT &nbsp;&nbsp;&nbsp;</a></span>";
			}
		echo '</div>';
		echo '<div style="width:978px; margin-bottom:2px; padding:10px; padding-bottom:15px; border:1px dotted #888; background-color:#a5cc52; display:none;" id="change'.$row['personal_id'].'">';
		?>
		<form method="post" action="?updatecustomerinfo&pid=<?php echo $row['personal_id']; ?>&rollback_search=<?php echo $searchfrase; ?>">
		<b><?php echo ucfirst($row['forename']).' '.ucfirst($row['lastname']).', '.$row['personal_id']; ?></b><br/>
		Kontaktperson:<br/><input type="text" name="contact_person" value="<?php echo $row['contact_person']; ?>"><br/>
		Adress, postnr och ort:<br/>
		<input type="text" name="address" value="<?php echo $row['address']; ?>"> <input type="text" name="postal" value="<?php echo $row['postal']; ?>"> <input type="text" name="city" value="<?php echo $row['city']; ?>"><br/>
		Telefonnummer och e-post:<br/>
		<input type="text" name="phone" value="<?php echo $row['phone']; ?>"> <input type="text" name="email" value="<?php echo $row['email']; ?>">
		<?php
		echo "<span style='float:right;'><input type='submit' class='whitebutton' value='SPARA ÄNDRINGAR'> <a href='javascript:HideContent(\"change".$row['personal_id']."\");' class='whitebutton'>AVBRYT</a></span>";

		echo '</div>';		
	} 	
	
	echo '</table>';
}
}
	if (isset($_GET['orders'])) {
	if (!isset($_GET['p'])) {
		$page = 0;
	} else {
		$page = $_GET['p'];
	}
	echo '<div class="clearfix">&nbsp;</div><h2 style="padding-left:20px;">Orderhantering</div><br/><span style="font-size:12px; font-weight:normal;">';
	echo 'Visa: <a href="?orders">Nyinkomna ordrar</a> | <a href="?orders&sort=f_printed">Delvis klara ordrar</a> | <a href="?orders&sort=total_printed">Färdiga ordrar</a></br><br/>';
?>
<form method="post" action="index.php?orders&dosearch">
<span style="font-size:14px; font-weight:bold;">Sök order</span>
<br/>Sök order genom att ange orderid, personnummer eller skol/elev-id
<p><input type="text" style="font-size:12px; height:22px" name="searchfrase"> <input type="submit" class="greenOKbutton" value="SÖK"></p>
</form>
<?php
if (isset($_GET['id_deleted'])) {
		echo '<div style="width:1100px; margin-bottom:2px; padding:10px; padding-bottom:5px; border:1px dotted #888; text-align:center; background-color:#a5cc52;" id="deleted_id">';
		echo '<span align="center"><b>Order ID '.$_GET['id_deleted'].'</b> raderades ur systemet!</span>';
		echo '</div>';
}
if (isset($_GET['order_updated'])) {
		echo '<div style="width:1100px; margin-bottom:2px; padding:10px; padding-bottom:5px; border:1px dotted #888; text-align:center; background-color:#a5cc52;" id="deleted_id">';
		echo '<span align="center"><b>Ordern uppdaterades!</b></span>';
		echo '</div>';
}
if (isset($_GET['dosearch'])) {
	//sökning
	echo '<p><big>Sökresultat</big></p>';	
	echo '<table width=1100 border=0 cellpadding=0 cellspacing=0 class="tabell" >';
	echo '<tr><td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:12px;"><b>Order ID</b></td>';
	echo '<td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:12px;"><b>Namn</b></td>';
	echo '<td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:12px;"><b>Personnr/Elev-ID</b></td>';
	echo '<td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:12px;"><b>Orderdatum</b></td>';
	echo '<td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:12px;"><b>Orderstatus</b></td>';
	echo '<td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:12px;"><b>Åtgärder</b></td></tr>';
	
	if (isset($_GET['rollback_search'])) {
		$searchfrase = $_GET['rollback_search'];
	} else {
		$searchfrase = $_POST['searchfrase'];
	}
		$result = mysql_query("SELECT * FROM orders WHERE id = '".$searchfrase."' OR customer_personal_id LIKE '%".$searchfrase."%'") or exit (mysql_error());

	
	while ($row = mysql_fetch_array($result)) {
		$i++;
		if ($i%2==0) {
			$bgColor = 'style="padding-left:10px; padding-bottom:2px; padding-top:2px; background-color:#FFF;"';
		} else {
			$bgColor = 'style="padding-left:10px; padding-bottom:2px; padding-top:2px; background-color:#EEE;"';
		}

		$f_available = 0;
		$lt_available = 0;
		echo '<tr><td valign="top" align="left" '.$bgColor.'><span style="font-size:11px;">'.$row['id'].'</span></td>';		
		$result2 = mysql_query("SELECT * FROM customers_youth WHERE personal_id = '".$row['customer_personal_id']."'") or exit (mysql_error());
		$row2 = mysql_fetch_array($result2);
		if ($row2['school_id']==0) {
			echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:11px;">'.utf8_encode(ucfirst($row2['forename'])).' '.utf8_encode(ucfirst($row2['lastname']));
		} else {
			$result22 = mysql_query("SELECT * FROM customers_school WHERE id = ".$row2['school_id']) or exit (mysql_error());
			$row22 = mysql_fetch_array($result22);
			echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:11px;"><span style="color:green;">'.utf8_encode(ucfirst($row22['school_name'])).'</span><br/> '.utf8_encode(ucfirst($row2['forename'])).' '.utf8_encode(ucfirst($row2['lastname']));
		}
		echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:11px;">'.$row['customer_personal_id'].'</span></td>';
		if (isset($row['order_last_change_date'])) {
			echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:11px;">'.substr($row['order_last_change_date'],0,10).'</span></td>';
		} else {
			echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:11px;">'.substr($row['order_created_date'],0,10).'</span></td>';
		}
		if ($row['order_status_id'] == 1) {
		echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:11px; color:990000;"><b>Ej inskickad</b></span></td>';
		} elseif ($row['order_status_id'] == 2) {
		echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:11px; color:ffbf57;"><b>Nyinkommen</b></span></td>';
		} elseif ($row['order_status_id'] == 3) {
		echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:11px; color:ffbf57;"><b>Frö-/tillbehörsorder klar</b></span></td>';
		} elseif ($row['order_status_id'] == 5) {
		echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:11px; color:528009;"><b>Hela ordern klar</b></span></td>';
		}
		$result3 = mysql_query("SELECT orders_id, product_art_id, SUM(quantity_of_product) FROM orders_content WHERE orders_id= ".$row['id']." GROUP BY product_art_id ORDER BY product_art_id");

		while ($row3 = mysql_fetch_array($result3)) {
			$result4 = mysql_query("SELECT * FROM products WHERE art_id = '".$row3['product_art_id']."'");
			$r4 = mysql_fetch_array($result4);
			if ($r4['art_category'] == "F") $f_available = 1;
			if ($r4['art_category'] == "VL" || $r4['art_category'] == "HL" || $r4['art_category'] == "T") $lt_available = 1; 
		}	
		echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:11px;">';

			echo '<a target="_blank" href="?showorder&id='.$row['id'].'">Visa order</a>';
			if ($f_available == 1 && $lt_available != 1) echo ' | <a target="_blank" href="?showorder&status=5&id='.$row['id'].'&cats=FT">Skriv ut hela ordern</a>';
			if ($f_available == 1 && $lt_available == 1 ) echo ' | <a target="_blank" href="?showorder&status=3&id='.$row['id'].'&cats=FT">Skriv ut för-/tillbehörsorder</a>';
			if ($lt_available == 1 && $f_available == 1 ) echo ' | <a target="_blank" href="?showorder&status=5&id='.$row['id'].'&cats=L">Skriv ut lökorder</a>';
			if ($f_available != 1 && $lt_available == 1) echo ' | <a target="_blank" href="?showorder&status=5&id='.$row['id'].'&cats=FT">Skriv ut hela ordern</a>';
			if ($lt_available == 1 && $f_available == 1) echo ' | <a target="_blank" href="?showorder&status=5&id='.$row['id'].'">Skriv ut hela ordern</a>';
			echo '<br/><a href="?editorder&rollback_search='.$searchfrase.'&editid='.$row['id'].'">Ändra i order</a>';
			echo ' | <a href="javascript:ShowContent(\'delete_order_id_'.$row['id'].'\');">Radera order</a>';
		if ($row2['school_id']==0) {
			echo '| <a target="_blank" href="?showbill&inc_overview&id='.$row['id'].'">Faktura</a>';
		} else {
			echo '| <a target="_blank" href="?showbill&inc_overview&school_id='.$row2['school_id'].'">Faktura</a> | <a target="_blank" href="?showorder&inc_overview&id='.$row['id'].'">Klass spec</a> | <a href="?addschool&addpupilsfor='.$row2['school_id'].'">Lägg till elever</a> &nbsp;&nbsp;';
		}
		echo '</td></tr>';	
		echo '<div style="width:978px!important; margin-bottom:2px; padding:10px; padding-bottom:5px; border:1px dotted #888; background-color:#e4685d; display:none;" id="delete_order_id_'.$row['id'].'">';
		echo 'Är du säker på att <b>order ID '.$row['id'].'</b> skall raderas?  &nbsp;&nbsp;<a href="?delete_order_id='.$row['id'].'&rollback_search='.$searchfrase.'" style="text-decoration:none;" class="whitebutton">JA</a><span style="float:right;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="javascript:HideContent(\'delete_order_id_'.$row['id'].'\');" style="text-decoration:none; font-size:12px;" class="whitebutton">&nbsp;&nbsp;&nbsp; AVBRYT &nbsp;&nbsp;&nbsp;</a></span>';
		echo '</div>';
	}
			
	echo '</table>';
	
} else {
	//ingen sökning
	
	echo '</br></br>';
	echo '<table width=1100 border=0 cellpadding=0 cellspacing=0 class="tabell" >';
	echo '<tr><td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:12px;"><b>Order ID</b></td>';
	echo '<td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:12px;"><b>Namn</b></td>';
	echo '<td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:12px;"><b>Personnummer</b></td>';
	echo '<td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:12px;"><b>Orderdatum</b></td>';
	echo '<td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:12px;"><b>Orderstatus</b></td>';
	echo '<td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:12px;"><b>Åtgärder</b></td></tr>';
	if(isset($_GET['sort'])) {
		if ($_GET['sort'] == "f_printed") $result = mysql_query("SELECT * FROM orders WHERE order_status_id = 3 ORDER BY order_last_change_date, order_created_date ASC LIMIT ".($page*100000).",100000") or exit (mysql_error());
		if ($_GET['sort'] == "total_printed") $result = mysql_query("SELECT * FROM orders WHERE order_status_id = 5 ORDER BY order_last_change_date, order_created_date ASC LIMIT ".($page*100000).",100000") or exit (mysql_error());
		if ($_GET['sort'] == "") $result = mysql_query("SELECT * FROM orders WHERE order_status_id = 2 ORDER BY order_last_change_date, order_created_date ASC LIMIT ".($page*100000).",100000") or exit (mysql_error());
	} else {
		$result = mysql_query("SELECT * FROM orders WHERE order_status_id = 2 ORDER BY order_last_change_date, order_created_date ASC LIMIT ".($page*100000).",100000") or exit (mysql_error());
	}
	while ($row = mysql_fetch_array($result)) {
		$i++;
		if ($i%2==0) {
			$bgColor = 'style="padding-left:10px; padding-bottom:2px; padding-top:2px; background-color:#FFF;"';
		} else {
			$bgColor = 'style="padding-left:10px; padding-bottom:2px; padding-top:2px; background-color:#EEE;"';
		}

		$f_available = 0;
		$lt_available = 0;
		echo '<tr><td valign="top" align="left" '.$bgColor.'><span style="font-size:11px;">'.$row['id'].'</b></span></td>';		
		$result2 = mysql_query("SELECT * FROM customers_youth WHERE personal_id = '".$row['customer_personal_id']."'") or exit (mysql_error());
		$row2 = mysql_fetch_array($result2);
		if ($row2['school_id']==0) {
			echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:11px;">'.utf8_encode(ucfirst($row2['forename'])).' '.utf8_encode(ucfirst($row2['lastname'])).'</b>';
		} else {
			$result22 = mysql_query("SELECT * FROM customers_school WHERE id = ".$row2['school_id']) or exit (mysql_error());
			$row22 = mysql_fetch_array($result22);
			echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:11px;"><span style="color:green;">'.utf8_encode(ucfirst($row22['school_name'])).'</span><br/> '.utf8_encode(ucfirst($row2['forename'])).' '.utf8_encode(ucfirst($row2['lastname'])).'</b>';
		}
		echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:11px;">'.$row['customer_personal_id'].'</b></span></td>';
		if (isset($row['order_last_change_date'])) {
			echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:11px;">'.substr($row['order_last_change_date'],0,10).'</b></span></td>';
		} else {
			echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:11px;">'.substr($row['order_created_date'],0,10).'</b></span></td>';
		}
		if ($row['order_status_id'] == 1) {
		echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:11px; color:990000;"><b>Ej inskickad</b></span></td>';
		} elseif ($row['order_status_id'] == 2) {
		echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:11px; color:ffbf57;"><b>Nyinkommen</b></span></td>';
		} elseif ($row['order_status_id'] == 3) {
		echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:11px; color:ffbf57;"><b>Frö-/tillbehörsorder klar</b></span></td>';
		} elseif ($row['order_status_id'] == 5) {
		echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:11px; color:528009;"><b>Hela ordern klar</b></span></td>';
		}
		$result3 = mysql_query("SELECT orders_id, product_art_id, SUM(quantity_of_product) FROM orders_content WHERE orders_id= ".$row['id']." GROUP BY product_art_id ORDER BY product_art_id");

		while ($row3 = mysql_fetch_array($result3)) {
			$result4 = mysql_query("SELECT * FROM products WHERE art_id = '".$row3['product_art_id']."'");
			$r4 = mysql_fetch_array($result4);
			if ($r4['art_category'] == "F") $f_available = 1;
			if ($r4['art_category'] == "VL" || $r4['art_category'] == "HL" || $r4['art_category'] == "T") $lt_available = 1; 
		}	
		echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:11px;">';
		echo '<a target="_blank" href="?showorder&id='.$row['id'].'">Visa order</a>';
		if ($f_available == 1 && $lt_available != 1) echo ' | <a target="_blank" href="?showorder&status=5&id='.$row['id'].'&cats=FT">Skriv ut hela ordern</a>';
		if ($f_available == 1 && $lt_available == 1 ) echo ' | <a target="_blank" href="?showorder&status=3&id='.$row['id'].'&cats=FT">Skriv ut frö-/tillbehörsorder</a>';
		if ($lt_available == 1) echo ' | <a target="_blank" href="?showorder&status=5&id='.$row['id'].'&cats=L">Skriv ut lökorder</a>';
		if ($lt_available == 1 && $f_available == 1) echo ' | <a target="_blank" href="?showorder&status=5&id='.$row['id'].'">Skriv ut hela ordern</a>';
		if ($lt_available != 1 && $f_available == 1) echo ' | <a target="_blank" href="?showorder&status=5&id='.$row['id'].'">Skriv ut hela ordern</a>';
		echo '<br/><a href="?editorder&editid='.$row['id'].'">Ändra i order</a>';
		echo ' | <a href="javascript:ShowContent(\'delete_order_id_'.$row['id'].'\');">Radera order</a>';
		if ($row2['school_id']==0) {
			echo ' | <a target="_blank" href="?showbill&inc_overview&id='.$row['id'].'">Faktura</a>';
		} else {
			echo ' | <a target="_blank" href="?showbill&inc_overview&school_id='.$row2['school_id'].'">Faktura</a> | <a target="_blank" href="?showorder&inc_overview&id='.$row['id'].'">Klass spec</a> | <a href="?addschool&addpupilsfor='.$row2['school_id'].'">Lägg till elever &nbsp;&nbsp;</a>';
		}
		echo '</td></tr>';	
		echo '<div style="margin-bottom:2px; padding:10px; padding-bottom:5px; border:1px dotted #888; background-color:#e4685d; display:none;" id="delete_order_id_'.$row['id'].'">';
		echo 'Är du säker på att <b>order ID '.$row['id'].'</b> skall raderas?  &nbsp;&nbsp;<a href="?delete_order_id='.$row['id'].'" style="text-decoration:none;" class="whitebutton">JA</a><span style="float:right;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="javascript:HideContent(\'delete_order_id_'.$row['id'].'\');" style="text-decoration:none; font-size:12px;" class="whitebutton">&nbsp;&nbsp;&nbsp; AVBRYT &nbsp;&nbsp;&nbsp;</a></span>';
		echo '</div>';
	}
	?>
	<!--
	echo '<tr><td colspan="5">';		
	if (!$page==0) echo '<a href="?orders&sort='.$_GET['sort'].'&p='.($page-1).'" class="greenOKbutton">Föregående sida</a> ';
	echo '<a href="?orders&sort='.$_GET['sort'].'&p='.($page+1).'" class="greenOKbutton">Nästa sida</a>';
	echo '</td></tr></table>';
	-->
	<?php
}
}

?>

</div>		
</body>
</html>