<h2>Congratulations! You Are Ready For Upgrade</h2>

<p>This upgrader will install an entire new instance of OrangeHRM 2.4.1 under /newversion/. It will import data from current OrangeHRM installation. But it won't affect
	existing installation in anyway. After a successful installation, you can login to new instance at /newversion/. If you are satisfied with new instance, you can delete old installation files, old database and move contents under newversion one level up. So that you can have OrangeHRM 2.4.1 in the same location where old installation was.<br /><br />
	If you are not satisfied with new installation, simply delete all the content under  /newversion/ and corresponding database.
</p>

<form name="frmUpgradeStart" method="post" action="./">
<input type="hidden" name="hdnState" value="upgradeStart" />
<input type="submit" name="btnSubmit" value="Continue"  size="40" />
</form>