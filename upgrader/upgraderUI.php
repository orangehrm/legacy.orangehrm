<?php
session_start();

$cupath = realpath(dirname(__FILE__).'/../');

define('ROOT_PATH', $cupath);

if(isset($_SESSION['CONFDONE'])) {
	$currScreen = 10;
} else if(isset($_SESSION['RESTORING'])) {
	$currScreen = 9;
} else if (isset($_SESSION['SYSCHECK'])){ 
	$currScreen = 8;
} else if(isset($_SESSION['DOWNLOAD'])) {
	$currScreen = 7;
} else if(isset($_SESSION['LOCCONF'])) {
	$currScreen = 6;
} else if(isset($_SESSION['LOCCONFOPT'])) {
	$currScreen = 5;
} else if(isset($_SESSION['DBCONFOPT'])) {
	$currScreen = 4;
}else if(isset($_SESSION['DISCLAIMER'])) {
	$currScreen = 3;
} else if(isset($_SESSION['LICENSE'])) {
	$currScreen = 2;
} else if(isset($_SESSION['WELCOME'])) {
	$currScreen = 1;
} else $currScreen = 0;

if (isset($_SESSION['error'])) {
	$error = $_SESSION['error'];
}

if (isset($_SESSION['reqAccept'])) {
	$reqAccept = $_SESSION['reqAccept'];
}

$steps = array('welcome',
			   'license',
			   'disclaimer', 
			   'options', 
			   'database', 
			   'OrangeHRM 1.2', 
			   'Backup Data', 
			   'System Check', 
			   'Upload', 
			   'upgrading',
			   'registration');

$helpLink = array("#welcome", 
				  '#license',
				  '#disclaimer', 
				  '#options', 
				  "#DBCreation", 
				  "#old", 
				  "#backup", 
				  '#syscheck',
				  "#upload", 
				  '#upgrading',
				  "#registration");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>OrangeHRM Web Upgrader Wizard</title>
<link href="../favicon.ico" rel="icon" type="image/gif"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript">

function goToScreen(screenNo) {
	document.frmInstall.txtScreen.value = screenNo;
}

function cancel() {
	document.frmInstall.actionResponse.value  = 'CANCEL';
	document.frmInstall.submit();
}

function back() {
	document.frmInstall.actionResponse.value  = 'BACK';
	document.frmInstall.submit();
}

</script>
<link href="./style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="body">
  <a href="http://www.orangehrm.com"><img src="../themes/beyondT/pictures/orange3.png" alt="OrangeHRM" name="logo"  width="264" height="62" border="0" id="logo" style="margin-left: 10px;" title="OrangeHRM"></a>
<form name="frmInstall" action="../upgrade.php" method="POST" enctype="multipart/form-data">
<input type="hidden" name="txtScreen" value="<?php echo $currScreen?>">
<input type="hidden" name="actionResponse">

<table border="0" cellpadding="0" cellspacing="0">
  <tr>
<?php
	$tocome = '';
	for ($i=0; $i < count($steps); $i++) {
		if ($currScreen == $i) {
			$tabState = 'Active';
		} else {
			$tabState = 'Inactive';
		}
?>

    <td nowrap="nowrap" class="left_<?php echo $tabState?>">&nbsp;</td>
    <td nowrap="nowrap" class="middle_<?php echo $tabState.$tocome?>"><?php echo $steps[$i]?></td>
	<td nowrap="nowrap" class="right_<?php echo $tabState?>">&nbsp;</td>
	
    <?php
		if ($tabState == 'Active') {		
			$tocome = '_tocome';
		}
	} 
	?>
  </tr>
</table>
<a href="./guide/<?php echo $helpLink[$currScreen]?>" id="help" target="_blank">[Help ?]</a>
<?php
switch ($currScreen) {
	
	default :
	case 0 	: 	require(ROOT_PATH . '/upgrader/welcome.php'); break;
	case 1 	: 	require(ROOT_PATH . '/upgrader/license.php'); break;
	case 2 	: 	require(ROOT_PATH . '/upgrader/disclaimer.php'); break;
	case 3 	: 	require(ROOT_PATH . '/upgrader/options.php'); break;
	case 4 	: 	require(ROOT_PATH . '/upgrader/dbConfig.php'); break;
	case 5 	: 	require(ROOT_PATH . '/upgrader/backup/getConfLocation.php'); break;
	case 6 	: 	require(ROOT_PATH . '/upgrader/backup/downloadFile.php'); break;
	case 7 	: 	require(ROOT_PATH . '/upgrader/checkSystem.php'); break;
	case 8 	: 	require(ROOT_PATH . '/upgrader/restore/restoreData.php'); break;
	case 9 	: 	require(ROOT_PATH . '/upgrader/restore/processing.php'); break;
	case 10	: 	require(ROOT_PATH . '/upgrader/registration.php'); break;
}
?>

</form>
<div id="footer"><a href="http://www.orangehrm.com" target="_blank" tabindex="37">OrangeHRM</a> Web Upgrader Wizard ver 0.2 &copy; OrangeHRM Inc. 2005 - 2007 All rights reserved. </div>
</div>
</body>
</html>