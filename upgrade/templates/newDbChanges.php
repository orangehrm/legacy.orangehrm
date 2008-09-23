<?php
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] !='Yes') {
	header('location:../');
}
?>

<htm>
<head>
<title>New Database Schema Changes</title>
<link type="text/css" rel="stylesheet" href="upgraderStyle.css" />
<script language="javascript" type="text/javascript" src="templates/newDbChanges-ajax.js"></script>
</head>
<body>
<table width="400" border="0" cellspacing="20" cellpadding="5" align="center">
  <tr>
    <td><h1>New Database Schema Changes</h1></td>
  </tr>
  <tr>
    <td><p id="message">Upgrader is now applying database schema changes introduced by version 2.4.1</p>
	<table width="200" border="0" cellspacing="5" cellpadding="5" align="center">
  <tr>
    <td>Creating new database tables</td>
    <td><div id="tables"></div></td>
  </tr>
  <tr>
    <td>Applyinfg new database alterations</td>
    <td><div id="alter"></div></td>
  </tr>
  <tr>
    <td>Storing default data</td>
    <td><div id="store"></div></td>
  </tr>
</table>

	</td>
  </tr>
  <tr>
    <td align="center">
	<form name="frmNewDbChanges" method="post" action="UpgradeController.php">
	<input type="hidden" name="hdnState" value="" />
	<input type="submit" name="btnSubmit" value="Continue"  size="40" id="btnSubmit" style="display:none" />
	</form>
	</td>
  </tr>
</table>
<script language="javascript" type="text/javascript">
newDbChanges();
</script>
</body>
</html>