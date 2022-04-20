<?php
session_start(); 
mysql_query( "SET CHARACTER SET utf8"); 
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
setcookie("user","eadmin", time() + 3600);
header("Location: http://www.emilstradgard.se/inloggad/index.php?pupiloverview");
}

include('../inloggad/db_settings.php');


if (isset($_GET['checkedit'])) {
	
	for ($i=0;$i<120;$i++) {

		$_SESSION['art_id_field_'.$i] = $_POST['art_id_field_'.$i];
		$_SESSION['q_art_id_field_'.$i] = $_POST['q_art_id_field_'.$i];
		
		$art_id = $_POST['art_id_field_'.$i];
		if ($art_id == "") continue;
		$result = mysql_query("SELECT * FROM products WHERE art_id = '".$art_id."'");
		$num_rows = mysql_num_rows($result);
		$_SESSION['error_art_id'.$i] = 0;
		if ($num_rows == "1") {
			if ($_POST['q_art_id_field_'.$i] == "") {				 
				$_SESSION['errormessage'] .= '<h6 style="color:red">Fel på artikelnummer vid markering</h6>';
				$problem = 1;
				$_SESSION['error_art_id'.$i] = 1;
			}
		} else {
			$problem = 1;
			$_SESSION['errormessage'] .= '<h6 style="color:red">Inga antal angivna vid röd markering </h6>';
			$_SESSION['error_art_id'.$i] = 1;
		}
	}
	if ($problem == 1) {
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
	}
	
	
	mysql_query("SET NAMES 'utf8'") or die(mysql_error());
	mysql_query("SET CHARACTER SET 'utf8'") or die(mysql_error()); 	
	
	for ($i=0;$i<120;$i++) {
		$art_id = $_POST['art_id_field_'.$i];
		$q_art_id = $_POST['q_art_id_field_'.$i];
		if ($art_id == "") continue;
		mysql_query("INSERT INTO orders_content (orders_id, product_art_id, quantity_of_product) VALUES (".$_GET['editid'].",'".$art_id."',".$q_art_id.")") or exit(mysql_error());
		unset($_SESSION['art_id_field_'.$i]);
		unset($_SESSION['q_art_id_field_'.$i]);
	 }	
	 $_SESSION['errormessage'] = '<h6 style="color:green">Artiklarna lades till ordern & orderstatusen uppdaterades!';
	 $_SESSION['errormessage'] .='<br/><br/><span style="font-size:14px;"><a href="index.php?orders">Gå tillbaka till Orderhanteringen</a></span></h5><p>&nbsp;</p>';
	 header("Location: " . $_SERVER['HTTP_REFERER']);
	 exit;

}
if (isset($_GET['checkschool'])) {
	if ($_POST['contactperson'] == "" || $_POST['school'] == "" || $_POST['klass'] == "" || $_POST['address'] == "" || $_POST['postal'] == "" || $_POST['city'] == "") {
		$_SESSION['errormessage'] = '<h6 style="color:red">Kundinfo saknas, kontrollera uppgifterna!</h6>';
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
	}
	mysql_query("SET NAMES 'utf8'") or die(mysql_error());
	mysql_query("SET CHARACTER SET 'utf8'") or die(mysql_error()); 
	mysql_query("INSERT INTO customers_school (school_name, class_name, address, postal, city, country_id, contactperson, phone, shipping_to) VALUES ('".$_POST['school']."', '".$_POST['klass']."', '".$_POST['address']."', '".$_POST['postal']."', '".$_POST['city']."', ".$_POST['country'].", '".$_POST['contactperson']."', '".$_POST['phone']."', '".$_POST['shipto']."')");
	$_SESSION['errormessage'] = '<h6 style="color:green">Skolan skapades, lägg nu till elever och ordrar!</h6>';
	header('Location: http://www.emilstradgard.se/eadmin/index.php?addschool');
}


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
	
	if ($_POST['personalid'] == "" || $_POST['adult'] == "" || $_POST['forename'] == "" || $_POST['lastname'] == "" || $_POST['address'] == "" || $_POST['postal'] == "" || $_POST['city'] == "" || $_POST['phone'] == "") {
		$_SESSION['errormessage'] = '<h6 style="color:red">Kundinfo saknas, kontrollera uppgifterna!</h6>';
		$problem=1;
	}
