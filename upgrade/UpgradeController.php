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

require_once 'Authorize.php';

session_start();

/* This is set only at login */
if (isset($_POST['hdnInProgress'])) {
    $_SESSION['inProgress'] = true;
}

/* If login hasn't been accessed yet
if (!isset($_SESSION['inProgress']) || !isset($_POST['hdnState'])) {
    header('location:./');
}*/

/* Initializing Current Conf Object */
$oldConfObj = new Conf();
$oldVersion = $oldConfObj->version;

/* Loading relevant upgrader class */
function __autoload($class_name) {
    require_once $class_name . '.php';
}

/* Initializing Upgrader Object */
if ($oldVersion == '2.2.2.2') {
    $upgrader = new Upgrade2222To241($oldConfObj);
} elseif ($oldVersion == '2.3') {
    $upgrader = new Upgrade23To241($oldConfObj);
}

/* Checking whether upgrader support curret version */
$versionSupport = false;
if ($oldVersion == '2.2.2.2' || $oldVersion == '2.3') {
    $versionSupport = true;
}

$state = $_REQUEST['hdnState'];

switch ($state) {

	case 'authAdmin':
		$_SESSION['authorized'] = 'No';
		if (Authorize::authAdmin(trim($_POST['txtUsername']), trim($_POST['txtPassword']))) {
			$_SESSION['authorized'] = 'Yes';
		   	if ($versionSupport) {
				require_once 'templates/upgradeStart.php';
		   	} else {
		   	    require_once 'templates/versionError.php';
		   	}
		} else {
		    require_once 'templates/loginError.php';
		}
		break;

	case 'upgradeStart':
		if ($upgrader->isDataCompatible()) {
			require_once 'templates/dbInfo.php';
		} else {
			require_once 'templates/dataError.php';
		}
		break;

	case 'dbInfo':
		$dbName = mysql_real_escape_string(trim($_POST['newDbName']));
		if ($upgrader->isDatabaseAccessible($dbName)) {

			$sqlPath = 'sql/2222tables.sql';

			if ($upgrader->executeSql($sqlPath, $dbName)) {
				$_SESSION['newDb'] = $dbName;
				$tablesArray = $upgrader->getAllTables($dbName);
				require_once 'templates/dataImport.php';
			} else {
				require_once 'templates/tableCreationError.php';
			}

		} else {
			require_once 'templates/dbError.php';
		}
		break;

	case 'dataImport':

		$tableName = $_REQUEST['table'];
		if ($upgrader->importDataFromTable($tableName, $oldConfObj->dbname, $_SESSION['newDb'])) {
		    echo 'Yes-'.$tableName;
		} else {
			echo 'No-'.$tableName;
		}

		break;

	case 'newDbChanges':

		switch ($_POST['action']) {

		    case 'tables':
		    	if ($upgrader->createNewTables()) {
					echo 'tablesYes';
		    	} else {
					echo 'tablesNo';
		    	}
		    	break;

		    case 'alter':
		    	if ($upgrader->applyDbAlterations()) {
					echo 'alterYes';
		    	} else {
					echo 'alterNo';
		    	}
		    	break;

		    case 'store':
		    	if ($upgrader->storeDefaultData()) {
					echo 'storeYes';
		    	} else {
					echo 'storeNo';
		    	}
		    	break;

		}

		break;

	case 'invalidLogin':
		unset($_SESSION['authorized']);
		unset($_SESSION['inProgress']);
		header('location:./');
		break;

	case 'versionError':
		unset($_SESSION['authorized']);
		unset($_SESSION['inProgress']);
		header('location:../../');
		break;

	case 'dataError':
		unset($_SESSION['authorized']);
		unset($_SESSION['inProgress']);
		header('location:../../');
		break;


}

?>
