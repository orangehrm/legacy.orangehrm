<?php
if (!isset($_SESSION['authorized'])) {
	header('location:./');
}
?>

<htm>
<head>
<title>Congratulations! You Are Ready For Upgrade</title>
<link type="text/css" rel="stylesheet" href="upgraderStyle.css" />
</head>
<body>
<table width="400" border="0" cellspacing="20" cellpadding="5" align="center">
  <tr>
    <td><h1>Congratulations! You Are Ready For Upgrade</h1></td>
  </tr>
  <tr>
    <td><p>Write something here.</p></td>
  </tr>
  <tr>
    <td align="center">
	<form name="frmUpgradeStart" method="post" action="UpgradeController.php">
	<input type="hidden" name="hdnState" value="upgradeStart" />
	<input type="submit" name="btnSubmit" value="Continue"  size="40" />
	</form>
	</td>
  </tr>
</table>
</body>
</html>
