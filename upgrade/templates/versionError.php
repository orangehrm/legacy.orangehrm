<?php
if (!isset($_SESSION['authorized'])) {
	header('location:./');
}
?>

<htm>
<head>
<title>Current Version Is Not Supported</title>
<link type="text/css" rel="stylesheet" href="upgraderStyle.css" />
</head>
<body>
<table width="400" border="0" cellspacing="20" cellpadding="5" align="center">
  <tr>
    <td><h1>Sorry! Your Current OrangeHRM Version Is Not Supported</h1></td>
  </tr>
  <tr>
    <td><p>OrangeHRM 2.4.1 Upgrader supports only version 2.2.2.2 and 2.3. Your version is <?php echo $oldVersion; ?></p></td>
  </tr>
  <tr>
    <td align="center">
	<form name="frmVersionCheck" method="post" action="UpgradeController.php">
	<input type="hidden" name="hdnState" value="versionError" />
	<input type="submit" name="btnSubmit" value="Back to OrangeHRM Home"  size="80" />
	</form>
	</td>
  </tr>
</table>
</body>
</html>
