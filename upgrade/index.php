<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */

/* Check whether the upgrading has been done */

if (file_exists('../lib/confs/upgradeConf.php')) {
    header('location:../');
} elseif (!file_exists('../../lib/confs/Conf.php')) {
    echo "You have put upgrader in wrong location. It should be under /newversion/upgrade/";
} else {

?>

<htm>
<head>
<title>Welcome to OrangeHRM 2.4.1 Upgrader</title>
<link type="text/css" rel="stylesheet" href="templates/upgraderStyle.css" />
</head>
<body>
<table width="400" border="0" cellspacing="20" cellpadding="5" align="center">
  <tr>
    <td><h1>Welcome to OrangeHRM 2.4.1 Upgrader</h1></td>
  </tr>
  <tr>
    <td><p>Please enter your admin login details to proceed with the upgrader</p>
	<form name="frmAdminLogin" method="post" action="UpgradeController.php">
	<table width="200" border="0" cellspacing="5" cellpadding="5" align="center">
  <tr>
    <td>Username:</td>
    <td><input type="text" name="txtUsername" size="20" /></td>
  </tr>
  <tr>
    <td>Password:</td>
    <td><input type="password" name="txtPassword" size="20" /></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" name="btnLogin" value="Login"  size="40" /></td>
    </tr>
</table>
<input type="hidden" name="hdnState" value="authAdmin" />
<input type="hidden" name="hdnInProgress" value="authAdmin" />
</form>
</td>
  </tr>
  <tr>
    <td>
	</td>
  </tr>
</table>
</body>
</html>
<?php } ?>
