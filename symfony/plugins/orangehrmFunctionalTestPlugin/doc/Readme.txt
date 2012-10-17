Steps to get the tests running:
1. Copy orangehrmFunctionalTestPlugin folder (functional test automation suite) into webroot/symfony/plugins folder

2. Remove "test" database section from webroot/symfony/config/databases.yml

3. Run the jar file in lib/vendor folder:
    execute the below command in the folder where the jar file is located. (Currently located in the lib/vendor folder.)
    java -jar selenium-server-standalone-2.0b2.jar

    This will start the selenium server. The tests will not run if selenium server is not running.
	If you want to keep the browser window opened all time without popping for each and every testcase, then execute 
	the above command with the option --browserSessionReuse 

4. Update Config/FunctionalTestConfig.yml with the relevant settings according to your environment.

5. Change the values of the modules under "ExternalDependenciesCreated" section in config/FunctionalTestConfig.yml into false

6. For the PIM module automation, check All the CheckBoxes under the PIM->configure->optional Fields in the application.

7. For the Leave module automation, Change your system date to 2011-12-20 and after 
	that manually define the leave period of the application as January 1st
 
8. For the Time module automation, Set the statrting day of timesheet as Monday and after
	that check All the CheckBoxes under the Time->Attendance->Configuration Fields in the application
        
9. Run the whole test suite in a console window (located in test folder)
    phpunit AllModuleFunctionalPluginTests.php
	
	
	
	
