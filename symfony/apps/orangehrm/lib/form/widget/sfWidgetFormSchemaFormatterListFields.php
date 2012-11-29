<?php

class sfWidgetFormSchemaFormatterListFields extends sfWidgetFormSchemaFormatter {

    protected static $index = 1;
    protected static $noOfColumns = 4;
    protected $noOfFields;
    protected $counter = 0;
    protected $rowFormat = "<li>%label%\n  %field%%help%\n%hidden_fields%%error%</li>\n";
    protected $errorRowFormat = "%errors%";
    protected $helpFormat = '<br />%help%';
    protected $decoratorFormat = "<form>\n  %content%</form>";
    protected $errorListFormatInARow = "%errors%";
    protected $errorRowFormatInARow  = "<span class='validation-error' generated='true'>%error%</span>\n";
    protected $namedErrorRowFormatInARow = "    <li>%name%: %error%</li>\n";
    /**
     *
     * @return int
     */
    public static function getNoOfColumns() {
        return self::$noOfColumns;
    }

    /**
     *
     * @param int $noOfColumns 
     */
    public static function setNoOfColumns($noOfColumns) {
        self::$noOfColumns = $noOfColumns;
    }

    public function setNoOfFields($noOfFields) {
        $this->noOfFields = $noOfFields;
    }
    
    public function getNumberOfFields() {
        
        if (!empty($this->noOfFields)) {
            
            return $this->noOfFields;
            
        } else {
            
            $fields = $this->getWidgetSchema()->getFields();
            $count = count($fields);
            
            foreach ($fields as $field) {
                
                if ($field instanceof sfWidgetFormInputHidden) {
                    $count--;
                }
                
            }
            
            $this->noOfFields = $count;
            
            return $this->noOfFields;
            
        }
                
    }    

    /**
     *
     * @param string $label
     * @param string $field
     * @param array $errors
     * @param string $help
     * @param string $hiddenFields
     * @return string
     */
    public function formatRow($label, $field, $errors = array(), $help = '', $hiddenFields = null) {
        
        $this->counter++;
        
        return strtr($this->getRowFormat(), array(
            '%label%' => $label,
            '%field%' => $field,
            '%error%' => $this->formatErrorsForRow($errors),
            '%help%' => $this->formatHelp($help),
            '%hidden_fields%' => null === $hiddenFields ? '%hidden_fields%' : $hiddenFields,
        ));
        
    }

    public function getRowFormat() {
        
        if ($this->isFirstElement()) {
            return "<ol>" . $this->rowFormat;
        } elseif ($this->isLastElement()) {
            return $this->rowFormat . "</ol>";
        } else {
            return $this->rowFormat;
        }

    }
    
    protected function isFirstElement() {
        
        if ($this->counter == 1) {
            return true;
        }
        
        return false;
        
    }
    
    protected function isLastElement() {
        
        if ($this->counter == $this->getNumberOfFields()) {
            return true;
        }
        
        return false;
        
    }

}

