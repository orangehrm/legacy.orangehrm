<?php
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] !='Yes') {
	header('location:../');
}
?>

<htm>
<head>
<title>Given Database Is Not Accessible</title>
<link type="text/css" rel="stylesheet" href="upgraderStyle.css" />
</head>
<body>
<table width="400" border="0" cellspacing="20" cellpadding="5" align="center">
  <tr>
    <td><h1>Given Database Is Not Accessible</h1></td>
  </tr>
  <tr>
    <td><p>Upgrader found following error in the given database. Please correct it and re-enter database name.</p>
	<div class="error">
	<?php 
	foreach ($upgrader->errorArray as $error) {
		echo "<li>$error</li>";
	}
	?>
	</div>
	</td>
  </tr>
  <tr>
    <td align="center">
	<form name="frmDbError" method="post" action="UpgradeController.php">
	<input type="hidden" name="hdnState" value="upgradeStart" />
	<input type="submit" name="btnSubmit" value="Back"  size="40" />
	</form>
	</td>
  </tr>
</table>
</body>
</html>
