<?php
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] !='Yes') {
	header('location:../');
}
?>

<htm>
<head>
<title>Database Value Changes</title>
<link type="text/css" rel="stylesheet" href="upgraderStyle.css" />
</head>
<body>
<table width="400" border="0" cellspacing="20" cellpadding="5" align="center">
  <tr>
    <td><h1>Database Value Changes</h1></td>
  </tr>
  <tr>
    <td><p id="message">OrangeHRM version 2.4.1 contains following database value changes. You can select or skip these changes.</p>
	<form name="frmDbValueChanges" method="post" action="UpgradeController.php">
	<table width="200" border="0" cellspacing="5" cellpadding="5" align="center">
  <tr>
    <td>Data encryption for Employee SSN Number and Employee Basic Salary. Note that encryption is done only at database level
	and values will be shown as they were in the application.</td>
    <td><input name="chkEncryption" type="checkbox" value="Enable"></td>
  </tr>
</table>
	</td>
  </tr>
  <tr>
    <td align="center">
	<input type="hidden" name="hdnState" value="dbValueChanges" />
	<input type="submit" name="btnSubmit" value="Continue"  size="40" />
	</form>
	</td>
  </tr>
</table>
</body>
</html>
