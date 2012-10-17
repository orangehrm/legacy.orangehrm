Steps to get the tests running:
1. Copy orangehrmFunctionalTestPlugin folder (functional test automation suite) into webroot/symfony/plugins folder
2. Remove test database section from databases.yml
3. Run the jar file in lib/vendor folder:
    execute the below command in the folder where the jar file is located. (Currently located in the lib/vendor folder.)
    java -jar selenium-server-standalone-2.0b2.jar

    This will start the selenium server. The tests will not run if selenium server is not running.

4. Update Config/FunctionalTestConfig.yml with the relevant settings according to your environment.

5. Change the value of the 'leave:' in config/FunctionalTestConfig.yml into false

6. Change your system date to 2011-12-20

7. Manually define the leave period of the application as January 1st
        
8. Run the leave test suite (located in test/leave folder)
    phpunit LeaveFunctionalPluginAllTests.php