//	for ($i=0;$i<120;$i++) {
//		setcookie("art_id_field_".$i, $_POST['art_id_field_'.$i], time() + 3600);
//		setcookie("q_art_id_field_".$i, $_POST['q_art_id_field_'.$i], time() + 3600);
//		$art_id = $_POST['art_id_field_'.$i];
//		if ($art_id == "") continue;
//		$result = mysql_query("SELECT * FROM products WHERE art_id = '".$art_id."'");
//		$num_rows = mysql_num_rows($result);
//		$_SESSION['error_art_id'.$i] = 0;
//		if ($num_rows == "1") {
//			if ($_POST['q_art_id_field_'.$i] == "") {				 
//				$_SESSION['errormessage'] .= '<h6 style="color:red">Fel på artikelnummer vid markering</h6>';
//				$problem = 1;
//				$_SESSION['error_art_id'.$i] = 1;
//				//header("Location: " . $_SERVER['HTTP_REFERER']);
//				//exit;
//			}
//		} else {
//			$problem = 1;
//			$_SESSION['errormessage'] .= '<h6 style="color:red">Inga antal angivna vid röd markering </h6>';
//			$_SESSION['error_art_id'.$i] = 1;
//			//header("Location: " . $_SERVER['HTTP_REFERER']);
//			//exit;
//		}
//	}
	if ($problem == 1) {
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
	}
	
	
	mysql_query("SET NAMES 'utf8'") or die(mysql_error());
	mysql_query("SET CHARACTER SET 'utf8'") or die(mysql_error()); 
	mysql_query("INSERT INTO customers_youth (personal_id, contact_person, forename, lastname, address, postal, city, country_id, phone) VALUES ('".$_POST['personalid']."', '".$_POST['adult']."', '".$_POST['forename']."', '".$_POST['lastname']."', '".$_POST['address']."', '".$_POST['postal']."', '".$_POST['city']."', ".$_POST['country'].", '".$_POST['phone']."')");
	
	mysql_query("INSERT INTO orders (customer_personal_id, order_status_id, order_last_change_date) VALUES ('".$_POST['personalid']."','2', '".date('Y-m-d H:i:s', time())."')");
	$latestOrderByCustomer = mysql_query("SELECT * FROM orders WHERE customer_personal_id ='".$_POST['personalid']."' ORDER BY order_created_date DESC LIMIT 1");
	$latestOrder = mysql_fetch_array($latestOrderByCustomer);
	$latestOrderId = $latestOrder['id'];
	
//	for ($i=0;$i<120;$i++) {
//		$art_id = $_POST['art_id_field_'.$i];
//		$q_art_id = $_POST['q_art_id_field_'.$i];
//		if ($art_id == "") continue;
//		mysql_query("INSERT INTO orders_content (orders_id, product_art_id, quantity_of_product) VALUES (".$latestOrderId.",'".$art_id."',".$q_art_id.")");
//		setcookie("art_id_field_".$i, "", time() - 3600);
//		setcookie("q_art_id_field_".$i, "", time() - 3600);
//	 }	
	 $_SESSION['errormessage'] = '<h6 style="color:green">Kunden skapades!</h6>';
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
</head>
<body height="100%" style="background:none;">
<?php
if (isset($_GET['showorder'])) {
} else {
?>
<h2 class="grid_12 caption clearfix">Emils Trädgård Administration </h2>
		
		<div class="hr solid clearfix">&nbsp;</div>
		
<?php
			$result = mysql_query("SELECT * FROM eadmin WHERE username = '". $_COOKIE['adminuser']."'") or exit (mysql_error());
			$row = mysql_fetch_array($result);
			
			//echo '<p>Välkommen, '.$row['name'].'</p>';
?>

<div class="grid_12 clearfix">
<input type="button" name="menuObject1" class="button" value="Orderhantering" onClick="parent.location='?orders'">
<input type="button" name="menuObject2" class="button" value="Statistik" onClick="parent.location='?statistics'">
<input type="button" name="menuObject3" class="button" value="Lägg till ungdom" onClick="parent.location='?addorder'">
<!--input type="button" name="menuObject3" class="button" value="Lägg till skola" onClick="parent.location='?addschool'"-->
<input type="button" name="menuObject4" class="button" value="Logga ut" onClick="parent.location='?logout'">
</div>
<?php
}

