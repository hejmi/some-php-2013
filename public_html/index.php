<?php 
//session_destroy();
//session_start();
$ishome = 1; 

//SPRÅK
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $_SITE_TITLE; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
	<!-- Stylesheets -->
	<link rel="stylesheet" href="css/reset.css" />
	<link rel="stylesheet" href="css/styles.css" />
	
	<!-- Scripts -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/sv_SE/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery.roundabout-1.0.min.js"></script> 
	<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
	<script type="text/javascript">		
		$(document).ready(function() { //Start up our Featured Project Carosuel
			$('#featured ul').roundabout({
				easing: 'easeOutInCirc',
				duration: 600
			});
		});
      
      function validateForm()
      {

	  	if (document.create_account_form.cust_type[0].checked)
	  	{
	  		if (document.forms["create_account_form"]["school_name"].value.length<3)
	  		{
	  			alert("<?php echo $_ERROR_SCHOOL_NAME; ?>");
	  			return false;
	  		}
	  		if (document.forms["create_account_form"]["school_class"].value.length<1)
	  		{
	  			alert("<?php echo $_ERROR_SCHOOL_CLASS; ?>");
	  			return false;
	  		}
	  		if (document.forms["create_account_form"]["school_contact"].value.length<3)
	  		{
	  			alert("<?php echo $_ERROR_SCHOOL_CONTACT; ?>");
	  			return false;
	  		}
	  		if (document.forms["create_account_form"]["school_shipping_to"].value.length<3)
	  		{
	  			alert("<?php echo $_ERROR_SCHOOL_SHIP_TO; ?>");
	  			return false;
	  		}
	  		if (document.forms["create_account_form"]["school_shipping_address"].value.length<3)
	  		{
	  			alert("<?php echo $_ERROR_SCHOOL_ADDRESS; ?>");
	  			return false;
	  		}
	  		if (document.forms["create_account_form"]["school_shipping_postal"].value.length<3)
	  		{
	  			alert("<?php echo $_ERROR_SCHOOL_POSTAL; ?>");
	  			return false;
	  		}
	  		if (document.forms["create_account_form"]["school_shipping_city"].value.length<3)
	  		{
	  			alert("<?php echo $_ERROR_SCHOOL_CITY; ?>");
	  			return false;
	  		}
	  		
	  	} else {

	  		if (document.forms["create_account_form"]["youth_name"].value.length<2)
	  		{
	  			alert("<?php echo $_ERROR_YOUTH_NAME; ?>");
	  			return false;
	  		}
	  		if (document.forms["create_account_form"]["youth_lastname"].value.length<2)
	  		{
	  			alert("<?php echo $_ERROR_YOUTH_NAME; ?>");
	  			return false;
	  		}
	  		if (document.forms["create_account_form"]["youth_personalid"].value.length<12)
	  		{
	  			alert("<?php echo $_ERROR_PERSONALID; ?>");
	  			return false;
	  		}
	  		if (document.forms["create_account_form"]["youth_contact"].value.length<3)
	  		{
	  			alert("<?php echo $_ERROR_YOUTH_CONTACT; ?>");
	  			return false;
	  		}
	  		if (document.forms["create_account_form"]["youth_address"].value.length<3)
	  		{
	  			alert("<?php echo $_ERROR_SCHOOL_ADDRESS; ?>");
	  			return false;
	  		}
	  		if (document.forms["create_account_form"]["youth_postal"].value.length<3)
	  		{
	  			alert("<?php echo $_ERROR_SCHOOL_POSTAL; ?>");
	  			return false;
	  		}
	  		if (document.forms["create_account_form"]["youth_city"].value.length<3)
	  		{
	  			alert("<?php echo $_ERROR_SCHOOL_CITY; ?>");
	  			return false;
	  		}
  		
	  	}
	  	
	  	var x=document.forms["create_account_form"]["email"].value
	  	var atpos=x.indexOf("@");
		var dotpos=x.lastIndexOf(".");
		if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length)
	  	{
	  		alert("<?php echo $_ERROR_EMAIL; ?>");
	  		return false;
	  	}
	  	
	  	if (document.forms["create_account_form"]["mobile"].value.length<3)
	  	{
	  		alert("<?php echo $_ERROR_MOBILE; ?>");
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
   </SCRIPT>  

	<!--[if IE 6]>
	<script src="js/DD_belatedPNG_0.0.8a-min.js"></script>
	<script>
	  /* EXAMPLE */
	  DD_belatedPNG.fix('.button');
	  
	  /* string argument can be any CSS selector */
	  /* .png_bg example is unnecessary */
	  /* change it to what suits you! */
	</script>
	
				<li><a href="?"><span class="meta"><?php echo $_TOP_MENU_SORTS; ?></span><br /><?php echo $_TOP_MENU_SORTS_BOLD; ?></a></li>
			<li><a href="?"><span class="meta"><?php echo $_TOP_MENU_GUIDE; ?></span><br /><?php echo $_TOP_MENU_GUIDE_BOLD; ?></a></li>
	
	
	<![endif]-->
</head>

<body>
	<div id="wrapper" class="container_12 clearfix">

		<!-- Text Logo -->
		<h1 id="logo" class="grid_2"><img src="images/logo_<?php echo $_GET['lng'];?>.png"></h1>
		
		<div class="grid_2">
		<!--<a href="?<?php echo $_SERVER['QUERY_STRING'];?>&lng=sv"><img src="images/flag_sv.png" width="25" style="border:1px solid gray;"></a> <a href="?<?php echo $_SERVER['QUERY_STRING'];?>&lng=fi"><img src="images/flag_fi.png" width="25" style="border:1px solid gray;"></a>-->
		</div>
		
		<!-- Navigation Menu -->
		<ul id="navigation" class="grid_8">
			<li><a href="index.php?contact" <?php if(isset($_GET['contact'])) { $ishome = 0; echo 'class="current"';} ?>><span class="meta"><?php echo $_TOP_MENU_CONTACT; ?></span><br /><?php echo $_TOP_MENU_CONTACT_BOLD; ?></a></li>
			<li><a href="index.php?createnewaccount" <?php if(isset($_GET['createnewaccount'])) { $ishome = 0; echo 'class="current"';} ?>"><span class="meta"><?php echo $_TOP_MENU_ORDER; ?></span><br /><?php echo $_TOP_MENU_ORDER_BOLD; ?></a></li>
			<li><a href="index.php?catalog" <?php if(isset($_GET['catalog'])) { $ishome = 0; echo 'class="current"';} ?>><span class="meta"><?php echo $_TOP_MENU_CATALOG; ?></span><br /><?php echo $_TOP_MENU_CATALOG_BOLD; ?></a></li>
			<li><a href="index.php" <?php if($ishome == 1) echo 'class="current"'; ?>><span class="meta"><?php echo $_TOP_MENU_HOME; ?></span><br /><?php echo $_TOP_MENU_HOME_BOLD; ?></a></li>
		</ul>
	
		<div class="hr grid_12">&nbsp;</div>
		<div class="clear"></div>

<?php
if (isset($_GET['forgotpassword'])) {
	$ishome = 0;
if (isset($_GET['sendpassword'])) {

include 'inloggad/db_settings.php';

	$result = mysql_query("SELECT customers.usrname, customers.passwd FROM customers_school, customers,customers_youth WHERE customers_youth.customer_id = customers.id AND customers_youth.email = '".$_POST['email']."' OR customers_school.customer_id = customers.id AND  customers_school.email = '".$_POST['email']."' GROUP BY customers.usrname");
	$row = mysql_fetch_array($result);
	$emailexist = mysql_num_rows($result);
	
	if ($emailexist==0) {
		echo '<h2 class="grid_12 caption clearfix" style="color:darkred;">'.$_EMAIL_NOT_EXIST.'</h2>';
	} else {
	
		$to = $_POST['email'];
		$subject = utf8_decode($_EMAIL_SUBJECT);
		$body = utf8_decode($_EMAIL_BODY_START) . "\r\n";
		$body .= utf8_decode($_EMAIL_BODY_USERNAME). ': '.$row['usrname'] . "\n";
		$body .= utf8_decode($_EMAIL_BODY_PASSWORD). ': '.$row['passwd'];
	
		if (mail($to, $subject, $body, "From: noreply@emilstradgard.se")) 
  		{
   			echo('<h2 class="grid_12 caption clearfix" style="color:darkgreen;">'.$_PASSWORD_SENT.'</h2>');
  		} else {
   			echo('<h2 class="grid_12 caption clearfix" style="color:darkred;">Ett fel uppstod, vänligen försök igen.</h2>');
  		}
	}
}

?>
<!-- Caption Line -->
		<h2 class="grid_12 caption clearfix"><?php echo $_FORGOT_PASSWORD_TITLE; ?></h2>
		
		<div class="hr grid_12 clearfix">&nbsp;</div>

		<!-- Column 1 / Content -->
		<div class="grid_8">
			<!-- Contact Form -->
			<form action='index.php?forgotpassword&sendpassword' method='post' id='contact_form'>
					<h3><?php echo $_FORGOT_PASSWORD_EMAIL; ?></h3>
				<div class="hr dotted clearfix">&nbsp;</div>
				<ul>						
					<li class="clearfix"> 
						<label for="email"><?php echo $_SEND_FORM_YOUR_EMAIL; ?></label>
						<input type='text' name='email' id='email' />
						<div class="clear"></div>
						<p id='email_error' class='error'><?php echo $_SEND_FORM_YOUR_EMAIL_M; ?></p>
					</li> 
					<li class="clearfix"> 
					<div id="button">
					<input type='submit' id='send_message' class="button" value='<?php echo $_FORGOT_PASSWORD_SUBMIT; ?>' />
					</div>
					</li> 
				</ul> 
			</form>  
		</div>
		
		<!-- Column 2 / Sidebar -->
		<div class="grid_4 contact">
			
			<!-- Adress and Phone Details -->
			<h4><?php echo $_ADDRESS_PHONE; ?></h4> 
			<div class="hr dotted clearfix">&nbsp;</div>
			<ul> 
				<li> 
					<strong><?php echo $_COMPANY_NAME; ?></strong><br /> 
					<?php echo $_COMPANY_ADDRESS; ?><br /> 
					<?php echo $_COMPANY_POSTAL; ?><br /> 
					<?php echo $_COMPANY_COUNTRY; ?><br /><br /> 
				</li> 
				<li><?php echo $_COMPANY_PHONE; ?></li>
				<li><?php echo $_COMPANY_PHONE_HOURS; ?></li> 
			</ul> 
			
			<!-- Email Addresses -->
			<h4><?php echo $_COMPANY_EMAIL_TITLE; ?></h4> 
			<div class="hr dotted clearfix">&nbsp;</div>
			<ul> 
				<li><?php echo $_COMPANY_EMAIL_DESC; ?> - <a href="mailto:<?php echo $_COMPANY_EMAIL; ?>"><?php echo $_COMPANY_EMAIL; ?></a></li> 
			</ul> 

		</div>
<?php
}		

if (isset($_GET['contact'])) {
	
if (isset($_GET['sendmail'])) {
  
if (mail( "kundtjanst@emilstradgard.se", "Webbfråga", $_POST['name'].", ".$_POST['email']." : ".$_POST['message'], "From: noreply@emilstradgard.se" )) {
   echo('<h2 class="grid_12 caption clearfix" style="color:darkgreen;">Tack! Ditt meddelande skickades till oss.</h2>');
  } else {
   echo('<h2 class="grid_12 caption clearfix" style="color:darkred;">Ett fel uppstod, vänligen försök igen.</h2>');
  }
}

?>
<!-- Caption Line -->
		<h2 class="grid_12 caption clearfix"><?php echo $_CONTACT_US_TITLE; ?></h2>
		
		<div class="hr grid_12 clearfix">&nbsp;</div>

		<!-- Column 1 / Content -->
		<div class="grid_8">
			<p><?php echo $_CONTACT_US_SUBTITLE; ?></p>
			<!-- Contact Form -->
			<form action='index.php?contact&sendmail' method='post' id='contact_form'>
					<h3><?php echo $_SEND_FORM_TITLE; ?></h3>
				<div class="hr dotted clearfix">&nbsp;</div>
				<ul>						
					<li class="clearfix"> 
						<label for="name"><?php echo $_SEND_FORM_YOUR_NAME; ?></label>
						<input type='text' name='name' id='name' />
						<div class="clear"></div>
						<p id='name_error' class='error'><?php echo $_SEND_FORM_YOUR_NAME_M; ?></p>
					</li> 
					<li class="clearfix"> 
						<label for="email"><?php echo $_SEND_FORM_YOUR_EMAIL; ?></label>
						<input type='text' name='email' id='email' />
						<div class="clear"></div>
						<p id='email_error' class='error'><?php echo $_SEND_FORM_YOUR_EMAIL_M; ?></p>
					</li> 
					<li class="clearfix"> 
						<label for="message"><?php echo $_SEND_FORM_YOUR_MESSAGE; ?></label>
						<textarea name='message' id='message' rows="30" cols="30"></textarea>
						<div class="clear"></div>
						<p id='message_error' class='error'><?php echo $_SEND_FORM_YOUR_MESSAGE_M; ?></p>
					</li> 
					<li class="clearfix"> 
					<div id="button">
					<input type='submit' id='send_message' class="button" value='<?php echo $_SEND_FORM_SUBMIT; ?>' />
					</div>
					</li> 
				</ul> 
			</form>  
		</div>
		
		<!-- Column 2 / Sidebar -->
		<div class="grid_4 contact">
		
		<h4>Försäljningsvillkor</h4>
			<div class="hr dotted clearfix">&nbsp;</div>
			<ul>
			<li><a href="pdf/forsaljning.pdf" target="_blank">Klicka här för att se försäljningsvillkoren</a></li>
			</ul>
			</br>
		
			<!-- Adress and Phone Details -->
			<h4><?php echo $_ADDRESS_PHONE; ?></h4> 
			<div class="hr dotted clearfix">&nbsp;</div>
			<ul> 
				<li> 
					<strong><?php echo $_COMPANY_NAME; ?></strong><br /> 
					<?php echo $_COMPANY_ADDRESS; ?><br /> 
					<?php echo $_COMPANY_POSTAL; ?><br /> 
					<?php echo $_COMPANY_COUNTRY; ?><br /><br /> 
				</li> 
				<li><?php echo $_COMPANY_PHONE; ?></li>
				<li><?php echo $_COMPANY_PHONE_HOURS; ?></li> 
			</ul> 
			
			<!-- Email Addresses -->
			<h4><?php echo $_COMPANY_EMAIL_TITLE; ?></h4> 
			<div class="hr dotted clearfix">&nbsp;</div>
			<ul> 
				<li><?php echo $_COMPANY_EMAIL_DESC; ?> - <a href="mailto:<?php echo $_COMPANY_EMAIL; ?>"><?php echo $_COMPANY_EMAIL; ?></a></li> 
			</ul> 
			
			<!-- Social Profile Links 
			<h4></h4> 
			<div class="hr dotted clearfix">&nbsp;</div>
			<ul> 
				<li class="float"><a href="#"><img alt="" src="images/twitter.png" title="Twitter" /></a></li> 
				<li class="float"><a href="#"><img alt="" src="images/facebook.png" title="Facebook" /></a></li> 
				<li class="float"><a href="#"><img alt="" src="images/stumbleupon.png" title="StumbleUpon" /></a></li> 
				<li class="float"><a href="#"><img alt="" src="images/flickr.png" title="Flickr" /></a></li> 
				<li class="float"><a href="#"><img alt="" src="images/delicious.png" title="Delicious" /></a></li> 
			</ul> 
			-->
		</div>
<?php
}

if (isset($_GET['createnewaccount'])) {
	$ishome=0;

	if (isset($_GET['create'])) {
		
		function createRandomPassword() {
		    $chars = "abcdefghijkmnpqrstuvwxyz123456789";
			srand((double)microtime()*1000000);
			$i = 0;
			$pass = '' ;
			while ($i <= 7) {
				$num = rand() % 33;
				$tmp = substr($chars, $num, 1);
				$pass = $pass . $tmp;
				$i++;
			}
			return $pass;
		}

		
		if ($_POST['cust_type'] == "school") {
			$_name = $_POST['school_name'];
			$_class = $_POST['school_class'];
			$_contact = $_POST['school_contact'];
			$_shipping_to = $_POST['school_shipping_to'];
			$_shipping_address = $_POST['school_shipping_address'];
			$_shipping_postal = $_POST['school_shipping_postal'];
			if ($_POST['school_shipping_country']=="1" || $_POST['school_shipping_country']=="Sverige") { 
				$_shipping_country = 1;
			} else {
				$_shipping_country = 2;
			}
			$_shipping_city = $_POST['school_shipping_city'];
			$_mobile = $_POST['mobile']; 
			$_email = $_POST['email'];
			
			include 'inloggad/db_settings.php';

			$result = mysql_query("SELECT * FROM customers_school WHERE class_name = '".$_class."' AND school_name = '".$_name."'");
			$row = mysql_fetch_array($result);
			if($_name == $row['school_name'] && $_class == $row['class_name']) {
				echo "<h6 style='color:red;' class='grid_12 caption clearfix'>".$_ERROR_CLASS_EXIST."</p>";
			} else {
				$result = mysql_query("SELECT * FROM customers_school ORDER BY id DESC LIMIT 1");
				$row = mysql_fetch_array($result);
				$password = createRandomPassword();
				$username = date("Y").($row['id']+1);
				
				mysql_query("INSERT INTO customers (customer_type, usrname, passwd) VALUES (2,'".$username."','".$password."')") or die (mysql_error());
				$getlastresult = mysql_query("SELECT * FROM customers WHERE usrname = '".$username."'");
				$getlast = mysql_fetch_array($getlastresult);
				$customer_id = $getlast['id'];
				mysql_query("SET NAMES 'utf8'") or die(mysql_error());
				mysql_query("SET CHARACTER SET 'utf8'") or die(mysql_error()); 
				mysql_query("INSERT INTO customers_school (customer_id, school_name, class_name, contactperson, shipping_to, address, postal, city, country_id, phone, email) VALUES (".$customer_id.", '".$_name."', '".$_class."', '".$_contact."', '".$_shipping_to."', '".$_shipping_address."', '".$_shipping_postal."', '".$_shipping_city."', ".$_shipping_country.", '".$_mobile."', '".$_email."')") or exit (mysql_error());
				echo "<h6 style='color:green;' class='grid_12 caption clearfix'>".$_REGISTRATION_DONE."</p>";	
				
				$to = $_POST['email'];
				$subject = $_ACCOUNT_CREATED_MAIL_TITLE;
				$body = $_ACCOUNT_CREATED_MAIL . "\r\n";
				$body .= $_ACCOUNT_CREATED_MAIL1. ': '.$username. "\n";
				$body .= $_ACCOUNT_CREATED_MAIL2. ': '.$password;
	
				mail($to, $subject, $body, "From: noreply@emilstradgard.se") ;						
			}
			
			
		} 
		
		if ($_POST['cust_type'] == "youth") {
			
			$_forename = $_POST['youth_name'];
			$_lastname = $_POST['youth_lastname'];
			$_personalid = strtoupper($_POST['youth_personalid']);
			$_contact = $_POST['youth_contact'];
			$_shipping_address = $_POST['youth_address'];
			$_shipping_postal = $_POST['youth_postal'];
			if ($_POST['youth_country']=="1" || $_POST['youth_country']=="Sverige") { 
				$_shipping_country = 1;
			} else {
				$_shipping_country = 2;
			}
			$_shipping_city = $_POST['youth_city'];
			$_mobile = $_POST['mobile']; 
			$_email = $_POST['email'];
			
			include 'inloggad/db_settings.php';

			$result = mysql_query("SELECT * FROM customers_youth WHERE personal_id = '".$_personalid."'") or die(mysql_error());
			$row = mysql_fetch_array($result);
			if($_personalid == $row['personal_id']) {
				echo "<h6 style='color:red;' class='grid_12 caption clearfix'>".$_ERROR_YOUTH_EXIST."</p>";
			} else {
				$password = createRandomPassword();
				$username = $_personalid;
								
				mysql_query("INSERT INTO customers (customer_type, usrname, passwd) VALUES (1,'".$username."','".$password."')");
				$getlastresult = mysql_query("SELECT * FROM customers WHERE usrname = '".$username."'");
				$getlast = mysql_fetch_array($getlastresult);
				$customer_id = $getlast['id'];
				mysql_query("SET NAMES 'utf8'") or die(mysql_error());
				mysql_query("SET CHARACTER SET 'utf8'") or die(mysql_error()); 
				mysql_query("INSERT INTO customers_youth (personal_id, customer_id, school_id, contact_person, forename, lastname, address, postal, city, country_id, phone, email) VALUES ('".$_personalid."', ".$customer_id.", 0, '".$_contact."', '".$_forename."', '".$_lastname."', '".$_shipping_address."', '".$_shipping_postal."', '".$_shipping_city."', ".$_shipping_country.", '".$_mobile."', '".$_email."')") or die(mysql_error());
				echo "<h6 style='color:green;' class='grid_12 caption clearfix'>".$_REGISTRATION_DONE."</p>";	
				
				$to = $_POST['email'];
				$subject = $_ACCOUNT_CREATED_MAIL_TITLE;
				$body = $_ACCOUNT_CREATED_MAIL . "\r\n";
				$body .= $_ACCOUNT_CREATED_MAIL1. ': '.$username. "\n";
				$body .= $_ACCOUNT_CREATED_MAIL2. ': '.$password;
	
				mail($to, $subject, $body, "From: noreply@emilstradgard.se") ;						
			}
			
			

			
		}
	}
?>

<script language="JavaScript">
function layerShow(id, visibility) {
document.getElementById(id).style.display = visibility;
}
layerShow('newSchool','inline');
document.getElementById('radio_school').checked = true;
</script>				
			<h2 class="grid_12 caption clearfix"><?php echo $_ACCOUNT_HEADER;?></h2>
			<div class="hr grid_12 clearfix">&nbsp;</div>
            
            <div class="grid_8">
            <form method="post" action="index.php?createnewaccount&create" id="contact_form" name="create_account_form" onsubmit="return validateForm()">
            <h3><?php echo $_ACCOUNT_TEXT; ?></h3>
            <p><?php echo $_ACCOUNT_OBLI; ?></p>
            <div class="hr dotted clearfix">&nbsp;</div>
            <h6><?php echo $_CORDER_WE_ARE; ?></h6>
            <p>
            <input type="radio" name="cust_type" id="radio_school" value="school" onChange="layerShow('newSchool','inline');layerShow('newYouth','none');layerShow('newSchool','inline');layerShow('newYouth','none')" checked> <?php echo $_SCHOOL_CLASS; ?>
            <input type="radio" name="cust_type" id="radio_youth" value="youth" onChange="layerShow('newSchool','none');layerShow('newYouth','inline');layerShow('newSchool','none');layerShow('newYouth','inline')"> <?php echo $_YOUTH; ?>
            </p>
            <div id="newSchool">
            <table border="0">
            	<tr><td>
            		<?php echo $_SCHOOL;?>:<br/> <input type="text" name="school_name" size="40">
            	</td><td style="padding-left:4px;">
            		<?php echo $_CLASS;?>:<br/> <input type="text" name="school_class" size="10">
            	</td><td style="padding-left:4px;">
            		<?php echo $_A_CONTACT_PERSON;?>:<br/> <input type="text" name="school_contact" size="35">
            	</td></tr>
            </table>
            <h6><?php echo $_SHIPPING_TO_HEADER; ?></h6>
            <div class="hr dotted clearfix">&nbsp;</div>
            <table border="0">
            	<tr><td>
            		<?php echo $_SHIPPING_TO; ?>:<br/> <input type="text" name="school_shipping_to" size="40">
            	</td><td style="padding-left:4px;">
            		<?php echo $_ADDRESS;?>:<br/> <input type="text" name="school_shipping_address" size="40">
            	</td></tr>
            </table>
            <table border="0">
            	<tr><td>
            		<?php echo $_POSTAL_NO;?>:<br/> <input type="text" name="school_shipping_postal" size="10">
            	</td><td style="padding-left:4px;">
            		<?php echo $_CITY;?>:<br/> <input type="text" name="school_shipping_city" size="30">
            	</td><td style="padding-left:4px;">
            		<?php echo $_COUNTRY;?>:<br/> <select name="school_shipping_country"><option nvalue="1">Sverige</option></select>
            	</td></tr>
            </table>
            <h6><?php echo $_IMPORTANT_DATA_HEADER; ?></h6>
            <div class="hr dotted clearfix">&nbsp;</div>
            </div>

            
            <div id="newYouth" style="display:none;">
            <table border="0">
            	<tr><td>
            		<?php echo $_FNAME;?>:<br/> <input type="text" name="youth_name" size="15">
            	</td><td style="padding-left:4px;">
            		<?php echo $_LNAME;?>:<br/> <input type="text" name="youth_lastname" size="15">
            	</td><td style="padding-left:4px;">
            		<?php echo $_YOUTH_PERSONALID;?>:<br/> <input type="text" name="youth_personalid" size="25" MAXLENGTH=12 onkeyup="this.value=this.value.replace(/[^\d]/,'')"> 
            	</td><td style="padding-left:4px;">
            		<?php echo $_YOUTH_CONTACT;?>:<br/> <input type="text" name="youth_contact" size="30">
            	</td></tr>
            </table>
            <table border="0">
            	<tr><td>
            		<?php echo $_ADDRESS;?>:<br/> <input type="text" name="youth_address" size="80">
            	</td></tr>
            </table>
            <table border="0">
            	<tr><td>
            		<?php echo $_POSTAL_NO;?>:<br/> <input type="text" name="youth_postal" onkeyup="this.value=this.value.replace(/[^\d]/,'')" maxlength="5" size="10">
            	</td><td style="padding-left:4px;">
            		<?php echo $_CITY;?>:<br/> <input type="text" name="youth_city" size="30">
            	</td><td style="padding-left:4px;">
            		<?php echo $_COUNTRY;?>:<br/> <select name="youth_country"><option nvalue="1">Sverige</option></select>            	
            	</td></tr>
            </table>
            </div>
            <table border="0">
            	<tr><td>
            		<?php echo $_EMAIL_SHIPPING; ?>:<br/> <input type="text" name="email" size="40" >
            	</td><td style="padding-left:4px;">
            		<?php echo $_MOBILE_SHIPPING;?>:<br/> <input type="text" name="mobile" size="40" onkeyup="this.value=this.value.replace(/[^\d]/,'')">
            	</td></tr>
            </table>  
        

			<input type="submit" class="button" value="<?php echo $_SUBMIT_REGISTRATION;?>"><br/><br/>&nbsp;
						</p>
            </form>
            </div>
            		<!-- Column 2 / Sidebar -->
		<div class="grid_4 contact">

			<h4>Försäljningsvillkor</h4>
			<div class="hr dotted clearfix">&nbsp;</div>
			<ul>
			<li><a href="pdf/forsaljning.pdf" target="_blank">Klicka här för att se försäljningsvillkoren</a></li>
			</ul>
			</br>

			<!-- Adress and Phone Details -->
			<h4><?php echo $_ADDRESS_PHONE; ?></h4> 
			<div class="hr dotted clearfix">&nbsp;</div>
			<ul> 
				<li> 
					<strong><?php echo $_COMPANY_NAME; ?></strong><br /> 
					<?php echo $_COMPANY_ADDRESS; ?><br /> 
					<?php echo $_COMPANY_POSTAL; ?><br /> 
					<?php echo $_COMPANY_COUNTRY; ?><br /><br /> 
				</li> 
				<li><?php echo $_COMPANY_PHONE; ?></li>
				<li><?php echo $_COMPANY_PHONE_HOURS; ?></li> 
			</ul> 
			
			<!-- Email Addresses -->
			<h4><?php echo $_COMPANY_EMAIL_TITLE; ?></h4> 
			<div class="hr dotted clearfix">&nbsp;</div>
			<ul> 
				<li><?php echo $_COMPANY_EMAIL_DESC; ?> - <a href="mailto:<?php echo $_COMPANY_EMAIL; ?>"><?php echo $_COMPANY_EMAIL; ?></a></li> 
			</ul> 
			
			<!-- Social Profile Links 
			<h4></h4> 
			<div class="hr dotted clearfix">&nbsp;</div>
			<ul> 
				<li class="float"><a href="#"><img alt="" src="images/twitter.png" title="Twitter" /></a></li> 
				<li class="float"><a href="#"><img alt="" src="images/facebook.png" title="Facebook" /></a></li> 
				<li class="float"><a href="#"><img alt="" src="images/stumbleupon.png" title="StumbleUpon" /></a></li> 
				<li class="float"><a href="#"><img alt="" src="images/flickr.png" title="Flickr" /></a></li> 
				<li class="float"><a href="#"><img alt="" src="images/delicious.png" title="Delicious" /></a></li> 
			</ul> 
			-->
		</div>


<?php
}




if (isset($_GET['catalog'])) {

	if (isset($_GET['catalogordered'])) {
		if ($_POST['Skola'] != "") {
		$body = $_POST['Skola'].", klass: ".$_POST['Klass']."\r\nAtt: ".$_POST['Kontaktperson']."\r\n".$_POST['Adress']."\r\n".$_POST['Postnr']." ".$_POST['Ort']."\r\n\r\nVi vill ha ".$_POST['Antalkataloger']."st kataloger och ".$_POST['Antalordersedlar']."st ordersedlar";
		} else {
		$body = $_POST['Namn']."\r\n".$_POST['Adress']."\r\n".$_POST['Postnr']." ".$_POST['Ort']."\r\n\r\nJag vill ha katalog: ".$_POST['Katalog']." och ordersedel: ".$_POST['Ordersedel'];
		}
	if (mail("order@emilstradgard.se", "Katalog", $body, "From: noreply@emilstradgard.se" )) {
   		echo('<h2 class="grid_12 caption clearfix" style="color:darkgreen;">'.$_CATALOG_ORDER_THANKS.'</h2>');
  	} else {
   		echo('<h2 class="grid_12 caption clearfix" style="color:darkred;">'.$_CATALOG_ORDER_ERROR.'</h2>');
    }

	}
?>

<script language="JavaScript">
function layerShow(id, visibility) {
document.getElementById(id).style.display = visibility;
}
layerShow('SchoolOpt','inline');
document.getElementById('rB1').checked = true;
</script>

			<h2 class="grid_12 caption clearfix"><?php echo $_CATALOG_DESC_TEXT	;?></h2>
			<div class="hr grid_12 clearfix">&nbsp;</div>
            <h4 class="grid_12 caption clearfix"><?php echo $_CORDER_TEXT; ?></h4>
            <div class="grid_8">
            <form method="post" action="index.php?catalog&catalogordered" class="grid_12 caption clearfix">
            <h6><?php echo $_CORDER_WE_ARE; ?></h6>
            <p>
            <input type="radio" name="Kundtyp" id="rB1" value="Skola" onChange="layerShow('SchoolOptDl','inline');layerShow('YouthOptDl','none');layerShow('SchoolOpt','inline');layerShow('YouthOpt','none')" checked> <?php echo $_SCHOOL_CLASS; ?>
            <input type="radio" name="Kundtyp" id="rB2" value="Ungdom" onChange="layerShow('SchoolOptDl','none');layerShow('YouthOptDl','inline');layerShow('SchoolOpt','none');layerShow('YouthOpt','inline')"> <?php echo $_YOUTH; ?>
            </p>
            <div id="SchoolOpt">
            	<h6><?php echo $_CORDER_WE_WISH;?>:</h6><p> <input type="text" size="2" name="Antalkataloger"> <?php echo $_Q_CATALOG;?> &<br/>
            	<input type="text" size="2" name="Antalordersedlar"> <?php echo $_Q_ORDERFORM;?><br/><br/>
            	<?php echo $_SCHOOL;?><br/>
            	<input type="text" name="Skola"><br/>
            	<?php echo $_CLASS;?><br/>
            	<input type="text" name="Klass"><br/>
            	<?php echo $_CONTACT_PERSON;?><br/>
            	<input type="text" name="Kontaktperson"></p>
            </div>
            <div id="YouthOpt" style="display:none;">
            	<h6><?php echo $_CORDER_I_WISH;?></h6><p>
            	<input type="checkbox" name="Katalog" value="JA"> <?php echo $_CATALOG;?> <input type="checkbox" name="Ordersedel" value="JA"> <?php echo $_ORDERFROM;?><br/><br/>
 	        	<?php echo $_NAME;?><br/>
            	<input type="text" name="Namn"><br/>
            </div>
            <?php echo $_ADDRESS;?><br/>
            <input type="text" name="Adress"><br/>
            <?php echo $_POSTAL_NO;?><br/>
            <input type="text" name="Postnr"><br/>
            <?php echo $_CITY;?><br/>
            <input type="text" name="Ort"><br/><br/>
			<input type="submit" class="button" value="<?php echo $_SUBMIT_ORDER;?>"><br/>
						</p>
            </form>
            </div>
            		<!-- Column 2 / Sidebar -->
		<div class="grid_4 contact">
		
			<!-- Ordersedel -->
			<h4>Onlinekatalog</h4>
			<div class="hr dotted clearfix">&nbsp;</div>
			<ul>
			<li><a href="pdf/katalog2013-1.pdf" target="_blank">Klicka här för att se årets vårkatalog!</a></li>
			</ul>
			</br>
			
			<h4>Försäljningsvillkor</h4>
			<div class="hr dotted clearfix">&nbsp;</div>
			<ul>
			<li><a href="pdf/forsaljning.pdf" target="_blank">Klicka här för att se försäljningsvillkoren</a></li>
			</ul>
			</br>
			
			<h4><?php echo $_CORDER_DL_ORDERFORM;?></h4>
			<div class="hr dotted clearfix">&nbsp;</div>
			<div id="SchoolOptDl">
			  <ul>
			  	<li><a href="pdf/sv_skola_fram.pdf" target="_blank"><?php echo $_ORDERFORM_FRONT;?></a></li>
				<li><a href="pdf/sv_baksida.pdf" target="_blank"><?php echo $_ORDERFORM_BACK;?></a></li>
			  </ul>
			</div>
			<div id="YouthOptDl" style="display:none;">
			  <ul>
			    <li><a href="pdf/sv_ungd_fram.pdf" target="_blank"><?php echo $_ORDERFORM_FRONT;?></a></li>
			    <li><a href="pdf/sv_baksida.pdf" target="_blank"><?php echo $_ORDERFORM_BACK;?></a></li>
			  </ul>
			</div>
			

			<!-- Adress and Phone Details -->
			<h4><?php echo $_ADDRESS_PHONE; ?></h4> 
			<div class="hr dotted clearfix">&nbsp;</div>
			<ul> 
				<li> 
					<strong><?php echo $_COMPANY_NAME; ?></strong><br /> 
					<?php echo $_COMPANY_ADDRESS; ?><br /> 
					<?php echo $_COMPANY_POSTAL; ?><br /> 
					<?php echo $_COMPANY_COUNTRY; ?><br /><br /> 
				</li> 
				<li><?php echo $_COMPANY_PHONE; ?></li>
				<li><?php echo $_COMPANY_PHONE_HOURS; ?></li> 
			</ul> 
			
			<!-- Email Addresses -->
			<h4><?php echo $_COMPANY_EMAIL_TITLE; ?></h4> 
			<div class="hr dotted clearfix">&nbsp;</div>
			<ul> 
				<li><?php echo $_COMPANY_EMAIL_DESC; ?> - <a href="mailto:<?php echo $_COMPANY_EMAIL; ?>"><?php echo $_COMPANY_EMAIL; ?></a></li> 
			</ul> 
			
			<!-- Social Profile Links 
			<h4></h4> 
			<div class="hr dotted clearfix">&nbsp;</div>
			<ul> 
				<li class="float"><a href="#"><img alt="" src="images/twitter.png" title="Twitter" /></a></li> 
				<li class="float"><a href="#"><img alt="" src="images/facebook.png" title="Facebook" /></a></li> 
				<li class="float"><a href="#"><img alt="" src="images/stumbleupon.png" title="StumbleUpon" /></a></li> 
				<li class="float"><a href="#"><img alt="" src="images/flickr.png" title="Flickr" /></a></li> 
				<li class="float"><a href="#"><img alt="" src="images/delicious.png" title="Delicious" /></a></li> 
			</ul> 
			-->
		</div>


<?php
}
if ($ishome == 1) {
?>		<!-- Featured Image Slider -->
		<div id="featured" class="clearfix grid_12">
			<ul> 
				<li>


						<img src="images/front1.jpg" alt="" />

				</li>  
				<li>
					
						
						<img src="images/tulips.png" alt="" />
					
				</li>  
				<li>

						<img src="images/front2.jpg" alt="" />

				</li>
				<li>

						<img src="images/front3.jpg" alt="" />

				</li>    
				<!--<li>
					<a href="portfolio_single.html">
						<span>Read about this project</span>
						<img src="images/600x300.gif" alt="" />
					</a>
				</li>
				 -->
 
			</ul> 
		</div>
		<div class="hr grid_12 clearfix">&nbsp;</div>
			
		<!-- Caption Line -->

		<h2 class="grid_12 caption clearfix"><?php echo $_WELCOME_TEXT; ?><br/><br/></h2>
		
<?php 
}
?>		
		<div id="quicknav" class="grid_12">
			<span class="quicknavgrid_3 quicknav alpha">
					<h4 class="title "><?php echo $_LOG_IN_NOW;?></h4>
					<form method="post" action="inloggad/loginform.php?check">
					<p><?php echo $_USERNAME;?>:<br/><input type="text" name="user" id="user" size="20"><br/>
					<?php echo $_PASSWORD;?>:<br/><input type="password" name="password" id="password" size="20"></p>
					<p><input type="submit" class="button" value="<?php echo $_LOG_IN;?>"><br/><br/><a href="index.php?forgotpassword"><?php echo $_FORGOT_PASSWORD;?>.</a><br/><a href="index.php?createnewaccount"><?php echo $_NEW_ACCOUNT;?>.</a><br/></p>
					</form>
				
			</span>

			<span class="quicknavgrid_3 quicknav alpha">
					<h4 class="title "><?php echo $_CATALOG.' '.$_YEAR;?></h4>
					<p><a href="index.php?catalog">> <?php echo $_CATALOG_DESC_TEXT;?></a><br/>
					<a href="pdf/katalog2013-1.pdf" target="_blank">> Bläddra i katalogen online</a></p>
					
					<p style="text-align:center;"><img alt="" src="images/varkatalog2013.jpg" height="118" /></p>
				
			</span>
			<a class="quicknavgrid_3 quicknav" href="index.php?createnewaccount">
					<h4 class="title "><?php echo $_ORDER;?></h4>
					<p><?php echo $_ORDER_DESC_TEXT;?></p>
					<p style="text-align:center;"><img alt="" src="images/Blog_Artdesigner.lv.png" /></p>
				
			</a>
			<a class="quicknavgrid_3 quicknav" href="index.php?contact">
					<h4 class="title "><?php echo $_CONTACT_US;?></h4>
					<p><?php echo $_CONTACT_US_DESC_TEXT;?></p>
					<p style="text-align:center;"><img alt="" src="images/contactus.png" /></p>
			</a>
		</div>
		<div class="hr grid_12 clearfix">&nbsp;</div>
		<!-- Footer -->
		<p class="grid_12 footer clearfix">
			
			<a class="float right" href="#"><?php echo $_TO_TOP;?></a>
		<br/><br/></p>
		
	</div><!--end wrapper-->
</body>
</html>
