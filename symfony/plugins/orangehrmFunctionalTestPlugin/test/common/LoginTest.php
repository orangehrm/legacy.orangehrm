<?php

class LoginTest extends FunctionalTestcase {

    public function setUp() {
        $helper = new Helper();
        $this->config = new TestConfig();
        $menu = new Menu();
        
        $this->setBrowser(Helper::getBrowserString());
        $this->setBrowserUrl($this->config->getBrowserURL());
    }

    //nusky
    public function testSuccessfulLogin() {

        //echo "Tomorrow is ".date("Y/m/d", $tomorrow);


        $log = new Login($this);

        $this->assertTrue($log->homePageLogin("admin", "admin"));
        
        Helper::logOutIfLoggedIn($this);
    }

    public function testLoginWithWrongPassword() {
        $log = new Login($this);
        $this->assertFalse($log->homePageLogin("Admin", "Admin"));
        Helper::logOutIfLoggedIn($this);
        
    }

}