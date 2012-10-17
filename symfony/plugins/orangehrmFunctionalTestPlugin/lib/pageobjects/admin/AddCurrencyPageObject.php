<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AddCurrencyPageObject
 *
 * @author madusani
 */
class AddCurrencyPageObject extends Component {

    public $txtCurrencyName = "payGradeCurrency_currencyName";
    public $txtMinSalary = "payGradeCurrency_minSalary";
    public $txtMaxSalary = "payGradeCurrency_maxSalary";
    public $btnSaveCurrency = "btnSaveCurrency";
    public $btnCancelCurrency = "cancelButton";
    public $btnAddCurrency = "btnAddCurrency";
    public $btnDeleteCurrency = "btnDeleteCurrency";
    public $list;

    public function __construct($selenium) {
        parent::__construct($selenium, "Add Currency");
        $this->list = new CurrencyList($selenium, "//form[@id='frmCurrency']", false);
    }

    public function addCurrency($currency, $minimumSalary=null, $maximumSalary =null) {
        $this->selenium->selectFrame("relative=top");
        if ($this->selenium->isElementPresent($this->btnAddCurrency))
            $this->selenium->click($this->btnAddCurrency);
        $this->selenium->type($this->txtCurrencyName, $currency);
        if ($minimumSalary)
            $this->selenium->type($this->txtMinSalary, $minimumSalary);
        if ($maximumSalary)
            $this->selenium->type($this->txtMaxSalary, $maximumSalary);
        $this->selenium->clickAndWait($this->btnSaveCurrency);
    }

    public function editCurrency($currency, $minimumSalary=null, $maximumSalary =null) {
        $this->addCurrency($currency, $minimumSalary, $maximumSalary);
    }

    public function deleteCurrency($currency) {
        $this->selenium->selectFrame("relative=top");
        $this->list->select("Currency", $currency);
        $this->selenium->clickAndWait($this->btnDeleteCurrency);
    }

    public function getSuccessfullMessage() {
        return $this->selenium->getText("//div[@class='messageBalloon_success']");
    }

}