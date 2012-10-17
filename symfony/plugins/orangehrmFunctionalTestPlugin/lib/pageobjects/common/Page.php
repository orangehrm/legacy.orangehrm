<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Page
 *
 * @author irshad
 */
abstract class Page {

    /**
     *
     * @var FunctionalTestcase $selenium
     */
    protected $selenium;
    protected $config;
    protected $helper;

    public function __construct(FunctionalTestcase $selenium) {
        $this->selenium = $selenium;
        $this->config = new TestConfig();
        $this->helper = new Helper();
    }

    /**
     *
     *
     * @return FunctionalTestcase
     */
    public function getBrowserInstance() {
        return $this->selenium;
    }

}