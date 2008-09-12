<?php
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] !='Yes') {
	header('location:../');
}
?>

<htm>
<head>
<title>Data Incompatibilities Found!</title>
<link type="text/css" rel="stylesheet" href="upgraderStyle.css" />
</head>
<body>
<table width="400" border="0" cellspacing="20" cellpadding="5" align="center">
  <tr>
    <td><h1>Data Incompatibilities Found!</h1></td>
  </tr>
  <tr>
    <td><p>Upgrader found following data incompatibilities. Upgrading is not possible with these incompatibilities. Please corrrect them and re-run the upgrader.</p>
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
	<form name="frmDataError" method="post" action="UpgradeController.php">
	<input type="hidden" name="hdnState" value="dataError" />
	<input type="submit" name="btnSubmit" value="Back to OrangeHRM Home"  size="80" />
	</form>
	</td>
  </tr>
</table>
</body>
</html>
