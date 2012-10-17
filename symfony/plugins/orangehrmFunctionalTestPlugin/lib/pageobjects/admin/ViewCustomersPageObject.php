<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewJobTitlesPageObject
 *
 * @author intel
 */
class ViewCustomersPageObject extends Component {

    public $btnAdd = "btnAdd";
    public $btnDelete = "btnDelete";
    public $list;
    public $dialogDeleteBtn = "dialogDeleteBtn";
    public $dialogCancelBtn = "dialogCancelBtn";

    public function __construct($selenium) {
        parent::__construct($selenium, "View Customers");
        $this->list = new CustomersList($selenium, "//form[@id='frmList_ohrmListComponent']", true);
    }

    public function goToAddCustomer() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnAdd);
        return new AddCustomerPageObject($this->selenium);
    }

    public function deleteCustomer($customer) {
        $this->selenium->selectFrame("relative=top");
        $this->list->select("Customer", $customer);
        $this->selenium->click($this->btnDelete);
        $this->selenium->clickAndWait($this->dialogDeleteBtn);
    }

    public function deleteAllCustomers() {
        $this->selenium->selectFrame("relative=top");
        $this->list->selectAllInTheList();
        $this->selenium->click($this->btnDelete);
        $this->selenium->clickAndWait($this->dialogDeleteBtn);
    }

    public function getSuccessfullMessage() {
        return $this->selenium->getText("//div[@class='messageBalloon_success']");
    }

}