// if (isset($_GET['testarnytt'])) {
//    //SET THE SEARCH TERM
//    $term = "%lina%";
// 
//    //QUERY THE TITLE AND CONTENT COLUMN OF THE PAGES TABLE RANK IT BY THE SCORE
//    $sql = "SELECT * FROM customers_youth, customers_school, orders WHERE customers_youth.forename LIKE '".$term."' OR customers_youth.lastname LIKE '".$term."'";
//    $query = mysql_query($sql);
// 
//    //BUILD A LIST OF THE RESULTS
//    while($result = mysql_fetch_assoc($query)) {
//        echo $result['forename'].' '.$result['lastname'].'<br>';
//    }
// }
if (isset($_GET['search'])) {
	echo '<div class="clearfix">&nbsp;</div><h2 class="grid_12 clearfix">Sök</div><br/><br/><span style="font-size:12px;">';
?>
<form method="post" action="index.php?search&dosearch">
<b>Sök efter:</b> <input type="text" name="searchfrase"><br/>
<b>Sök bland:</b> <input type="checkbox" name="ungdomar" CHECKED> Ungdomar <input type="checkbox" name="elever" CHECKED> Skolelever <input type="checkbox" name="skolor" CHECKED> Skolor  <br/><br/>
<input type="submit" value="Utför sökning" class="button">
</form>
<?php
if (isset($_GET['dosearch'])) {
	$result = mysql_query("SELECT * FROM customers_youth WHERE lastname LIKE '%".$_POST['searchfrase']."%' OR forename LIKE '%".$_POST['searchfrase']."%' OR contact_person LIKE '%".$_POST['searchfrase']."%' OR personal_id LIKE '%".$_POST['searchfrase']."%'");
	$i=1;
	echo "<p><big>Sökresultat</big></p>";
	echo '<table width=800 border=0 class="tabell"><tr><td>DB</td><td><b>PERSONNR</td><td><b>INLOGGNINGS-ID</td><td><b>NAMN</td><td><b>VUXEN KONTAKTPERSON</td></tr>';
	while ($row = mysql_fetch_array($result)) {
		echo '<tr><td>'.$i.'</td><td>'.$row['personal_id'].'</td><td>'.$row['customer_id'].'</td><td>'.utf8_encode($row['forename']).' '.utf8_encode($row['lastname']).'</td><td>'.utf8_encode($row['contact_person']).'</td></tr>';
		$i++;
	}
	echo '</table>';
}
}




