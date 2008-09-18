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
<style type="text/css">
#button {
	disply: none;
}
</style>
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
</table>
	</td>
  </tr>
  <tr>
    <td align="center">
	<form name="frmUpgraderFinished" method="post" action="UpgradeController.php">
	<input type="hidden" name="hdnState" value="" />
	<input type="submit" name="btnSubmit" value="Finish"  size="40" id="button" />
	</form>
	</td>
  </tr>
</table>
<script language="javascript" type="text/javascript">
locateConfFiles();
</script>
</body>
</html>
