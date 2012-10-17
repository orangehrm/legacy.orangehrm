<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FunctionalTestcase
 *
 * @author ravini
 */
include_once '../../lib/vendor/webdriver/__init__.php';

class FunctionalTestcase extends PHPUnit_Framework_TestCase {

    protected $config;
    public $errors = array();
    public $session;
    public $identifier;
    //private $waitForPage = false;

    public function setBrowser($browser) {

        $this->config = new TestConfig();
        if (!isset($this->session)) {
            $wd_host = 'http://localhost:4444/wd/hub';
            $web_driver = new WebDriver($wd_host);

            $browser = "firefox";
            if (!isset($this->session)) {
                $this->session = $web_driver->session($browser, array("nativeEvents" => false, "javascriptEnabled" => true));
            }
        }
    }

    /*private function waitToBecomeVisible($element) {

        for ($i=0; $i < 4; $i++){
            if ($element->displayed()){
                return $element;
            }else{
                sleep(1);
            }
        }
        return $element;
    }*/
    
    /*public function returnWhenVisibled($locator){
        for($i=0; $i<2; $i++){
            //echo $i . "  ";
             echo "waiting" . "   ";
            if ($this->findElement($locator)) {
                echo "waitingif" . "   ";
                echo  "Inside" . $i . "  ";
                return TRUE;
                
            }
            
        }
        echo "Outside" . " " . $i . " ";
        echo "\n";
        return TRUE;
        
        
    } */

    private function findElement($locator, $shouldWait = false) {
       
        $this->identifier = $this->findLocatorStrategy($locator);

        if ($this->identifier == "link text") {
            $locator = substr($locator, 5);
 
        }
        $retry = 2;

        for ($i = 1; $i <= $retry; $i++) {
            $element = null;
            try { 
                
                $element = $this->session->element($this->identifier, $locator);
                if ($element) {
                   // $this->waitForPage = false;
                    return $element;
                }
            } catch (NoSuchElementWebDriverError $e) {      
                    sleep(1);
           
            }
            
        }
       // $this->waitForPage = false;
        return null;

//                
//                
//                $this->selectFrame("rightMenu");
//                //searching in the inner frame
////                echo $i . ")" . $this->identifier . ", " . $locator . "\n";
//                $element = $this->session->element($this->identifier, $locator);
//                
//                  if ($element){
//                      $this->waitForPage = false;
//
//                     return $element; 
//                  }
//           
//            }catch (Exception $e){ 
////                echo $i . ")" . get_class($e) . " " . $e->getMessage() . "\n"; 
//                try {
//                   
//                    $this->selectFrame();
//                    //searching in the top frame------where all the menus are located
////                    echo $i . ")" . $this->identifier . ", " . $locator . "\n";
//                    
//                    $element = $this->session->element($this->identifier, $locator);
//                    if ($element){
//                      $this->waitForPage = false;
//
//                      return $element; 
//                    }
//                }catch (Exception $e){
////                   echo $i . ")" . get_class($e) . " " . $e->getMessage() . "\n";
//                   if ($shouldWait || $this->waitForPage){
////                       echo $i . ")" . "Sleeping\n";
//                       sleep(1);
//                   }else{
////                        echo $i . ")" . "break\n";
//                        break;
//                       
//                   }
//                }     
//          }
//        }
//        
//        $this->waitForPage = false;
//        return null;
    }

    public function findLocatorStrategy($locator) {
        $locatorStrategies = array("id", "name", "xpath", "link text");
        if (substr($locator, 0, 2) == "//") {
            return $locatorStrategies[2];
        } else if (substr($locator, 0, 5) == "link=")
            return $locatorStrategies[3];

        return $locatorStrategies[0];
    }

    public function open($URL) {
        if ($URL) {
            $this->session->open($URL);
        }
    }

    public function setBrowserUrl($browserURL) {
        //if($browserURL){
        //$this->setTimeout(600);
        //timeout value for the session is 10 minutes

        $this->session->open($browserURL);
        //}
    }

    public function type($locator, $value) {

        if (!is_null($value)) {

            $element = $this->findElement($locator);
            if ($element->displayed()) {
                if ($element->text() || $element->attribute("value")) {
                    $element->clear();
                }
                $element->value($this->split_keys($value));
            }
 else {
                sleep(1);

                    $element->clear();
                    $element->value($this->split_keys($value));


}
            /* if ($this->isVisible($locator) &&
              $this->isEditable($locator)) {

              if($this->getText($locator) || $this->getValue($locator)){
              $this->findElement($locator, true)->clear();
              }

              $this->findElement($locator)->value($this->split_keys($value));
              } */
        }
    }

    private function split_keys($toSend) {
        $payload = array("value" => preg_split("//u", $toSend, -1, PREG_SPLIT_NO_EMPTY));
        return $payload;
    }

