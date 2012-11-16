<?php

class sfWidgetFormSchemaFormatterAddEmployee extends sfWidgetFormSchemaFormatter {

    protected static $index = 1;
    protected static $row = 0;
    protected static $noOfColumns = 1;
    protected static $elements = 0;
    protected
            $rowFormat = "%rowstart% %label% %field%%error%%help%%hidden_fields% %rowend%",
            $errorRowFormat = "<span>\n%errors%</span>\n",
            $helpFormat = '%help%',
            $decoratorFormat = "<form> %content% </form>";

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
        self::$elements++;
        return strtr($this->getRowFormat(), array(
                    '%label%' => $label,
                    '%field%' => $field,
                    '%colspan%' => $this->getAttr($field, 'colspan'),
                    '%rowstart%' => $this->getTrStart($field),
                    '%rowend%' => $this->getTrEnd($field),
                    '%error%' => $this->formatErrorsForRow($errors),
                    '%help%' => $this->formatHelp($help),
                    '%hidden_fields%' => null === $hiddenFields ? '%hidden_fields%' : $hiddenFields,
                ));
    }

    /**
     *
     * @return string
     */
    protected function getTrStart($field) {
        $name = $this->getAttr($field, 'name');
        if ($name == 'user_name' || $name == 'status' || 
                $name == 'user_password' || $name == 're_password' ) {
            return (self::$elements == 1) ? '<li class="loginSection">' : '';
        }
        return (self::$elements == 1) ? '<li class="line">' : '';
    }

    /**
     *
     * @return string
     */
    protected function getTrEnd($field) {
        $br = '';

        if ($this->getAttr($field, 'br') == '') {
            self::$elements = self::$elements + (int) $this->getAttr($field, 'colspan');
            if ((self::$elements == self::$noOfColumns)) {
                $br = '</li>';
                self::$elements = 0;
            }
        } else {
            $br = '</li>';
        }

        return $br;
    }

    /**
     *
     * @return string
     */
    protected function getAttr($field, $attr) {
        $element = new SimpleXMLElement($field);
        $attrVal = '';
        if (isset($element[$attr])) {
            $attrVal = $element[$attr];
        }

        return (string) $attrVal;
    }

}
