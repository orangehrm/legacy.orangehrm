<h2>Provide New Database Name</h2>

<p>Please provide the name of new database for upgrade. Note that new database should be an empty database and should be under database user <b>"<?php echo $oldConfObj->dbuser; ?>"</b> (database user of current database).</p>

<div style="text-align:center">
<form name="frmDbInfo" method="post" action="./">
<input type="hidden" name="hdnState" value="dbInfo" />
<input type="text" name="newDbName" size="40" /><br />
<input type="submit" name="btnSubmit" value="Continue"  size="40" />
</form>
</div>