if (isset($_GET['statistics'])) {

 	echo '<div class="clearfix">&nbsp;</div><h2 class="grid_12 clearfix">Statistik<span style="font-size:12px;">, totalt antal per artikel.<br/>Sortering : <a href="?statistics&sort=artid">Numerisk ordning</a> | <a href="?statistics&sort=sum">Mest sålda</a> | <a href="?statistics&sort=nya">Beställda, ej utskrivna</a></span></h2><div class="clearfix">&nbsp;</div><div class="grid_8">';
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

echo '<div class="clearfix">&nbsp;</div><h2 class="grid_12 clearfix">Lägg till artiklar / Ändra orderstatus</h2>';
echo '<br/><br/><div class="clearfix">&nbsp;</div><br/>';

echo $_SESSION['errormessage']; 
$_SESSION['errormessage'] = "";
?>
<div class="grid_12 clearfix">
<form method="post" action="index.php?checkedit&editid=<?php echo $_GET['editid'];?>">
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
	echo '<td valign="top" align="left"><input size="5" type="text" id="art_id_field_'.$i.'" name="art_id_field_'.$i.'" value="'.$_SESSION['art_id_field_'.$i].'" '.$art_id_style.'> <input size="5" type="text" id="q_art_id_field_'.($i).'" name="q_art_id_field_'.($i).'" value="'.$_SESSION['q_art_id_field_'.$i].'" onkeypress="return isNumberKey(event)"></td>';

}
$_SESSION['error_art_id']="1000";
?>
</table>
Sätt orderstatus (1 = ej inskickad, 2 = inskickad)<br/> <input type="text" name="order_status" value="2"><br/><br/>
<input type="submit" class="button" value="Uppdatera order"> 
</div>
<?php
}
if (isset($_GET['addschool'])) {
	$result = mysql_query("SELECT * FROM customers_school WHERE customer_id = 0") or exit (mysql_error());
	$counted = mysql_num_rows($result);
	if ($counted >= 1) {
		echo '<div class="clearfix">&nbsp;</div><h2 class="grid_12 clearfix">Skapa elever & ordrar</h2>';
		echo '<div class="clearfix">&nbsp;</div>';
	}
	while ($row = mysql_fetch_array($result)) {
		echo '<p class="grid_12 clearfix"><a class="button" href="?pupilsoverview&customer_school_id='.$row['id'].'" target="_blank">'.$row['school_name'].' '.$row['class_name'].'</a> ';
	}
	echo '</p>';
				
		
echo '<div class="clearfix">&nbsp;</div><h2 class="grid_12 clearfix">Lägg till skola<br/><br/><span style="font-size:14px;">Fält markerade med * är obligatoriska!</span></h2>';
echo '<div class="clearfix">&nbsp;</div>';

echo $_SESSION['errormessage']; 
$_SESSION['errormessage'] = "";
?>

<div class="grid_12 clearfix">
<form method="post" action="index.php?checkschool">
<p><b>Skola *</b><br/><input type="text" name="school" value="<?php echo $_SESSION['school'];?>"></p>
<p><b>Klass *</b><br/><input type="text" name="klass" value="<?php echo $_SESSION['klass'];?>" size="5"></p>
<p>Levereras till (Anges om inte paket skall adresseras till Skolan)<br/><input type="text" name="shipto" value="<?php echo $_SESSION['shipto'];?>"></p>
<p><b>Kontaktperson *</b><br/><input type="text" name="contactperson"  value="<?php echo $_SESSION['contactperson'];?>"></p>
<p>Telefonnummer<br/> <input type="text" name="phone" value="<?php echo $_SESSION['phone'];?>"></p>
<p><b>Gatuadress *</b><br/><input type="text" name="address" size="35" value="<?php echo $_SESSION['address'];?>"></p>
<p><b>Postnummer *</b><br/><input type="text" name="postal" size="6" value="<?php echo $_SESSION['postal'];?>"></p>
<p><b>Ort *</b><br/><input type="text" name="city" size="20"  value="<?php echo $_SESSION['city'];?>"></p>
<p><b>Land *</b><br/> 
<select name="country">
<option value="1" <?php if ($_SESSION['c_code'] == 1) echo 'SELECTED'; ?>>Sverige</option>
<option value="2" <?php if ($_SESSION['c_code'] == 2) echo 'SELECTED'; ?>>Finland</option>
</select>
</table>
<p><input type="submit" class="button" value="Spara skola"></p>
</div>

<?php
}

