        1. Pre - Requirements
============================================================================
            web server such as apache, xampp, wamp etc
            PHPUnit 3.5.15 should be installed
            Java should be installed (atleast jdk 1.6.0 should be installed)

This URL will help you in configuring the PHPUnit in Windows or in Linux : ma
http://mailtoirs.blogspot.com/2011/05/configuring-phpunit-with-xampp-in.html


How to Install in Ubuntu 11.10

sudo apt-get install phpunit
sudo pear channel-discover pear.phpunit.de
sudo pear channel-discover components.ez.no
sudo pear channel-discover pear.symfony-project.com
sudo pear upgrade

*** Note: If you have PHPUnit already installed in your machine and if it is not 3.5.15, then do as the instructions given below. Because the test suite is not supporting with the latest version of PHPUnit. It supports with PHPUnit version 3.5.x

If you have installed 3.6 you can uninstall and install 3.5 by following below command.
Uninstallation of 3.6

pear uninstall phpunit/PHPUnit_Selenium
pear uninstall phpunit/PHPUnit
pear uninstall phpunit/DbUnit
pear uninstall phpunit/PHP_CodeCoverage
pear uninstall phpunit/PHP_Iterator
pear uninstall phpunit/PHPUnit_MockObject
pear uninstall phpunit/Text_Template
pear uninstall phpunit/PHP_Timer
pear uninstall phpunit/File_Iterator
pear uninstall pear.symfony-project.com/YAML

Installation of 3.5.15

pear update-channels
pear clear-cache
pear install pear.symfony-project.com/YAML-1.0.2
pear install phpunit/DbUnit-1.0.0
pear install phpunit/PHPUnit_Selenium-1.0.1
pear install phpunit/PHP_Timer-1.0.0
pear install phpunit/Text_Template-1.0.0
pear install phpunit/PHPUnit_MockObject-1.0.3
pear install phpunit/File_Iterator-1.2.3
pear install phpunit/PHP_CodeCoverage-1.0.2
pear install phpunit/DbUnit-1.0.0
pear install phpunit/PHPUnit-3.5.15


Change the value of the memory_limit as -1 in the php.ini file because the memory consumption of the test suite is more than 1GB. Also change the value of the mysql.connect_timeout as -1 in the php.ini file. Uncomment the extension=php_curl.dll in the php.ini file, because the webdriver (Selenium2) needs the functionality of the curl library.
   
If curl extension is not there u can install it by;
sudo apt-get install php5-curl


       2. Install the product
============================================================================
1. Take a SVN Chekout and install the product

Take the SVN chechkout from : https://orangehrm.svn.sourceforge.net/svnroot/orangehrm/branches/2012-ui 
Give Required permission to chechkout folder
Go inside *\symfony folder and run php symfony cc, php symfony orangehrm:publish-assets, php symfony doctrine:build-model
Then install the application by opening the browser and typing “http://localhost/*”.
 
After installing the product, go to “*\symfony\config\databases.yml” and remove the test: section.


       3. Configure Automation suite
============================================================================

2. Change Values as mentioned.
After the checkout is finished, change the values in the “*\symfony\plugins\orangehrmFunctionalTestPlugin\config\FunctionalTestConfig.yml” according to the specifications given below.

a) Under ExternalDependenciesCreated section, the values of the modules should be changed to false.

b) Under Login section, the url should be your URL to the login screen of the application (make sure not to include slashes at the end and the beginning of the path you’re giving)

c) Under SystemConfig and TestSettings sections, the values should be updated according to your environment.

Note : use etc/firefox for browserPath: in an Ubuntu environment and the os: should be mentioned as Ubuntu


3. Add vendor folder to the plugin
Add folder to “symfony/plugins/orangehrmFunctionalTestPlugin/lib”

4. Start the selenium server
Go to “*symfony/plugins/orangehrmFunctionalTestPlugin/lib/vendor from terminal and run following command.
java -jar selenium-server-standalone-2.25.0.jar

5. Run Tests
Go to "*/symfony/plugins/orangehrmFunctionalTestPlugin/test/newpim" from terminal
Run the command  phpunit NewPimFunctionalPluginAllTests.php



