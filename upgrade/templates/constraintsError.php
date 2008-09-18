<?php
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] !='Yes') {
	header('location:../');
}
?>

<htm>
<head>
<title>Problems in Applying Cuurent Database Constraints</title>
<link type="text/css" rel="stylesheet" href="upgraderStyle.css" />
</head>
<body>
<table width="400" border="0" cellspacing="20" cellpadding="5" align="center">
  <tr>
    <td><h1>Problems in Applying Cuurent Database Constraints</h1></td>
  </tr>
  <tr>
    <td><p>Upgrader found errors when applying current database constraints. You may delete this database and start with a new database.</p>
	</td>
  </tr>
  <tr>
    <td align="center">
	<form name="frmConstraintError" method="post" action="UpgradeController.php">
	<input type="hidden" name="hdnState" value="upgradeStart" />
	<input type="submit" name="btnSubmit" value="Back"  size="40" />
	</form>
	</td>
  </tr>
</table>
</body>
</html>
