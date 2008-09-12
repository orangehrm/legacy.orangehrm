<?php
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] !='Yes') {
	header('location:../');
}
?>

<htm>
<head>
<title>Provide New Database Name</title>
<link type="text/css" rel="stylesheet" href="upgraderStyle.css" />
</head>
<body>
<table width="400" border="0" cellspacing="20" cellpadding="5" align="center">
  <tr>
    <td><h1>Provide New Database Name</h1></td>
  </tr>
  <tr>
    <td><p>Please provide the name of new database for upgrade. Please note that new database should be under the same database user of current database.</p></td>
  </tr>
  <tr>
    <td align="center">
	<form name="frmDbInfo" method="post" action="UpgradeController.php">
	<input type="hidden" name="hdnState" value="dbInfo" />
	<input type="text" name="newDbName" size="40" /><br />
	<input type="submit" name="btnSubmit" value="Continue"  size="40" />
	</form>
	</td>
  </tr>
</table>
</body>
</html>
