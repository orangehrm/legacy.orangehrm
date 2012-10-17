<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EditOrganizationGeneralInformationPageObject
 *
 * @author intel
 */
class EditGeneralInformationPageObject extends Component {

    public $txtOrganizationName = "organization_name";
    public $txtTaxId = "organization_taxId";
    public $txtRegistrationNumber = "organization_registraionNumber";
    public $txtPhone = "organization_phone";
    public $txtFax = "organization_fax";
    public $txtEmail = "organization_email";
    public $txtAddress1 = "organization_street1";
    public $txtAddress2 = "organization_street2";
    public $txtCity = "organization_city";
    public $txtNote = "organization_note";
    public $txtProvince = "organization_province";
    public $txtPostalCode = "organization_zipCode";
    public $cmbCountry = "organization_country";
    public $btnSave = "btnSaveGenInfo";

    public function __construct($selenium) {
        parent::__construct($selenium, "Organization General Information");
    }

    public function editGeneralInformation($organizationName, $taxID=null, $regNum=null, $phoneNum=null, $faxNum=null, $email=null, $address1=null, $address2=null, $city=null, $province=null, $postalCode=null, $country=null, $note=null) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnSave);
        $this->selenium->type($this->txtOrganizationName, $organizationName);
        if ($taxID) {
            $this->selenium->type($this->txtTaxId, $taxID);
        }
        if ($regNum) {
            $this->selenium->type($this->txtRegistrationNumber, $regNum);
        }
        if ($phoneNum) {
            $this->selenium->type($this->txtPhone, $phoneNum);
        }
        if ($faxNum) {
            $this->selenium->type($this->txtFax, $faxNum);
        }
        if ($email) {
            $this->selenium->type($this->txtEmail, $email);
        }
        if ($address1) {
            $this->selenium->type($this->txtAddress1, $address1);
        }
        if ($address2) {
            $this->selenium->type($this->txtAddress2, $address2);
        }
        if ($city) {
            $this->selenium->type($this->txtCity, $city);
        }
        if ($province) {
            $this->selenium->type($this->txtProvince, $province);
        }
        if ($postalCode) {
            $this->selenium->type($this->txtPostalCode, $postalCode);
        }
        if ($country) {
            $this->selenium->select($this->cmbCountry, $country);
        }
        if ($note) {
            $this->selenium->type($this->txtNote, $note);
        }
        $this->selenium->clickAndWait($this->btnSave);
    }

    public function getSuccessfullyAssignedMessage() {
        return $this->selenium->getText("//div[@class='messageBalloon_success']");
    }

}