if (isset($_GET['addorder'])) {

echo '<div class="clearfix">&nbsp;</div><h2 class="grid_12 clearfix">Lägg till ungdom och beställning</h2>';
echo '<div class="clearfix">&nbsp;</div>';

echo $_SESSION['errormessage']; 
$_SESSION['errormessage'] = "";
?>
<div class="grid_12 clearfix">
<form method="post" action="index.php?checkadd">
<p>Personnummer: <input type="text" name="personalid" value="<?php echo $_COOKIE['personalid'];?>"> Telefonnummer: <input type="text" name="phone" value="<?php echo $_COOKIE['phone'];?>"></p>
<p>Förnamn: <input type="text" name="forename" value="<?php echo $_COOKIE['forename'];?>"> Efternamn: <input type="text" name="lastname" value="<?php echo $_COOKIE['lastname'];?>"> Vuxen: <input type="text" name="adult"  value="<?php echo $_COOKIE['adult'];?>"></p>
<p>Gatuadress: <input type="text" name="address" size="35" value="<?php echo $_COOKIE['address'];?>"> Postnr: <input type="text" name="postal" size="6" value="<?php echo $_COOKIE['postal'];?>"> Ort: <input type="text" name="city" size="20"  value="<?php echo $_COOKIE['city'];?>"> Land: 
<select name="country">
<option value="1" <?php if ($_COOKIE['c_code'] == 1) echo 'SELECTED'; ?>>Sverige</option>
<option value="2" <?php if ($_COOKIE['c_code'] == 2) echo 'SELECTED'; ?>>Finland</option>
</select>
<p>&nbsp;</p>
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
<input type="submit" class="button" value="Spara kund"> 
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
			if ($r4['art_category'] == "A") continue;
		} elseif ($_GET['cats'] == "A") {
			if ($r4['art_category'] == "L") continue;
		}  
		$counted = $counted + $row3['SUM(quantity_of_product)'];
		echo '<tr><td align="center" '.$bgColor.'>'.$row3['SUM(quantity_of_product)'].'</td><td '.$bgColor.'>'.utf8_encode($row3['product_art_id']).'</td>';
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
	echo '<tr><td colspan="5" height="2" style="background-color:#000;"></td></tr>';
	if ($row2['country_id'] == 1) {
		echo '<tr><td align="center"><b>'.$counted.'</td><td></td><td></td><td></td><td><b>'.number_format($salestotal_SEK,2).' kr</td></tr>';
	} else {
		echo '<tr><td align="center"><b>'.$counted.'</td><td></td><td></td><td></td><td><b>€ '.number_format($salestotal_SEK,2).'</td></tr>';
	}

	echo '</table>';
	if ($row2['country_id'] == 1) {
		echo '<span style="font-size:12px;"><b>Försäljningsförtjänst: '.number_format(($salestotal_SEK * 0.3),2).' kr</span><br/>';
		echo '<span style="font-size:12px;"><b>Belopp som kommer att faktureras: '.number_format(($salestotal_SEK * 0.7),2).' kr</span><br/>';
	} else {
		echo '<span style="font-size:12px;"><b>Försäljningsförtjänst: € '.number_format(($salestotal_SEK * 0.3),2).'</span><br/>';
		echo '<span style="font-size:12px;"><b>Belopp som kommer att faktureras: € '.number_format(($salestotal_SEK * 0.7),2).'</span><br/>';
	}	
	
	echo '<br/>[ <a href="javascript:print();" style="font-size:12px; color:black;">Skriv ut</a> ] ';

}

