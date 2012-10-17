<?php

class BasicList {

    public $xpathOfList = '';
    public $selectAllPresent = null;
    public $chkboxName = '';
    protected $selenium = '';
    public $config;

    function __construct($selenium, $xpathOfList, $selectAllPresent=FALSE) {
        $this->config = new TestConfig();
        $this->selenium = $selenium;
        $this->xpathOfList = $xpathOfList;
        //  if ($selectAllPresent)
        $this->selectAllPresent = $this->xpathOfList . "//thead/tr/td[1]/*[@class='checkbox']";
    }

    public function getBrowserInstance() {
        return $this->selenium;
    }

    /**
     *
     * @param <type> $header
     * @return int
     * 
     * 
     */
    public function getLeaveModuleColumnNumber($header) {
        $columnNumber = null;

        //echo " xpath is : " . $this->xpathOfList . "//table/thead/tr/td[1]//.[text()='" . $header . "']";
        for ($columnNumber = 1; $columnNumber <= 10; $columnNumber++) {


            if ($this->selenium->isElementPresent($this->xpathOfList . "//table/thead/tr/th[$columnNumber]//.[text()='" . $header . "']")) {

                return $columnNumber;
            }
        }
        return 0;
    }

    public function verifySortingOrder($array, $header) {
         $this->selenium->selectFrame("rightMenu");
//        $array1=$this->getListedRecordsIntoAnArray();
//        $lastRecord = sizeof($array);
//
//        $new = sizeof($array1);
//        if($new != $lastRecord){
//            //echo "Wrong";
//            return FALSE;
//        }

        $columnNumber = $this->getEmployeeListColumnNumber($header);
        for ($i = 1; $i <= 4; $i++) {
            if ($this->selenium->isElementPresent($this->xpathOfList . "//*/tbody/tr[$i]//td[$columnNumber]/a[text()='" . $array[$i - 1] . "']")) {
                return TRUE;
            }
            return FALSE;
        }
    }

    public function isLeaveItemPresentInColumn($header, $itemName) {
        if ($header == "Vacancy")
            $columnNumber = $this->getVacancyListColumnnNumber($header);
        else if ($header == "Candidate")
            $columnNumber = $this->getCandidateListColumnnNumber($header);
        else
            $columnNumber = $this->getLeaveModuleColumnNumber($header);
        //echo "\n Column number is : $columnNumber \n";

        if ($columnNumber != FALSE) {
            //echo "xpath for item : " . $this->xpathOfList . "//*/tr//td[$columnNumber]//.[text()='" . $itemName . "']";

            return $this->selenium->isElementPresent($this->xpathOfList . "//*/tr//td[$columnNumber]//.[text()='" . $itemName . "']");
        }
        else
            return FALSE;
    }

    public function getColumnNumber($header) {
        $columnNumber = null;
        
        //echo " xpath is : " . $this->xpathOfList . "//table/thead/tr/td[1]//.[text()='" . $header . "']";
        for ($columnNumber = 1; $columnNumber <= 10; $columnNumber++) {

            //echo "\n" . $this->xpathOfList . "//table/thead/tr/th[$columnNumber]//.[text()='" . $header . "']";

            if ($this->selenium->isElementPresent($this->xpathOfList . "//table/thead/tr/*[$columnNumber]//.[text()='" . $header . "']")) {

                return $columnNumber;
//            }   else{
//                    echo "came to false" . $columnNumber .", the xpath is " . $this->xpathOfList . "//table/thead/tr/td[$columnNumber]//.[text()='" . $header . "']";
            }
        }
        return 0;
    }

   
    
    public function getRowNumber($item) {
        $rowNumber = null;
        for ($rowNumber = 1; $rowNumber <= 20; $rowNumber++) {
            if ($this->selenium->isElementPresent($this->xpathOfList . "//table/tbody/tr[$rowNumber]//.[text()='" . $item . "']")) {
                return $rowNumber;
                //echo "row Number" . $rowNumber;
            }
        }
    }

    public function getEmployeeListColumnNumber($header){
        $columnNumber = null;

        //echo " xpath is : " . $this->xpathOfList . "//table/thead/tr/td[1]//.[text()='" . $header . "']";
        for ($columnNumber = 1; $columnNumber <= 10; $columnNumber++) {

//            echo "\n" . $this->xpathOfList . "//table/thead/tr/td[$columnNumber]//.[text()='" . $header . "']";
            
            if ($this->selenium->isElementPresent($this->xpathOfList . "//table/thead/tr/th[$columnNumber]//.[text()='" . $header . "']")) {

                return $columnNumber;
//            }   else{
//                    echo "came to false" . $columnNumber .", the xpath is " . $this->xpathOfList . "//table/thead/tr/td[$columnNumber]//.[text()='" . $header . "']";
            }
        }
        return 0;
    }
    
