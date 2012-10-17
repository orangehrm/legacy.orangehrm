Steps to get the tests running:
1. Copy orangehrmFunctionalTestPlugin folder (functional test automation suite) into webroot/symfony/plugins folder
2. Remove test database section from databases.yml
3. Run the jar file in lib/vendor folder:
    execute the below command in the folder where the jar file is located. (Currently located in the lib/vendor folder.)
    java -jar selenium-server-standalone-2.0b2.jar

    This will start the selenium server. The tests will not run if selenium server is not running.

4. Update Config/FunctionalTestConfig.yml with the relevant settings according to your environment.

5. Change the value of the 'time:' in config/FunctionalTestConfig.yml into false

6. Set the statrting day of timesheet as MONDAY.

7. Check All the CheckBoxes under the Time->Attendance->Configuration Fields.
        
8. Run the time test suite (located in test/time folder)
    phpunit TimeFunctionalPluginAllTests.php