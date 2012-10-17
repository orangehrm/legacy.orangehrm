<?php

require_once 'PHPUnit/Framework.php';

class SimpleTestListener implements PHPUnit_Framework_TestListener {
    private $i=0;
    
    public function writeFile(PHPUnit_Framework_Test $test1){
        //echo get_class($test1) . "\n";
        
    $file=fopen("Log.txt","a+") or exit("Unable to open file!");
      
        echo fwrite($file,$test1->getName() . "  ");
        return $file;
        
    }

    public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time) {
        printf(
                "Test '%s' failed\n",
                $test->getName()
              );
        
        $file2 =$this->writeFile($test);
        fprintf($file2, "Failure: " . $e->getMessage());
        fclose($file2);
        
    }
    
//    
   public function addError(PHPUnit_Framework_Test $test, Exception $e, $time) {
        printf(
                "Error while running test '%s'.\n" . "\n". $e->getMessage(),
                $test->getName()
        );
       
        $file2= $this->writeFile($test);
        fprintf($file2,"Error: ". $e->getMessage());        
        fclose($file2);
    }

   

    public function
    addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time) {
//        printf(
//                "Test '%s' is incomplete.\n",
//                $test->getName()
//        );
    }

    public function
    addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time) {
//        printf(
//                "Test '%s' has been skipped.\n",
//                $test->getName()
//        );
    }

    public function startTest(PHPUnit_Framework_Test $test) {
//        printf(
//                "Test '%s' started.\n",
//                $test->getName()
//        );
    }

    public function endTest(PHPUnit_Framework_Test $test, $time) {
        printf(
                "Test '%s' Passed \n" ,
                $test->getName()
        );
        
            $file2 = $this->writeFile($test);
            fprintf($file2, " Ended".  "\n");
            fclose($file2); 
        
      }

    public function
    startTestSuite(PHPUnit_Framework_TestSuite $suite) {
//        printf(
//                "TestSuite '%s' started.\n",
//                $suite->getName()
//        );
    }

    public function
    endTestSuite(PHPUnit_Framework_TestSuite $suite) {
//        printf(
//                "TestSuite '%s' ended.\n",
//                $suite->getName()
//        );
    }

}