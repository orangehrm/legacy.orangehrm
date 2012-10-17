<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AddOrganizationLocationPageObject
 *
 * @author intel
 */
class AddOrganizationLocationPageObject extends Component {

    public $txtLocationName = "location_name";
    public $cmbCountry = "location_country";
    public $txtProvince = "location_province";
    public $txtCity = "location_city";
    public $txtAddress = "location_address";
    public $txtPostalCode = "location_zipCode";
    public $txtPhone = "location_phone";
    public $txtFax = "location_fax";
    public $txtNotes = "location_notes";
    public $btnSave = "btnSave";
    public $btnCancel = "btnCancel";

    public function __construct($selenium) {
        parent::__construct($selenium, "Add Organization Location");
    }

    public function addLocation($locationName, $country, $province=null, $city =null, $address=null, $postalCode =null, $phone=null, $fax=null, $notes=null) {
        $this->selenium->selectFrame("relative=top");
        
        $this->selenium->type($this->txtLocationName, $locationName);

        $this->selenium->select($this->cmbCountry, $country);
        if ($province)
            $this->selenium->type($this->txtProvince, $province);
        if ($city)
            $this->selenium->type($this->txtCity, $city);
        if ($address)
            $this->selenium->type($this->txtAddress, $address);
        if ($postalCode)
            $this->selenium->type($this->txtPostalCode, $postalCode);
        if ($phone)
            $this->selenium->type($this->txtPhone, $phone);
        if ($fax)
            $this->selenium->type($this->txtFax, $fax);
        if ($notes)
            $this->selenium->type($this->txtNotes, $notes);
        $this->selenium->clickAndWait($this->btnSave);
        return new ViewOrganizationLocationsPageObject($this->selenium);
    }

    public function editLocation($locationName, $country, $province=null, $city =null, $address=null, $postalCode =null, $phone=null, $fax=null, $notes=null) {
        $this->selenium->click($this->btnSave);
        $this->addLocation($locationName, $country, $province, $city, $address, $postalCode, $phone, $fax, $notes);
    }

}