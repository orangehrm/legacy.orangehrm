<?php

class sfWidgetFormSchemaFormatterDefault extends sfWidgetFormSchemaFormatter {

    protected $rowFormat = "<li>%label%\n  %field%%help%\n%hidden_fields%%error%</li>\n";
    protected $errorRowFormat = "%errors%";
    protected $helpFormat = '<br />%help%';
    protected $decoratorFormat = "<form>\n  %content%</form>";
    protected $errorListFormatInARow = "%errors%";
    protected $errorRowFormatInARow  = "<span class='validation-error' generated='true'>%error%</span>\n";
    protected $namedErrorRowFormatInARow = "    <li>%name%: %error%</li>\n";

    public function formatRow($label, $field, $errors = array(), $help = '', $hiddenFields = null) {
        
        return strtr($this->getRowFormat(), array(
            '%label%' => $label,
            '%field%' => $field,
            '%error%' => $this->formatErrorsForRow($errors),
            '%help%' => $this->formatHelp($help),
            '%hidden_fields%' => null === $hiddenFields ? '%hidden_fields%' : $hiddenFields,
        ));
        
    }

}