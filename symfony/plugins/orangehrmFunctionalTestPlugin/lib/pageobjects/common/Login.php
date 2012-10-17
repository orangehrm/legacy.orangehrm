<?php

class Login {

    private $txtUsername;
    private $txtPassword;
    private $btnLogin;
    private $selenium;
    private $config;

    public function __construct($selenium) {
        $this->txtUsername = 'txtUsername';
        $this->txtPassword = 'txtPassword';

        $this->btnLogin = 'btnLogin';


        $this->selenium = $selenium;
        $this->config = new TestConfig();
    }

    /**
     *
     * @param <type> $username
     * @param <type> $password
     * @return Boolean
     */
    public function homePageLogin($username, $password) {

        $this->selenium->open($this->config->getLoginURL());
        $this->selenium->type($this->txtUsername, $username);
        $this->selenium->type($this->txtPassword, $password);
        $this->selenium->click($this->btnLogin);
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());

        if ($this->getWelcomeText()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     *
     * @return String
     */
    public function getWelcomeText() {
        $this->selenium->selectFrame();
        
        $welcomeText = $this->selenium->getText("//ul[@id='option-menu']/li[1]");
        $this->selenium->selectFrame("rightMenu");
        return $welcomeText;
    }

}