    public function getAttachmentListColumnNumber($header){
        $columnNumber = null;

        //echo " xpath is : " . $this->xpathOfList . "//table/thead/tr/td[1]//.[text()='" . $header . "']";
        for ($columnNumber = 1; $columnNumber <= 10; $columnNumber++) {

//            echo "\n" . $this->xpathOfList . "//table/thead/tr/td[$columnNumber]//.[text()='" . $header . "']";
            
            if ($this->selenium->isElementPresent($this->xpathOfList . "//table/thead/tr/td[$columnNumber]//.[text()='" . $header . "']")) {

                return $columnNumber;
//            }   else{
//                    echo "came to false" . $columnNumber .", the xpath is " . $this->xpathOfList . "//table/thead/tr/td[$columnNumber]//.[text()='" . $header . "']";
            }
        }
        return 0;
    }

    /**
     *
     * @param <type> $header
     * @param <type> $itemName
     * @return Boolean
     */
    public function isItemPresentInColumn($header, $itemName) {

        if(!is_numeric($header)){
            $columnNumber = $this->getColumnNumber($header);
        }else
            $columnNumber = $header;

        if ($columnNumber != FALSE) {
            //echo "xpath for item : " . $this->xpathOfList . "//*/tr/td[$columnNumber]//.[text()='" . $itemName . "']";

            return $this->selenium->isElementPresent($this->xpathOfList . "//*/tr/td[$columnNumber]//.[text()='" . $itemName . "']");
        }
        else
            return FALSE;
    }

    public function isMatchesFoundInColumn($header, $itemName) {
        $columnNumber = $this->getColumnNumber($header);
        // echo "\n Column number is : $columnNumber \n";

        if ($columnNumber != FALSE) {
            //   echo "xpath for item : " . $this->xpathOfList . "//*/tr//td[$columnNumber]//.[contains(text(),'" . $itemName . "')]";
            return $this->selenium->isElementPresent($this->xpathOfList . "//*/tr//td[$columnNumber]//.[contains(text(),'" . $itemName . "')]");
        }
        else
            return FALSE;
    }

    /**
     *
     * @param <type> $header
     * @param <type> $itemName
     * @return Boolean
     */
    public function select($header, $itemName) {
        if ($header == "Vacancy")
            $columnNumber = $this->getVacancyListColumnNumber($header);
        else if ($header == "Candidate")
            $columnNumber = $this->getCandidateListColumnNumber($header);
        else  if($header == "First (& Middle) Name" || $header == "Id")
            $columnNumber = $this->getEmployeeListColumnNumber($header);
         else if($header == "File Name")
         {
             $columnNumber = $this->getAttachmentListColumnNumber($header);
         } else {
            $columnNumber = $this->getColumnNumber($header);
         }
        //echo "col num " . $columnNumber;
        if ($columnNumber != FALSE) {
            $this->selenium->click($this->xpathOfList . "//tr/td[$columnNumber]//a[text()='" . $itemName . "']//../../td/input[@type!='hidden']");

            return $this->selenium->isChecked($this->xpathOfList . "//tr/td[$columnNumber]//a[text()='" . $itemName . "']//../../td/input[@type!='hidden']");
        }
        else
            return FALSE;
    }

    public function getCandidateListColumnNumber($header) {
        $columnNumber = null;


        for ($columnNumber = 1; $columnNumber <= 10; $columnNumber++) {


            if ($this->selenium->isElementPresent($this->xpathOfList . "//table/thead/tr/th[$columnNumber]//.[text()='" . $header . "']")) {
                return $columnNumber;
            }
        }
        return 0;
    }

