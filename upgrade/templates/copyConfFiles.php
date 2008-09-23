<?php
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] !='Yes') {
	header('location:../');
}
?>

<htm>
<head>
<title>Locating Configuration Files</title>
<link type="text/css" rel="stylesheet" href="upgraderStyle.css" />
<script language="javascript" type="text/javascript" src="templates/locateConfFiles-ajax.js"></script>
</head>
<body>
<table width="400" border="0" cellspacing="20" cellpadding="5" align="center">
  <tr>
    <td><h1>Locating Configuration Files</h1></td>
  </tr>
  <tr>
    <td><p id="message">Upgrader is now locating configuration files.</p>
	<table width="200" border="0" cellspacing="5" cellpadding="5" align="center">
  <tr>
    <td>Creating Conf.php</td>
    <td><div id="conf"></div></td>
  </tr>
  <tr>
    <td>Creating upgradeConf.php</td>
    <td><div id="upgrade"></div></td>
  </tr>
  <tr>
    <td>Copying mailConf.php</td>
    <td><div id="mail"></div></td>
  </tr>
 <?php if($oldVersion == '2.3') { ?> 
  <tr>
    <td>Copying key.ohrm</td>
    <td><div id="enckey"></div></td>
  </tr>
  <?php } ?>
</table>
	</td>
  </tr>
  <tr>
    <td align="center">
	<form name="frmUpgraderFinished" method="post" action="UpgradeController.php">
	<input type="hidden" name="hdnState" value="" />
	<input type="submit" name="btnSubmit" value="Finish"  size="40" id="btnSubmit" style="display:none" />
	</form>
	</td>
  </tr>
</table>
<script language="javascript" type="text/javascript">
var actions = new Array();
<?php if($oldVersion == '2.3') { ?> 
	actions[0] = "conf";
	actions[1] = "upgrade";
	actions[2] = "mail";
	actions[3] = "enckey";
	
 <?php  } elseif ($oldVersion == '2.2.2.2') { ?>
 	actions[0] = "conf";
	actions[1] = "upgrade";
	actions[2] = "mail";
 <?php } ?>
setData(actions) ;
locateConfFiles();
</script>
</body>
</html>