if (isset($_GET['testar'])) {

	if (!isset($_GET['p'])) {
		$page = 0;
	} else {
		$page = $_GET['p'];
	}
	echo '<br/><div class="clearfix">&nbsp;</div><h2 class="grid_12 clearfix">ORDERSAMMANSTÄLLNING</h2>';
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


if (isset($_GET['orders'])) {
	if (!isset($_GET['p'])) {
		$page = 0;
	} else {
		$page = $_GET['p'];
	}
	echo '<br/><div class="clearfix">&nbsp;</div><h2 class="grid_12 clearfix">Orderhantering</h2>';
	echo '<div class="clearfix">&nbsp;</div>';
	echo '<div class="grid_12"><a href="?orders">Nya ordrar</a> | <a href="?orders&sort=f_printed">Ordrar där amaryllis inte skrivits ut</a> | <a href="?orders&sort=total_printed">Helt utskrivna ordrar</a></div><div class="clearfix">&nbsp;</div><br/><br/>';
	echo '<table border=0 width="1000" cellpadding=0 cellspacing=0 class="tabell" >';
	echo '<tr><td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:14px;"><b>Order ID</b></td>';
	echo '<td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:14px;"><b>Namn</b></td>';
	echo '<td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:14px;"><b>Personnummer</b></td>';
	echo '<td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:14px;"><b>Orderdatum</b></td>';
	echo '<td valign="top" align="left" style="padding-left:10px; background-color:#DDDDDD;font-size:14px;"><b></b></td></tr>';
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
		if (isset($row['order_last_change_date'])) {
			echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:14px;"><b>'.substr($row['order_last_change_date'],0,10).'</b></span></td>';
		} else {
			echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:14px;"><b>'.substr($row['order_created_date'],0,10).'</b></span></td>';
		}
		$result3 = mysql_query("SELECT orders_id, product_art_id, SUM(quantity_of_product) FROM orders_content WHERE orders_id= ".$row['id']." GROUP BY product_art_id ORDER BY product_art_id");

		while ($row3 = mysql_fetch_array($result3)) {
			$result4 = mysql_query("SELECT * FROM products WHERE art_id = '".$row3['product_art_id']."'");
			$r4 = mysql_fetch_array($result4);
			if ($r4['art_category'] == "A") $a_available = 1;
			if ($r4['art_category'] == "L") $l_available = 1;
			//if ($r4['art_category'] == "L" || $r4['art_category'] == "T") $lt_available = 1; 
		}	
		echo '<td valign="top" align="left" '.$bgColor.'><span style="font-size:12px;"><b>';
		if ($row2['school_id']==0) {
			echo '<a target="_blank" href="?showorder&id='.$row['id'].'">Visa</a>';
		} else {
			echo '<a target="_blank" href="?showorder&inc_overview&id='.$row['id'].'">Skolöversikt</a> | <a target="_blank" href="?showorder&id='.$row['id'].'">Visa</a>';
		}
		if (isset($_GET['sort']) && $_GET['sort'] == "total_printed") {
		} else {
		if ($l_available == 1 && $a_available != 1) echo ' | <a target="_blank" href="?showorder&status=5&id='.$row['id'].'&cats=L">Skriv ut allt</a>';
		if ($l_available == 1 && $a_available == 1 ) echo ' | <a target="_blank" href="?showorder&status=3&id='.$row['id'].'&cats=L">Skriv ut lökar</a>';
		if ($a_available == 1) echo ' | <a target="_blank" href="?showorder&status=5&id='.$row['id'].'&cats=A">Skriv ut amaryllis</a>';
		if ($l_available == 1 && $a_available == 1) echo ' | <a target="_blank" href="?showorder&status=5&id='.$row['id'].'">Skriv ut allt</a>';
		echo ' | <a href="?editorder&editid='.$row['id'].'">Ändra</a>';
		}
		echo '</td></tr>';	
	}
			
	echo '</table>';
	if (!$page==0) echo '<a href="?orders&sort='.$_GET['sort'].'&p='.($page-1).'" class="button">Föregående sida</a> ';
	echo '<a href="?orders&sort='.$_GET['sort'].'&p='.($page+1).'" class="button">Nästa sida</a>';
	
}

?>

</div>		
</body>
</html>