    /**
     *
     * @return Boolean
     */
    public function selectAllInTheList() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->selectAllPresent);
        return $this->selenium->isChecked($this->selectAllPresent);
    }

    /**
     *
     * @param <type> $header
     * @param <type> $itemName
     * @return BasicList
     */
    public function clickOntheItem($header, $itemName) {
        
        $columnNumber = $this->getColumnNumber($header);
       
        //echo $i;
        if ($columnNumber != FALSE) {
            
            $this->selenium->isTextPresent($this->xpathOfList . "//tr/td[$columnNumber]//a[text()='" . $itemName . "']");
            echo //tr/td[$columnNumber]//a[text()='" . $itemName . "'];
            $this->selenium->click($this->xpathOfList . "//tr/td[$columnNumber]//a[text()='" . $itemName . "']");
            
            
            return $this;
            
        }
        else
            return FALSE;
        
    }

    public function clickOntheAbsoluteItem($colNumber, $itemName) {
        $rowNumber = $this->getRowNumber($itemName);
        if ($rowNumber != FALSE) {

            $this->selenium->click($this->xpathOfList . "//tr[$rowNumber]/td[$colNumber]/a");
            return $this;
        }
        else
            return FALSE;
        $this->selenium->click($this->xpathOfList . "//tr[$rowNum]/td[$colNumber]//a");
    }

    /**
     *
     * @param type $header
     * @param type $itemName
     * @return EditCandidate 
     */
    public function verifyAllItemsDeleted() {
        if ($this->selenium->isVisible($this->selectAllPresent)) {
            return FALSE;
        }
        return TRUE;
    }

    /*public function isOnlyItemsListed($array, $header) {
        echo "Woow";
        $this->selenium->selectFrame("rightMenu");
        $lastRecord = sizeof($array);
        $array=$this->getListedRecordsIntoAnArray();
        echo "List Record" . sizeof($array);
        echo "Test Record   ".$lastRecord;
        echo $header;
        $new = sizeof($array);
        if($new != $lastRecord){
            echo "Wrong";
            return FALSE;
        }
        elseif ($this->selenium->isElementPresent($this->xpathOfList . "//table/tbody/tr[" . $lastRecord . "]")) {
           
            return FALSE;
        }

        for ($i = $lastRecord; $i > 0; $i--) {
             
            if (!$this->isItemPresentInColumn($header, $array[$i - 1])) {
                echo "ya    ";
                return false;

            }
        }

        return true;
    }*/

    public function isOnlyItemsListed($array, $header) { //column header and array of items goin to chk
        $this->selenium->selectFrame("rightMenu");
        $lastRecord = sizeof($array); //size of array passsed
        $newlast=$lastRecord+1;
        if ($this->selenium->isElementPresent($this->xpathOfList . "//table/tbody/tr[" . $newlast . "]")) {
            return FALSE;
        }
        $columnNumber = $this->getColumnNumber($header);

        for ($i = $lastRecord; $i > 0; $i--) {
            if (!$this->isItemPresentInColumn($columnNumber, $array[$i - 1])) {

                return false;
            }
        }

        return true;
    }


    public function readValue($cell) {
        return $this->selenium->getText($cell);
    }

    public function getListedRecordsIntoAnArray() { //after search get results to an array by row and column and retun the results array
        $tableBodyXPath = $this->xpathOfList . "//table/tbody";
        $resultArray = null;


        $row = 1;
        $nextRow = $tableBodyXPath . "/tr[" . $row . "]";
        //echo $tableBodyXPath . "/tr[". $row . "]";

        while ($this->selenium->isElementPresent($nextRow)) {

            // echo $nextRow . "\n";
            $column = 1;
            $nextColumn = $nextRow . "/td[" . $column . "]";
            while ($this->selenium->isElementPresent($nextColumn)) {

                //echo $nextColumn . "\n";
                //echo $tableBodyXPath . "/tr[". $row . "]".  "/td[". $column . "]";
                $resultArray[$row][$this->getColumnName($column)] = $this->readValue($tableBodyXPath . "/tr[" . $row . "]" . "/td[" . $column . "]");
                $column++;
                $nextColumn = $nextRow . "/td[" . $column . "]";
            }
            $row++;
            $nextRow = $tableBodyXPath . "/tr[" . $row . "]";
        }
        //print_r($resultArray);
        return $resultArray;
    }

    public function getColumnName($columnNumber) {
        // echo $this->xpathOfList . "//table/thead/tr/th[$columnNumber] : " . $this->selenium->getText($this->xpathOfList . "//table/thead/tr/th[$columnNumber]") . "\n";

        return $this->selenium->getText($this->xpathOfList . "//table/thead/tr/th[$columnNumber]");
    }

    public function isRecordsPresentInList($expectedTwoDimensionalArray, $checkSize=false) { //ExpectedTwiDimention-> results Passed by yml
        $actualArray = $this->getListedRecordsIntoAnArray();
        //print_r($actualArray);
        if ($checkSize == true) {  //check size of actual array sent in yml
            if (count($expectedTwoDimensionalArray) != count($actualArray)) {
                echo "Expected: " . count($expectedTwoDimensionalArray) . " rows, but " .
                count($actualArray) . "rows are listed\n";
                return false;
            }
        }

        //print_r($actualArray);
        //print_r($expectedTwoDimensionalArray);

        for ($i = 0; $i < count($expectedTwoDimensionalArray); $i++) { //compare with actual value in count and itterate
// i expected array for loope
            $matching = false; //initially false
            for ($j = 1; $j <= count($actualArray); $j++) {


                //echo "expected: "; print_r($expectedTwoDimensionalArray); echo "actual: "; print_r($actualArray);
                $matchingPart = array_intersect_assoc($expectedTwoDimensionalArray[$i], $actualArray[$j]); //get comman things from array
                //print_r($matchingPart);
                if (count(array_diff_assoc($expectedTwoDimensionalArray[$i], $matchingPart)) == 0) { //diff of actual and expected arrays.if no diferent 
                    $matching = true;
                    break;

                    //echo "\nmatching\n"; echo $j . "\nexpected array: " ;print_r($expectedTwoDimensionalArray[$i]);echo $j . "\nmatching part: "; print_r($matchingPart); echo $j . "\narray diff: "; print_r(array_diff_assoc($expectedTwoDimensionalArray[$i], $matchingPart));
                } else {

                    $matching = false;
                    //echo "record number: ". $j. "does not match\n";
                    //echo "\nnot matching\n";echo $j . "\nexpected array: " ; print_r($expectedTwoDimensionalArray[$i]);echo $j . "\nmatching part: "; print_r($matchingPart);echo $j . "\narray diff: "; print_r(array_diff_assoc($expectedTwoDimensionalArray[$i], $matchingPart));
                }
            }
            //If one of the records does not have a match, return false.
            if ($matching == false)
                return $matching;
        }

        return $matching;
    }

}