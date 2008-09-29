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
    $oldTablesSqlPath = 'sql/2222tables.sql';
    $oldConstraintsSqlPath = 'sql/2222constraints.sql';
    $newTablesSqlPath = 'sql/2222to241newTables.sql';
    $newAlterSqlPath = 'sql/2222to241alterations.sql';
    $newDefaultDataSqlPath = 'sql/2222to241defaultData.sql';
    $upgrader = new Upgrade2222To241($oldConfObj);
} elseif ($oldVersion == '2.3') {
    $oldTablesSqlPath = 'sql/23tables.sql';
    $oldConstraintsSqlPath = 'sql/23constraints.sql';
    $newTablesSqlPath = 'sql/23to241newTables.sql';
    $newAlterSqlPath = 'sql/23to241alterations.sql';
    $newDefaultDataSqlPath = 'sql/23to241defaultData.sql';
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
			if ($upgrader->executeSql($oldTablesSqlPath, $dbName)) {
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

	case 'oldConstraints':
		$dbName = $_SESSION['newDb'];
		if ($upgrader->applyConstraints($oldConstraintsSqlPath, $dbName)) {
			require_once 'templates/newDbChanges.php';
		} else {
			require_once 'templates/constraintsError.php';
		}
		break;

	case 'newDbChanges':

		$action = $_REQUEST['action'];
		$dbName = $_SESSION['newDb'];

		switch ($action) {

		    case 'tables':
		    	if ($upgrader->createNewTables($newTablesSqlPath, $dbName)) {
					echo 'tablesYes';
		    	} else {
					echo 'tablesNo';
		    	}
		    	break;

		    case 'alter':
		    	if ($upgrader->applyDbAlterations($newAlterSqlPath, $dbName)) {
					echo 'alterYes';
		    	} else {
					echo 'alterNo';
		    	}
		    	break;

		    case 'store':
		    	if ($upgrader->storeDefaultData($newDefaultDataSqlPath, $dbName)) {
					echo 'storeYes';
		    	} else {
					echo 'storeNo';
		    	}
		    	break;

		}

		break;

	case 'dbValueChangeOption':
                if($oldVersion == '2.3'){
                  require_once 'templates/copyConfFiles.php';
                }elseif($oldVersion == '2.2.2.2'){
                  require_once 'templates/dbValueChanges.php';
                }
		break;

	case 'dbValueChanges':

		if (isset($_POST['chkEncryption']) && $_POST['chkEncryption'] == 'Enable') {
		    if ($upgrader->changeExistingData($_SESSION['newDb'])) {
				require_once 'templates/copyConfFiles.php';
		    } else {
		        require_once 'templates/dbValuesError.php';
		    }
		} else {
		    require_once 'templates/copyConfFiles.php';
		}

		break;

	case 'locateConfFiles':

		$action = $_REQUEST['action'];
		$dbName = $_SESSION['newDb'];

		switch ($action) {

		    case 'conf':
		    	if ($upgrader->createConfFile($dbName)) {
					echo 'confYes';
		    	} else {
					echo 'confNo';
		    	}
		    	break;

		    case 'upgrade':
		    	if ($upgrader->createUpgradeConfFile()) {
					echo 'upgradeYes';
		    	} else {
					echo 'upgradeNo';
		    	}
		    	break;

		    case 'mail':
		    	$filePath = '../../lib/confs/mailConf.php';
		    	if (file_exists($filePath)) {
			    	$newFilePath = '../lib/confs/mailConf.php';
			    	if ($upgrader->copyFile($filePath, $newFilePath)) {
						echo 'mailYes';
			    	} else {
						echo 'mailNo';
			    	}
		    	} else {
					echo 'mailNoFile';
		    	}
		    	break;

            case 'enckey':
		    	$filePath = '../../lib/confs/cryptokeys/key.ohrm';
		    	if (file_exists($filePath)) {
			    	$newFilePath = '../lib/confs/cryptokeys/key.ohrm';
			    	if ($upgrader->copyFile($filePath, $newFilePath)) {
						echo 'enckeyYes';
			    	} else {
						echo 'enckeyNo';
			    	}
		    	} else {
					echo 'enckeyNoFile';
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

	case 'upgradeFinish':
		session_destroy();
		header('location:../');
		break;

	case 'confError':
		$conf = '../lib/confs/Conf.php';
		$upgradeConf = '../lib/confs/upgradeConf.php';
		$mailConf ='../lib/confs/mailConf.php';
		if (file_exists($conf)) {
		    unlink($conf);
		}
		if (file_exists($upgradeConf)) {
		    unlink($upgradeConf);
		}
		if (file_exists($mailConf)) {
		    unlink($mailConf);
		}
		unset($_SESSION['authorized']);
		unset($_SESSION['inProgress']);
		header('location:./');
		break;

}

?>