    public function click($locator, $wait = false) {
        
        $element = $this->findElement($locator);
        $element->click();
        
        }

    public function isElementPresent($locator) {

        $element =null;
        try {
            $element = $this->findElement($locator);
            if (!is_null($element)) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {

            return FALSE;
        }
    }

    public function isVisible($locator) {


        try {
            //echo "Test 2";
            $element = $this->findElement($locator);
            if (!is_null($element)) {
                return $element->displayed();
            } else {
                return false;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function isEditable($locator) {

        try {
            return $this->findElement($locator)->enabled();
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function waitForPageToLoad($timeout) {
        
        $this->selectFrame("rightMenu");
        $state = null;
        $retry = 0;
        sleep(1);
        while ($state != "complete" && ++$retry < $this->config->getTimeoutValue()){
        $state = $this->session->execute(array(
             'script' => "return window.document.readyState",
             'args' => array()));
        sleep(1);
        }
        
    
    }

    public function getText($locator) {
        try {
            $element = $this->findElement($locator);
            if (!is_null($element)) {
                return $element->text();
            } else {
                return null;
            }
        } catch (Exception $e) {

            return null;
        }
    }

    public function clickAndWait($locator) {
        //$this->identifier = $this->findLocatorStrategy($locator);
        $this->click($locator, true);


//        if (!isset($this->config)) {
//            $this->config = new TestConfig();
//        }
        $this->waitForPageToLoad($this->config->getTimeoutValue());
    }

    public function selectFrame($locator=null) {
        //$this->session->frame(array('xpath' => $locator));
        
        try {
            if (!is_null($locator)) {
                
                $this->session->frame(array('id' => NULL));
                $this->session->frame(array('id' => 'rightMenu'));
            } else {
                $this->session->frame(array('id' => NULL));
            }
           
        } catch (NoSuchFrameWebDriverError $e) {
            return;
        }
    }

    public function select($locator, $text) {

        if (substr($text, 0, 6) == "label=") {
            $text = substr($text, 6);
        }
        if ($text) {

            $combobox = $this->findElement($locator);
            $elements = $combobox->elements("tag name", "option");
            //print_r($elements);
            foreach ($elements as $element) {

                if (trim($element->text()) == $text) {

                    $element->click();
                    //echo 'returning after clicking';
                    return;
                }
            }
            //echo "returning because element is not found";
            return false;
        } else {

            return null;
        }
    }

    public function getValue($locator) {
        try {
            $element = $this->findElement($locator);
            if (!is_null($element)) {
                return $element->attribute("value");
            } else {
                return null;
            }
        } catch (Exception $e) {

            return null;
        }
    }

    public function getSelectedLabel($locator) {
        $combobox = $this->findElement($locator);
        $elements = $combobox->elements("tag name", "option");

        foreach ($elements as $element) {

            if ($element->selected()) {
                return $element->text();
            }
        }
        return null;
    }

    //not tested yet
    public function getSelectOptions($locator) {

        $selectOptions = array();

        $combobox = $this->findElement($locator);
        $elements = $combobox->elements("tag name", "option");
        foreach ($elements as $element) {
//            $selectOptions = $element->text();
            array_push($selectOptions, $element->text());
        }
        if (is_null($selectOptions))
            return null;
        return $selectOptions;
    }

    //not tested yet
    public function isTextPresent($locator) {
        if (!is_null($this->getText($locator))) {
            return true;
        } else {
            return false;
        }
    }

//    public function selectMenuItem($locator, $text) {
//
//        $this->identifier = $this->findLocatorStrategy($locator);
//        if ($text) {
//            
//            $mainMenuItem = $this->session->element($this->identifier, $locator);
//            $elements = $mainMenuItem->elements("tag name", "a");
//               
//              foreach ($elements as $element) {
//                    if ($element->text() == $text) {
//                        
//                        $element->click();
//                        return;
//                    }
//                }
//                //ul/li[2]//a[@class='l2_link recruit']
//            
//            
//        }
//        return null;
//    }
//    


    public function isChecked($locator) {

        if ($this->findElement($locator)->selected()) {
            return true;
        } else {

            return false;
        }
    }

    public function check($locator) {
        $element = $this->findElement($locator);

        if (!$element->selected()) {
            $element->click();
        } else {
            return;
        }
    }

    public function unCheck($locator) {
        $element = $this->findElement($locator);
        if ($element->selected()) {
            $element->click();
        } else {
            return;
        }
    }

    public function isSomethingSelected($locator, $text) {
        if ($this->getSelectedLabel($locator) == $text) {
            return true;
        } else {
            return false;
        }
    }

    public function tearDown() {
        $this->session->close();
        parent::tearDown();
    }

   
}

?>