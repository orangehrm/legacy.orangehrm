<?php
class Conf {

	var $smtphost;
	var $dbhost;
	var $dbport;
	var $dbname;
	var $dbuser;
	var $version;
        var $customVersion;

	function Conf() {

		$this->dbhost	= 'localhost';
		$this->dbport 	= '3306';
		$this->dbname	= 'hr_mysql';
		$this->dbuser	= 'root';
		$this->dbpass	= '';
		$this->version = '2.2';
                $this->customVersion = 'ors-0.1-alpha.5';


		$this->emailConfiguration = dirname(__FILE__).'mailConf.php';
		$this->errorLog =  realpath(dirname(__FILE__).'/../logs/').'/';
	}
}
?>
