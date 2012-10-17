1. Take a SVN Chekout and install the product

Take the SVN from : https://orangehrm.svn.sourceforge.net/svnroot/orangehrm/branches/2012-ui
Then install the application by opening the browser and typing “http://localhost/*”. 
After installing the product, go to “*\symfony\config\databases.yml” and remove the test: section.


2. Change Values as mentioned.
After the checkout is finished, change the values in the “*\symfony\plugins\orangehrmFunctionalTestPlugin\config\FunctionalTestConfig.yml” according to the specifications given below.

a) Under ExternalDependenciesCreated section, the values of the modules should be changed to false.

b) Under Login section, the url should be your URL to the login screen of the application (make sure not to include slashes at the end and the beginning of the path you’re giving)

c) Under SystemConfig and TestSettings sections, the values should be updated according to your environment.

Note : use etc/firefox for browserPath: in an Ubuntu environment and the os: should be mentioned as Ubuntu


3. Add vendor folder and run the sellenium server
Add folder to “symfony/plugins/orangehrmFunctionalTestPlugin/lib”

Go to “*symfony/plugins/orangehrmFunctionalTestPlugin/lib/vendor

for firefox 15 run the below command:
java -jar selenium-server-standalone-2.25.0.jar

4. Run Tests
Go to "*/symfony/plugins/orangehrmFunctionalTestPlugin/test/newpim"
Run the command  phpunit NewPimFunctionalPluginAllTests.php



