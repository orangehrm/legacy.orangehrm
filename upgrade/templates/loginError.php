<?php
if (!isset($_SESSION['authorized'])) {
	header('location:../');
}
?>

<htm>
<head>
<title>Invalid Login</title>
<link type="text/css" rel="stylesheet" href="upgraderStyle.css" />
</head>
<body>
<table width="400" border="0" cellspacing="20" cellpadding="5" align="center">
  <tr>
    <td><h1>Invalid Login</h1></td>
  </tr>
  <tr>
    <td><p>Either you have entered username/password incorrect or you are not authorized to upgrade the system</p></td>
  </tr>
  <tr>
    <td align="center">
	<form name="frmInvalidLogin" method="post" action="UpgradeController.php">
	<input type="hidden" name="hdnState" value="invalidLogin" />
	<input type="submit" name="btnSubmit" value="Back"  size="40" />
	</form>
	</td>
  </tr>
</table>
</body>
</html>
